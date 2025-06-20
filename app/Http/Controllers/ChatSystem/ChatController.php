<?php

namespace App\Http\Controllers\ChatSystem;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Chat\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Notifications\ChatNotification;
use App\Notifications\ChatBellNotification;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function getContacts()
    {
        try {
            $user = Auth::user();

            $messages = Message::where('sender_id', $user->id)
                ->orWhere('receiver_id', (int) $user->id)
                ->with(['sender:id,first_name,last_name,image_url', 'receiver:id,first_name,last_name,image_url'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy(function($message) use ($user) {
                    return (int) $message->sender_id === (int) $user->id ? (int) $message->receiver_id : (int) $message->sender_id;
                })
                ->map(function($messages) use ($user) {
                    $lastMessage = $messages->first();
                    $contact =  (int) $lastMessage->sender_id === (int) $user->id ? $lastMessage->receiver : $lastMessage->sender;
                    return [
                        'id' => $contact->id,
                        'first_name' => $contact->first_name,
                        'last_name' => $contact->last_name,
                        'image' => $contact->image_url,
                        'type' => $contact->roles[0]['name'],
                        // 'last_message' => $lastMessage->message,
                        // 'timestamp' => $lastMessage->created_at,
                    ];
                })
                ->values();

            return response()->json([
                'status' => true,
                'contacts' => $messages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch contacts. Please check your connection.',
                'error' => $e->getMessage()
            ], 503);
        }
    }

    public function getChat($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid user ID',
                    'errors' => $validator->errors()
                ], 422);
            }

        $user = Auth::user();

        //  Block user from accessing chat with themselves
        // if ($user->id == $id) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => "You can't chat with yourself."
        //     ], 403);
        // }

        $messages = Message::where(function($query) use ($user, $id) {
                $query->where('sender_id', $user->id)
                      ->where('receiver_id', (int) $id);
            })
            ->orWhere(function($query) use ($user, $id) {
                $query->where('sender_id', (int) $id)
                      ->where('receiver_id', $user->id);
            })
            ->with(['sender:id,first_name,last_name,image_url'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($message) use ($user) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'is_mine' => (int) $message->sender_id === (int) $user->id,
                    'sender' => [
                        'id' => (int) $message->sender->id,
                        'first_name' => $message->sender->first_name,
                        'last_name' => $message->sender->last_name,
                        'image' => $message->sender->image_url
                    ],
                    'sent_at' => $message->created_at
                ];
            });

            return response()->json([
                'status' => true,
                'messages' => $messages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch messages. Please check your connection.',
                'error' => $e->getMessage()
            ], 503);
        }
    }

    public function SendChat($id, Request $request)
    {
        $validator = Validator::make(
            array_merge(['id' => $id], $request->all()),
            [
                'id' => 'required|integer|exists:users,id',
                'message' => 'required|string|max:1000',
                'is_active_chat' => 'nullable|boolean'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        // Prevent sending a message to yourself
        // if ($user->id == $id) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => "You can't chat with yourself."
        //     ], 403);
        // }


        try {
            // Create and save the message
            $message = Message::create([
                'sender_id' => $user->id,
                'receiver_id' => (int)$id,
                'message' => $request->message
            ]);

            // Get the receiver
            $receiver = User::find((int) $id);

            // Only send notification if the receiver is not actively viewing this chat
            $isActiveChat = $request->input('is_active_chat', false);

            // If the receiver is not viewing this chat, send both types of notifications
            if (!$isActiveChat) {

                // Send bell notification for the notification center
                $receiver->notify(new ChatBellNotification($request->message, $user, (int)$id));
            }

            // Send real-time chat notification
            $receiver->notify(new ChatNotification($request->message, $user, (int)$id));

            return response()->json([
                'status' => true,
                'message' => 'Message sent successfully',
                'data' => [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender' => [
                        'id' => $user->id,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'image' => $user->image_url
                    ],
                    'timestamp' => $message->created_at
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to send message. Please check your connection.',
                'error' => $e->getMessage(),
                'retry' => true
            ], 503);
        }
    }
}

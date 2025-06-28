<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AiRankController extends Controller
{

  public function index(Request $request)
  {
    $parser = new \Smalot\PdfParser\Parser();

      $cv = Auth::user()->cv ;

      $pdf = $parser->parseFile($cv);

      $text = $pdf->getText();

    return response()->json(['message' => $text]);
  }
}

@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm']) !!}></textarea>

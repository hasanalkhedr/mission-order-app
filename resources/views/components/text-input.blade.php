@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'appearance-none block w-full bg-white text-gray-700 border border-blue-700 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'type'=>'text']) !!}>

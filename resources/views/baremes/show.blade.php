@extends('layouts.app')
@section('title', __('Show bareme'))
@section('content')

@section('title', __('Show bareme'))
<div class="m-4">
    <div class="mb-6">
        <a href="{{ url(route('baremes.index')) }}">
            <button
                class="inline-block px-6 py-2.5 text-white font-medium text-xs leading-tight uppercase rounded-full shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out blue-bg">{{ __('Back') }}</button>
        </a>
    </div>
    <div class="relative z-0 mb-6 w-full group">
        <input type="text" name="name"
            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
            disabled value="{{ $bareme->pays }}" />
        <label for="name"
            class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">{{ __('Pays') }}</label>
    </div>
    <div class="relative z-0 mb-6 w-full group">
        <input type="text" name="currency"
            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
            disabled value="{{ $bareme->currency }}" />
        <label for="currency"
            class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">{{ __('Currency') }}</label>
    </div>
    <div class="relative z-0 mb-6 w-full group">
        <input type="text" name="pays_per_day"
            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
            disabled value="{{ $bareme->pays_per_day }}" />
        <label for="pays_per_day"
            class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">{{ __('pays_per_day') }}</label>
    </div>
    <div class="relative z-0 mb-6 w-full group">
        <input type="text" name="meal_cost"
            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
            disabled value="{{ $bareme->meal_cost }}" />
        <label for="meal_cost"
            class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">{{ __('meal_cost') }}</label>
    </div>
    <div class="relative z-0 mb-6 w-full group">
        <input type="text" name="accomodation_cost"
            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
            disabled value="{{ $bareme->accomodation_cost }}" />
        <label for="accomodation_cost"
            class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">{{ __('accomodation_cost') }}</label>
    </div>

    {{-- @hasanyrole('human_resource|sg|head') --}}
    @if (auth()->user()->employee->hasRole('controller') || auth()->user()->employee->hasRole('sg'))
        <button
            class="text-white hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center blue-bg"
            data-modal-toggle="editModal-{{$bareme->id}}">
            {{ __('Edit') }}
        </button>
        <button
            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
            data-modal-toggle="deleteModal-{{$bareme->id}}">
            {{ __('Delete') }}
        </button>
    @endif
    {{-- @endhasanyrole --}}
</div>

@include('partials.modals._edit-bareme')
@include('partials.modals._delete-bareme')

@endsection

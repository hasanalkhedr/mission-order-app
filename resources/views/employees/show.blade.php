@extends('layouts.app')

@section('content')
@section('title', __('Show Employee'))
@push('head')
    <style>
        [x-cloak] {
            display: none;
        }

        .svg-icon {
            width: 1em;
            height: 1em;
        }

        .svg-icon path,
        .svg-icon polygon,
        .svg-icon rect {
            fill: #333;
        }

        .svg-icon circle {
            stroke: #4691f6;
            stroke-width: 1;
        }
    </style>
@endpush
<div class="m-4">
    <div class="mb-6">
        <a href="{{ url(route('employees.index')) }}">
            <button
                class="inline-block px-6 py-2.5 text-white font-medium text-xs leading-tight uppercase rounded-full shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out blue-bg">
                {{ __('Back') }}</button>
        </a>
    </div>
    <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-2/3 px-3">
            <div class="grid md:grid-cols-2 md:gap-6 mt-7">
                <div class="relative z-0 mb-6 w-full group">
                    <input type="text" name="first_name"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        disabled value="{{ $employee->first_name }}" />
                    <label for="first_name"
                        class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                        {{ __('First name') }}
                    </label>
                </div>
                <div class="relative z-0 mb-6 w-full group">
                    <input type="text" name="last_name"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        disabled value="{{ $employee->last_name }}" />
                    <label for="last_name"
                        class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                        {{ __('Last name') }}
                    </label>
                </div>
            </div>
            <div class="relative z-0 mb-6 w-full group">
                <input type="email" name="email"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    disabled value="{{ $employee->email }}" />
                <label for="email"
                    class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                    {{ __('Email Address') }}
                </label>
            </div>
        </div>
        <div class="w-1/3 px-3">
            <div class="relative w-full h-full mx-auto">
                @if ($employee->profile_image)
                    <img class="w-1/2 h-auto object-cover" src="{{ asset('storage/app/public/' . $employee->profile_image) }}" alt="Profile Image" class="object-cover w-full h-full">
                @else
                    <img class="w-1/2 h-auto object-cover" src="{{ Vite::asset('resources/images/default-avatar.jpg') }}" alt="Profile Image" class="object-cover w-full h-full">
                @endif
            </div>
        </div>
    </div>
    <div class="grid md:grid-cols-2 md:gap-6">
        <div class="relative z-0 mb-6 w-full group">
            <input type="text" name="phone"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                disabled value="{{ $employee->phone }}" />
            <label for="phone"
                class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                {{ __('Phone number') }}
            </label>
        </div>
        <div class="relative z-0 mb-6 w-full group">
            <input type="text" name="role_id"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                disabled
                value="{{ implode('|', $employee->roles)
                    /*config('globals.roles.'.$employee->role) */}}" />
            <label for="role_id"
                class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                {{ __('Roles') }}
            </label>
        </div>
    </div>
    <div class="relative z-0 mb-6 w-full group">
        @if ($employee->department)
            <input type="text" name="department_id"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                disabled value="{{ $employee->department->name }}" />
        @else
            <input type="text" name="department_id"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                disabled value="None" />
        @endif
        <label for="department_id"
            class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">{{ __('Department') }}</label>
    </div>
    <div class="relative z-0 mb-6 w-full group">
        @if ($employee->position)
            <input type="text" name="position"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                disabled value="{{ $employee->position }}" />
        @else
            <input type="text" name="department_id"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                disabled value="None" />
        @endif
        <label for="position"
            class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">{{ __('Fonction Administrative') }}</label>
    </div>
    <div class="relative z-0 mb-6 w-full group">
        @if ($employee->administrativ_residence)
            <input type="text" name="department_id"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                disabled value="{{ $employee->administrativ_residence }}" />
        @else
            <input type="text" name="department_id"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                disabled value="None" />
        @endif
        <label for="department_id"
            class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">{{ __('Fonction Administrative') }}</label>
    </div>
    <div class="relative z-0 mb-6 w-full group">
        @if ($employee->service)
            <input type="text" name="department_id"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                disabled value="{{ $employee->service }}" />
        @else
            <input type="text" name="department_id"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                disabled value="None" />
        @endif
        <label for="department_id"
            class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">{{ __('Service') }}</label>
    </div>
    <div class="grid md:grid-cols-2 md:gap-6">
        <div class="relative z-0 mb-6 w-full group">
            <p class="mb-2 text-sm font-medium blue-color">
                {{ __('Submit Requests') }}</p>
            <div class="mt-2 flex flex-row">
                <input type="checkbox" disabled name="allow_order" {{ $employee->allow_order ? 'checked' : '' }}>
            </div>
        </div>
        <div class="relative z-0 mb-6 w-full group">
            <p class="mb-2 text-sm font-medium blue-color">
                {{ __('Receive Emails') }}</p>
            <div class="mt-2 flex flex-row">
                <input type="checkbox" disabled name="recieve_email" {{ $employee->recieve_email ? 'checked' : '' }}>
            </div>
        </div>
    </div>
    {{-- @hasanyrole('human_resource|sg|head') --}}
    @if (auth()->user()->employee->hasRole('hr') || auth()->user()->employee->hasRole('sg'))
        <button
            class="text-white hover:bg-blue-400 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center blue-bg mr-3"
            data-modal-toggle="editProfileModal-{{$employee->id}}">
            {{ __('Edit Employee Profile') }}
        </button>
    @endif
    {{-- @endhasanyrole --}}
    @if (auth()->user()->employee->id == $employee->id ||
            (auth()->user()->employee->hasRole('hr') || auth()->user()->employee->hasRole('sg')))
        {{-- auth()->user()->hasRole(['human_resource', 'sg', 'head'])) --}}
        <button
            class="text-white hover:bg-blue-400 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center blue-bg"
            data-modal-toggle="editPasswordModal">
            {{ __('Edit Employee Password') }}
        </button>
    @endif
</div>
@include('partials.modals._edit-employee')

@include('partials.modals._edit-password')

@endsection

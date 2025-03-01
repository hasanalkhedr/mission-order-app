<!-- resources/views/tournees/show.blade.php -->
@php
    use App\Models\Department;
@endphp
@extends('layouts.app')
@section('title', $tournee->order_number.'-'.$tournee->employee->first_name.' '.$tournee->employee->last_name)
@section('content')
<h2 class="text-2xl font-bold mb-2 text-blue-700">Demander une tournee</h2>
    <div class="w-11/12">
        <div class="flex flex-wrap -mx-1 mb-2">
            <div class="w-1/5 px-3">
                <x-label>
                    Tournee #
                </x-label>
                <label
                    class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $tournee->order_number }}</label>
            </div>
            <div class="w-1/5 px-3">
                <x-label>
                    Ordre de Tournee Date
                </x-label>
                <label
                    class="ms-1 text-sm font-bold {{$tournee->order_date > $tournee->start_date ? 'text-red-600 bg-yellow-400' : 'text-blue-600'}} dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $tournee->order_date->format('d/m/Y') }}</label>
            </div>
            <div class="w-1/5 px-3">
                <x-label>
                    Etat de la demande
                </x-label>
                <label
                    class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ __($tournee->status) }}</label>
            </div>
            <div class="w-1/5 px-3">
                <x-label>
                    Nom, Prénom
                </x-label>
                <label
                    class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $tournee->employee->first_name }}
                    {{ $tournee->employee->last_name }}</label>
            </div>
            <div class="w-1/5 px-3">
                <x-label>
                    Dép / Antenne
                </x-label>
                <label
                    class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $tournee->employee->department->name }}</label>
            </div>
        </div>
        <div class="flex flex-wrap -mx-1 mb-2">
            <div class="w-full px-3">
                <x-label>
                    Objet<span class="text-red-500">*</span>
                </x-label>
                <label
                    class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $tournee->purpose }}</label>
            </div>
        </div>
        <div class="flex flex-wrap -mx-1 mb-2">
            <div class="w-1/2 px-3">
                <x-label>
                    Lieu de départ<span class="text-red-500">*</span>
                </x-label>
                <label
                    class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $tournee->departure_location }}</label>
            </div>
            <div class="w-1/2 px-3">
                <x-label>
                    Lieu d'arrivée<span class="text-red-500">*</span>
                </x-label>
                <label
                    class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $tournee->arrive_location }}</label>
            </div>
        </div>
        <div class="flex flex-wrap -mx-1 mb-2">
            <div class="w-3/4 px-3">
                <x-label class="inline">
                    Débute le : Date & Heure :<span class="text-red-500">*</span>
                </x-label>
                <label
                    class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $tournee->start_date->format('d/m/Y') }}
                    at {{ $tournee->start_time }}</label>
                <x-label  class="inline">
                    S'achève le : Date & Heure :<span class="text-red-500">*</span>
                </x-label>
                <label
                    class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $tournee->end_date->format('d/m/Y') }}
                    at {{ $tournee->end_time }}</label>

            </div>
            <div class="w-1/4">
                @if (auth()->user()->employee->role === 'hr' && $tournee->status=='hr_approve')
                <x-primary-button data-modal-toggle="editDatesModal-{{ $tournee->id }}">{{__('Change Dates')}}</x-primary-button>
                @include('partials.modals._tournee-change-dates')
                @endif
            </div>
        </div>
        <div class="flex flex-wrap -mx-1 mb-2">
            <div class="w-full px-3">
                <x-label>
                    Pays de Tournee<span class="text-red-500">*</span>
                </x-label>
                <label class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">
                        {{ $tournee->bareme->pays }}
                        (Montant:{{ $tournee->bareme->pays_per_day . ' ' . $tournee->bareme->currency }} /
                        Repas:{{ $tournee->bareme->meal_cost }} /
                        Hebergement:{{ $tournee->bareme->accomodation_cost }})
                </label>
            </div>
        </div>
        <div class="flex flex-wrap -mx-1 mb-2">
            <div class="w-1/2">
                <div class="w-full px-3 py-1">
                    <x-label class="w-4/5 inline-flex">
                        Prise en charge des frais de transport<span class="text-red-500">*</span>
                    </x-label>
                    <label
                        class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $tournee->charge == 1 ? 'OUI' : 'NON' }}</label>
                </div>
                <div class="w-full px-3 py-1">
                    <x-label class="w-4/5 inline-flex">
                        Prise en charge des indemnités journalières de tournee<span class="text-red-500">*</span>
                    </x-label>
                    <label
                        class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $tournee->ijm == 1 ? 'OUI' : 'NON' }}</label>
                </div>
                <div class="w-full px-3 py-1">
                    <label
                        class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $tournee->budget_text }}</label>
                </div>
            </div>
            <div class="w-1/2">
                <x-label>
                    Observations
                </x-label>
                <label
                    class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $tournee->description }}</label>
            </div>
        </div>
    </div>
    <div class="w-11/12 flex flex-wrap -mx-1 mb-2 border border-gray-200">
        <div class="w-full flex justify-center items-center p-2 space-x-2 rounded-b">
            @switch($tournee->status)
                @case('draft')
                    @if (auth()->user()->employee->id === $tournee->employee_id)
                        <a href="{{ route('tournees.edit', $tournee->id) }}"
                            class="text-white bg-blue-700
                            hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm
                            w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900">{{ __('Edit') }}</a>
                        <button
                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900"
                            type="button" data-modal-toggle="deleteModal-{{ $tournee->id }}">{{ __('Delete') }}</button>
                    @endif
                @break

                @case('sup_approve')
                @if (
                    (auth()->user()->employee->role === 'supervisor') &&
                        in_array(
                            $tournee->employee->department_id,
                            Department::where('manager_id', Auth::user()->employee->id)->pluck('id')->toArray()))
                        <button
                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900"
                            type="button" data-modal-toggle="approveModal-{{ $tournee->id }}">
                            {{ __('AVIS DU SUPÉRIEUR HIÉRARCHIQUE') }}
                        </button>
                        <a href="{{ route('tournees.report', $tournee->id) }}"
                            class="text-white bg-blue-700
                        hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm
                        w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900">{{ __('Print Order') }}</a>
                    @endif
                    @if (auth()->user()->employee->role === 'hr' || auth()->user()->employee->role === 'sg')
                        <a href="{{ route('tournees.report', $tournee->id) }}"
                            class="text-white bg-blue-700
                    hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm
                    w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900">{{ __('Print Order') }}</a>
                    @endif
                @break

                @case('hr_approve')
                    @if (auth()->user()->employee->role === 'hr')
                        <button
                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900"
                            type="button" data-modal-toggle="approveModal-{{ $tournee->id }}">
                            {{ __('Approve or Reject') }}
                        </button>
                    @endif
                    @if (auth()->user()->employee->role === 'hr' ||
                            auth()->user()->employee->role === 'sg' ||
                            (auth()->user()->employee->role === 'supervisor' &&
                                auth()->user()->employee->department_id === $tournee->employee->department_id))
                        <a href="{{ route('tournees.report', $tournee->id) }}"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900">{{ __('Print Order') }}</a>
                    @endif
                @break

                @case('sg_approve')
                    @if (auth()->user()->employee->role === 'sg')
                        <button
                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900"
                            type="button" data-modal-toggle="approveModal-{{ $tournee->id }}">
                            {{ __('Approve or Reject') }}
                        </button>
                    @endif
                    @if (auth()->user()->employee->role === 'hr' ||
                            auth()->user()->employee->role === 'sg' ||
                            (auth()->user()->employee->role === 'supervisor' &&
                                auth()->user()->employee->department_id === $tournee->employee->department_id))
                        <a href="{{ route('tournees.report', $tournee->id) }}"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900">{{ __('Print Order') }}</a>
                    @endif
                @break

                @case('approved')
                    @if ($tournee->employee->id == auth()->user()->employee->id)
                        <a href="{{ route('tournees.m_index') }}"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900">{{ __('Mémoire de Frais/Tournee') }}</a>
                        <a href="{{ route('tournees.report', $tournee->id) }}"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900">{{ __('Print Order') }}</a>
                    @elseif (auth()->user()->employee->role == 'supervisor' &&
                            auth()->user()->employee->department_id == $tournee->employee->department_id)
                        <a href="{{ route('tournees.report', $tournee->id) }}"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900">{{ __('Print Order') }}</a>
                    @elseif(auth()->user()->employee->role == 'hr' || auth()->user()->employee->role == 'sg')
                        <a href="{{ route('tournees.report', $tournee->id) }}"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900">{{ __('Print Order') }}</a>
                    @endif
                @break

                @case('paid')
                @break
            @endswitch
        </div>
    </div>
    <div class="w-11/12 flex flex-wrap -mx-1 mb-2 border border-gray-200">
        <h4 class="text-1xl text-blue-600 w-full text-center">{{__('Tournee Approves:')}}</h4>

        <table class="w-full text-sm text-left text-gray-500">
            @unless ($tournee->getTourneeAprroves()->isEmpty())
                <thead class="text-s text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="cursor-pointer py-3 px-6 blue-color">
                            {{ __('Approval Role') }}
                        </th>
                        <th class="cursor-pointer py-3 px-6 blue-color">
                            {{ __('Aprroval Name') }}
                        </th>
                        <th class="cursor-pointer py-3 px-6 blue-color">
                            {{ __('Comment') }}
                        </th>
                        <th class="cursor-pointer py-3 px-6 blue-color">
                            {{ __('Sataus') }}
                        </th>
                        <th class="cursor-pointer py-3 px-6 blue-color">
                            {{ __('Date of Approve') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tournee->getTourneeAprroves() as $approve)
                        <tr class="bg-white hover:bg-gray-50">
                            <td class="py-4 px-6 border-b cursor-pointer">
                                <div class="cursor-pointer">
                                    {{config('globals.roles.'. $approve->employee->role) }}
                                </div>
                            </td>
                            <td class="py-4 px-6 border-b cursor-pointer">
                                <div class="cursor-pointer">
                                    {{ $approve->employee->first_name }} {{ $approve->employee->last_name }}
                                </div>
                            </td>
                            <td class="py-4 px-6 border-b cursor-pointer">
                                <div class="cursor-pointer">
                                    {{ $approve->comment }}
                                </div>
                            </td>
                            <td class="py-4 px-6 border-b cursor-pointer">
                                <div class="cursor-pointer">
                                    {{ __($approve->status) }}
                                </div>
                            </td>
                            <td class="py-4 px-6 border-b cursor-pointer">
                                <div class="cursor-pointer">
                                    {{ $approve->created_at->format('d/m/Y H:i:s') }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="border-gray-300">
                        <td colspan="4" class="px-4 py-8 border-t border-gray-300 text-lg">
                            <p class="text-center">{{ __('No Approves Found') }}</p>
                        </td>
                    </tr>
                @endunless
            </tbody>
        </table>
    </div>

    @include('partials.modals._delete-tournee')
    @include('partials.modals._approve-tournee')
@endsection

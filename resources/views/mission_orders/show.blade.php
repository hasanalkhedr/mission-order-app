@php
    use App\Models\Department;
@endphp
@extends('layouts.app')
@section('title', $missionOrder->order_number . '-' . $missionOrder->employee->first_name . ' ' .$missionOrder->employee->last_name)
@section('content')
    <h2 class="text-2xl font-bold mb-2 text-blue-700">Demander une mission</h2>
    <div class="w-11/12">
        <div class="flex flex-wrap -mx-1 mb-2">
            <div class="w-1/5 px-3">
                <x-label>
                    Mission #
                </x-label>
                <label
                    class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $missionOrder->order_number }}</label>
            </div>
            <div class="w-1/5 px-3">
                <x-label>
                    Ordre de Mission Date
                </x-label>
                <label
                    class="ms-1 text-sm font-bold {{ $missionOrder->order_date > $missionOrder->start_date ? 'text-red-600 bg-yellow-400' : 'text-blue-600' }} dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $missionOrder->order_date->format('d/m/Y') }}</label>
            </div>
            <div class="w-1/5 px-3">
                <x-label>
                    Etat de la demande
                </x-label>
                <label
                    class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ __($missionOrder->status) }}</label>
            </div>
            <div class="w-1/5 px-3">
                <x-label>
                    Nom, Prénom
                </x-label>
                <label
                    class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $missionOrder->employee->first_name }}
                    {{ $missionOrder->employee->last_name }}</label>
            </div>
            <div class="w-1/5 px-3">
                <x-label>
                    Dép / Antenne
                </x-label>
                <label
                    class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $missionOrder->employee->department->name }}</label>
            </div>
        </div>
        <div class="flex flex-wrap -mx-1 mb-2">
            <div class="w-full px-3">
                <x-label>
                    Objet<span class="text-red-500">*</span>
                </x-label>
                <label
                    class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $missionOrder->purpose }}</label>
            </div>
        </div>
        <div class="flex flex-wrap -mx-1 mb-2">
            <div class="w-1/3 px-3">
                <x-label>
                    Lieu de départ<span class="text-red-500">*</span>
                </x-label>
                <label class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $missionOrder->departure_location }}</label>
            </div>
            <div class="w-1/3 px-3">
                <x-label>
                    Lieu de la mission<span class="text-red-500">*</span>
                </x-label>
                <label class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $missionOrder->arrive_location }}</label>
            </div>
            <div class="w-1/3 px-3">
                <x-label>
                    Lieu de retour<span class="text-red-500">*</span>
                </x-label>
                <label class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $missionOrder->return_location }}</label>
            </div>
        </div>

        <div class="flex flex-wrap -mx-1 mb-2">
            <div class="w-3/4 px-3">
                <x-label class="inline">
                    Date et Heure d'arrivée lieu de mission:<span class="text-red-500">*</span>
                </x-label>
                <label
                    class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $missionOrder->start_date->format('d/m/Y') }}
                    at {{ \Carbon\Carbon::parse($missionOrder->start_time2)->format('H:i') }}</label>
                <x-label class="inline">
                    Date et Heure de départ lieu de mission:<span class="text-red-500">*</span>
                </x-label>
                <label
                    class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $missionOrder->end_date->format('d/m/Y') }}
                    at {{ \Carbon\Carbon::parse($missionOrder->end_time2)->format('H:i') }}</label>

            </div>
            <div class="w-1/4">
                @if (auth()->user()->employee->hasRole('sg') && $missionOrder->status === 'sg_approve')
                    <x-primary-button
                        data-modal-toggle="editDatesModal-{{ $missionOrder->id }}">{{ __('Change Dates') }}</x-primary-button>
                    @include('partials.modals._change-dates')
                @endif
            </div>
        </div>
        <div class="flex flex-wrap -mx-1 mb-2">
            <div class="w-full px-3">
                <x-label>
                    Pays de Mission<span class="text-red-500">*</span>
                </x-label>
                <label class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">
                    @if (in_array(
                            $missionOrder->bareme->id,
                            array_column(App\Models\Bareme::where('pays', 'like', '%France%')->get('id')->toArray(), 'id')))
                        {{ $missionOrder->bareme->pays }}
                        ({{ $missionOrder->bareme->currency }})
                    @else
                        {{ $missionOrder->bareme->pays }}
                        (Montant:{{ $missionOrder->bareme->pays_per_day . ' ' . $missionOrder->bareme->currency }} /
                        Repas:{{ $missionOrder->bareme->meal_cost }} /
                        Hebergement:{{ $missionOrder->bareme->accomodation_cost }})
                    @endif
                </label>
            </div>
        </div>
        <div class="flex flex-wrap -mx-1 mb-2">
            <div class="w-1/2">
                <div class="w-full px-3 py-1">
                    <x-label class="w-4/5 inline-flex">
                        Prise en charge des frais de transport<span class="text-red-500">*</span> (Avion, Train, Taxi/Uber, Transport public)
                    </x-label>
                    <label
                        class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $missionOrder->charge == 1 ? 'OUI' : 'NON' }}</label>
                </div>
                <div class="w-full px-3 py-1">
                    <x-label class="w-4/5 inline-flex">
                        Prise en charge frais d'hébergement:<span class="text-red-500">*</span>
                    </x-label>
                    <label
                        class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $missionOrder->ijm == 1 ? 'OUI' : 'NON' }}</label>
                </div>
                <div class="w-full px-3 py-1">
                    <x-label class="w-4/5 inline-flex">
                        Prise en charge frais de repas:<span class="text-red-500">*</span>
                    </x-label>
                    <label
                        class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $missionOrder->repas == 1 ? 'OUI' : 'NON' }}</label>
                </div>
                {{-- <div class="w-full px-3 py-1">
                    <x-label class="w-4/5 inline-flex">
                        Prise en charge d'une assurance voyage<span class="text-red-500">*</span>
                    </x-label>
                    <label
                        class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $missionOrder->assurance == 1 ? 'OUI' : 'NON' }}</label>
                </div> --}}
                <div class="w-full px-3 py-1">
                    <label
                        class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $missionOrder->budget_text }}</label>
                </div>
            </div>
            <div class="w-1/2">
                <x-label>
                    Observations
                </x-label>
                <label
                    class="ms-1 text-sm font-medium text-blue-600 dark:text-gray-500 mr-5 bg-gray-100 px-2 py-2">{{ $missionOrder->description }}</label>
            </div>
        </div>
    </div>
    <div class="w-11/12 flex flex-wrap -mx-1 mb-2 border border-gray-200">
        <div class="w-full flex justify-center items-center p-2 space-x-2 rounded-b">
            @switch($missionOrder->status)
                @case('draft')
                    @if (auth()->user()->employee->id === $missionOrder->employee_id)
                        <a href="{{ route('mission_orders.edit', $missionOrder->id) }}"
                            class="text-white bg-blue-700
                            hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm
                            w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900">{{ __('Edit') }}</a>
                        <button
                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900"
                            type="button" data-modal-toggle="deleteModal-{{ $missionOrder->id }}">{{ __('Delete') }}</button>
                    @endif
                @break

                @case('sup_approve')
                    @if (auth()->user()->employee->hasRole('supervisor') &&
                            in_array(
                                $missionOrder->employee->department_id,
                                Department::where('manager_id', Auth::user()->employee->id)->pluck('id')->toArray()))
                        <button
                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900"
                            type="button" data-modal-toggle="approveModal-{{ $missionOrder->id }}">
                            {{ __('AVIS DU SUPÉRIEUR HIÉRARCHIQUE') }}
                        </button>
                    @endif
                @break

                @case('sg_approve')
                    @if (auth()->user()->employee->hasRole('sg'))
                        <button
                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900"
                            type="button" data-modal-toggle="approveModal-{{ $missionOrder->id }}">
                            {{ __('Approve or Reject') }}
                        </button>
                    @endif
                @break

                @case('approved')
                    @if ($missionOrder->employee->id == auth()->user()->employee->id)
                        <a href="{{ route('mission_orders.m_create', $missionOrder->id) }}"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900">{{ __('Mémoire de Frais') }}</a>
                    @endif
                @break

                @case('paid')
                @break

            @endswitch
            <a href="{{ route('mission_orders.report', $missionOrder->id) }}"
                            class="text-white bg-blue-700
                        hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm
                        w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900">{{ __('Print Order') }}</a>
        </div>
    </div>
    <div class="w-11/12 flex flex-wrap -mx-1 mb-2 border border-gray-200">
        <h4 class="text-1xl text-blue-600 w-full text-center">{{ __('Mission Approves:') }}</h4>

        <table class="w-full text-sm text-left text-gray-500">
            @unless ($missionOrder->getMissionAprroves()->isEmpty())
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
                            {{ __('Status') }}
                        </th>
                        <th class="cursor-pointer py-3 px-6 blue-color">
                            {{ __('Date of Approve') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($missionOrder->getMissionAprroves() as $approve)
                        <tr class="bg-white hover:bg-gray-50">
                            <td class="py-4 px-6 border-b cursor-pointer">
                                <div class="cursor-pointer">
                                    {{-- {{ config('globals.roles.' . $approve->employee->role) }} --}}
                                    {{ implode('|', $approve->employee->getRoles()) }}
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

    @include('partials.modals._delete-mission')
    @include('partials.modals._approve-mission')
@endsection

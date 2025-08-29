@extends('layouts.app')
@section('title', __('Add Memoire'))
@section('content')
    <h2 class="text-2xl font-bold mb-2 text-blue-700">MÉMOIRE DE FRAIS</h2>
    <form action="{{ route('mission_orders.m_update', $missionOrder->id) }}" method="POST" class="w-11/12 items-center">
        @csrf
        @method('PUT')
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-1/3 px-3">
                <x-label>
                    Mission #
                </x-label>
                <x-readonly-text-input value="{{ $missionOrder->order_number }}" />
            </div>
            <div class="w-1/3 px-3">
                <x-label>
                    Date le Ordre:<span class="text-red-500">*</span>
                </x-label>
                <x-date-time-input
                    class="appearance-none block h-12 w-full bg-white text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                    name="memor_date" value="{{ old('memor_date', $missionOrder->end_date->format('Y-m-d')) }}" type="date" required>
                </x-date-time-input>
            </div>
            <div class="w-1/3 px-3">
                <x-label>
                    Etat de la demande
                </x-label>
                <x-disabled-select-input>
                    <option value="draft">Brouillon</option>
                </x-disabled-select-input>
            </div>
        </div>
        <x-form-divider>Missionaire</x-form-divider>
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-1/2 px-3">
                <x-label>
                    Nom, Prénom
                </x-label>
                <x-readonly-text-input
                    value="{{ $missionOrder->employee->first_name }} {{ $missionOrder->employee->last_name }}" />
            </div>
            <div class="w-1/2 px-3">
                <x-label>
                    Fonction
                </x-label>
                <x-readonly-text-input value="{{ $missionOrder->employee->position }}" />
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-1/2 px-3">
                <x-label>
                    Résidence administrative
                </x-label>
                <x-readonly-text-input value="{{ $missionOrder->employee->administrativ_residence }}" />
            </div>
            <div class="w-1/2 px-3">
                <x-label>
                    Dép / Antenne
                </x-label>
                <x-readonly-text-input value="{{ $missionOrder->employee->department->name }}" />
            </div>
        </div>
        <x-form-divider>Mission</x-form-divider>
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full px-3">
                <x-label>
                    Objet/Motfits
                </x-label>
                <textarea rows="2" readonly
                    class="appearance-none block w-full bg-white text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none border border-blue-700 focus:bg-white focus:border-blue-900">{{ $missionOrder->purpose }}</textarea>
            </div>
        </div>
        <x-form-divider>Détail du déplacement résidence administrative - lieu de la mission</x-form-divider>
        <div class="flex flex-wrap -mx-3 mb-0 w-full">
            <div class="w-1/4 pr-1 pl-3">
                <x-label>Lieu de départ</x-label>
                <x-readonly-text-input value="{{ $missionOrder->departure_location }}" />
            </div>
            <div class="w-1/4 px-1">
                <x-label>Lieu de mission</x-label>
                <x-readonly-text-input value="{{ $missionOrder->arrive_location }}"/>
            </div>
            <div class="w-1/4 px-1">
                <x-label>Date de départ</x-label>
                <x-date-time-input class="w-full h-12" name="start_date" value="{{ $missionOrder->start_date->format('Y-m-d') }}" type="date" disabled></x-date-time-input>
            </div>
            <div class="w-1/8 px-1">
                <x-label>Heure de départ</x-label>
                <x-date-time-input class="w-full h-12" name="start_time" value="{{ $missionOrder->start_time }}" type="time" disabled></x-date-time-input>
            </div>
            <div class="w-1/8 pl-1 pr-3">
                <x-label>Heure d'arrivée</x-label>
                <x-date-time-input class="w-full h-12" name="start_time2" value="{{ $missionOrder->start_time2 }}" type="time" disabled></x-date-time-input>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-2 w-full">
            <div class="w-1/4 pr-1 pl-3">
                <x-label>Lieu de mission</x-label>
                <x-readonly-text-input value="{{ $missionOrder->endMission_location }}"/>
            </div>
            <div class="w-1/4 px-1">
                <x-label>Lieu de retour</x-label>
                <x-readonly-text-input value="{{ $missionOrder->return_location }}" />
            </div>
            <div class="w-1/4 px-1">
                <x-label>Date de départ</x-label>
                <x-date-time-input class="w-full h-12" name="end_date" value="{{ $missionOrder->end_date->format('Y-m-d') }}" type="date" disabled></x-date-time-input>
            </div>
            <div class="w-1/8 px-1">
                <x-label>Heure de départ</x-label>
                <x-date-time-input class="w-full h-12" name="end_time2" value="{{ $missionOrder->end_time2 }}" type="time" disabled></x-date-time-input>
            </div>
            <div class="w-1/8 pl-1 pr-3">
                <x-label>Heure d'arrivée</x-label>
                <x-date-time-input class="w-full h-12" name="end_time" value="{{ $missionOrder->end_time }}" type="time" disabled></x-date-time-input>
            </div>
        </div>
        {{-- <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-1/3 px-3">
                <x-label>
                    Lieu de départ
                </x-label>
                <x-readonly-text-input value="{{ $missionOrder->departure_location }}" />
            </div>
            <div class="w-1/3 px-3">
                <x-label>
                    Lieu de la Mission
                </x-label>
                <x-readonly-text-input value="{{ $missionOrder->arrive_location }}" />
            </div>
            <div class="w-1/3 px-3">
                <x-label>
                    Lieu de retour
                </x-label>
                <x-readonly-text-input value="{{ $missionOrder->return_location }}" />
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-2/3 px-3">
                <x-label>
                    Date et Heure d'arrivée lieu de mission:<span class="text-red-500">*</span>
                </x-label>
                <x-date-time-input disabled name="start_date" value="{{ $missionOrder->start_date->format('Y-m-d') }}"
                    type="date">
                </x-date-time-input>
                <x-date-time-input disabled name="start_time" value="{{ $missionOrder->start_time }}" type="time">
                </x-date-time-input>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-2/3 px-3">
                <x-label>
                    Date et Heure de départ lieu de mission:<span class="text-red-500">*</span>
                </x-label>
                <x-date-time-input readonly name="end_date" value="{{ $missionOrder->end_date->format('Y-m-d') }}"
                    type="date">
                </x-date-time-input>
                <x-date-time-input disabled name="end_time" value="{{ $missionOrder->end_time }}" type="time">
                </x-date-time-input>
            </div>
        </div> --}}
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full px-3">
                <x-label>
                    Pays de Mission<span class="text-red-500">*</span>
                </x-label>
                <x-disabled-select-input>
                    @if (in_array(
                            $missionOrder->bareme->id,
                            array_column(App\Models\Bareme::where('pays', 'like', '%France%')->get('id')->toArray(), 'id')))
                        <option value="{{ $missionOrder->bareme->id }}">
                            {{ $missionOrder->bareme->pays }}
                            ({{ $missionOrder->bareme->currency }})
                        </option>
                    @else
                        <option value="{{ $missionOrder->bareme->id }}">
                            {{ $missionOrder->bareme->pays }}
                            (Montant:{{ $missionOrder->bareme->pays_per_day . ' ' . $missionOrder->bareme->currency }} /
                            Repas:{{ $missionOrder->bareme->meal_cost }} /
                            Hebergement:{{ $missionOrder->bareme->accomodation_cost }})
                        </option>
                    @endif
                </x-disabled-select-input>
            </div>
        </div>
        {{-- <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-1/2 px-3">
                <x-label>
                    Nuitées à déduire des IJM<span class="text-red-500">*</span>
                </x-label>
                <x-text-input type="number" name="no_ded_accomodation" id="no_ded_accomodation"
                    value="{{old('no_ded_accomodation',  $missionOrder->no_ded_accomodation) }}" />
            </div>
            <div class="w-1/2 px-3">
                <x-label>
                    Repas à déduire<span class="text-red-500">*</span>
                </x-label>
                <x-text-input type="number" name="no_ded_meals" id="no_ded_meals"
                    value="{{old('no_ded_meals', $missionOrder->no_ded_meals) }}" />
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-1/2 px-3">
                <x-label>
                    Avance sur IJM (EURO ou USD)<span class="text-red-500">*</span>
                </x-label>
                <x-readonly-text-input value="{{ $missionOrder->advance }}" />
            </div>
            <div class="w-1/2 px-3">
                <x-label>{{__('Submit Values before add expenses')}}</x-label>
                <x-primary-button name="action" value="partialSubmit" class="h-11">Soumettre des valeurs</x-primary-button>
            </div>
        </div> --}}
        <x-form-divider>Frais Mission</x-form-divider>
        @include('partials.modals._expensestable')
        <x-form-divider><!-- Hebergement --></x-form-divider>
        @include('partials.modals._ijmtable')
        <div class="-mx-3 mb-2">
            <div class="w-full px-3 text-end">
                <x-primary-button data-modal-toggle="draftOrSubmitModal" type="button">Soumettre</x-primary-button>
                <div id="draftOrSubmitModal" tabindex="-1" aria-hidden="true"
                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
                    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow">
                            <!-- Modal header -->
                            <div class="flex justify-between items-center p-4 rounded-t border-b">
                                <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left">
                                    {{ __('Save Mission as Draft or Submit it') }}
                                </div>
                                <div>
                                    <button type="button"
                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                        data-modal-toggle="draftOrSubmitModal">
                                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                            </div>
                            <!-- Modal body -->
                            <div class="p-6">
                                <div class="text-base leading-relaxed text-gray-500 text-start">
                                    <ul>
                                        <li>{{ __('When save the mission as draft, you can edit or delete it later.')}}</li>
                                        <li>{{ __('When submit the mission, you can not edit or delete it, and the mission will go to the approve process.')}}</li>
                                    </ul>
                                </div>
                                <div
                                    class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                                    <div>
                                        <button data-modal-toggle="draftOrSubmitModal" name="action" value="draft"
                                            class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                            {{ __('Save as Draft') }}
                                        </button>
                                    </div>
                                    <div>
                                        <button name="action" value="submit"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                                            data-modal-toggle="draftOrSubmitModal">{{ __('Submit Memoire') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
    @include('partials.modals._create-expense')

@endsection

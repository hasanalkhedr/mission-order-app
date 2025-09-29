@extends('layouts.app')
@section('title', __('Add Memoire'))
@section('content')
    <h2 class="text-2xl font-bold mb-2 text-blue-700">MÉMOIRE DE FRAIS / TOURNEE</h2>
    @if($errors->has('error'))
    <div class="alert alert-danger">
        {{ $errors->first('error') }}
    </div>
@endif
    <form action="{{ route('tournees.m_update', $tournee->id) }}" method="POST" class="w-11/12 items-center" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-1/3 px-3">
                <x-label>
                    Tournee #
                </x-label>
                <x-readonly-text-input value="{{ $tournee->order_number }}" />
            </div>
            <div class="w-1/3 px-3">
                <x-label>
                    Date le Ordre:<span class="text-red-500">*</span>
                </x-label>
                <x-date-time-input
                    class="appearance-none block h-12 w-full bg-white text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                    name="memor_date" value="{{ old('memor_date', $tournee->lastDestination->end_date->format('Y-m-d')) }}" type="date"
                    required>
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
        <x-form-divider>Tourneeaire</x-form-divider>
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-1/2 px-3">
                <x-label>
                    Nom, Prénom
                </x-label>
                <x-readonly-text-input value="{{ $tournee->employee->first_name }} {{ $tournee->employee->last_name }}" />
            </div>
            <div class="w-1/2 px-3">
                <x-label>
                    Fonction
                </x-label>
                <x-readonly-text-input value="{{ $tournee->employee->position }}" />
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-1/2 px-3">
                <x-label>
                    Residence Administrative
                </x-label>
                <x-readonly-text-input value="{{ $tournee->employee->administrativ_residence }}" />
            </div>
            <div class="w-1/2 px-3">
                <x-label>
                    Dép / Antenne
                </x-label>
                <x-readonly-text-input value="{{ $tournee->employee->department->name }}" />
            </div>
        </div>
        <x-form-divider>Tournee</x-form-divider>
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full px-3">
                <x-label>
                    Objet/Motfits
                </x-label>
                <textarea rows="2" readonly
                    class="appearance-none block w-full bg-white text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none border border-blue-700 focus:bg-white focus:border-blue-900">{{ $tournee->purpose }}</textarea>
            </div>
        </div>

        {{-- CONGE PENDANT MISSION --}}
        @if($tournee->conge)
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full px-3 py-1">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="if_conge" id="if_conge" disabled
                        value="1" checked
                        class="w-4 h-4 text-blue-600 bg-gray-100 border border-blue-700 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <span class="ms-2 text-sm font-medium text-blue-500 dark:text-gray-300">Conge pendant mission</span>
                </label>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-2" id="conge_container">
            <div class="w-1/2 px-3">
                <textarea class="appearance-none block w-full bg-white text-gray-700 rounded py-3 px-4 mb-1 leading-tight focus:outline-none border border-blue-700 focus:bg-white focus:border-blue-900"
                        name="conge"
                        id="conge_input" disabled>{{$tournee->conge}}</textarea>
            </div>
        </div>
        @endif

        <div x-data="destinationManager({{ $tournee->tourneeDestinations->toJson() }})">
            <!-- Destinations container -->
            <div class="border rounded-md p-2 border-gray-300 mb-4">
                <template x-for="(destination, index) in destinations" :key="index">
                    <div class="destination-field border rounded-md p-2 border-gray-300 mb-4">
                        <div class="flex flex-wrap -mx-3 mb-[2px]">
                            <div class="w-1/2 px-3">
                                <x-label>
                                    Lieu de départ<span class="text-red-500">*</span>
                                </x-label>
                                <x-readonly-text-input x-model="destination.departure_location"/>
                            </div>
                            <div class="w-1/2 px-3">
                                <x-label>
                                    Lieu de mission<span class="text-red-500">*</span>
                                </x-label>
                                <x-readonly-text-input x-model="destination.arrive_location"/>
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-3 mb-[2px]">
                            <div class="w-1/2 px-3">
                                <x-label>
                                    Date et Heure d'arrivée lieu de mission:<span class="text-red-500">*</span>
                                </x-label>
                                <x-date-time-input x-model="destination.start_date" disapled type="date">
                                </x-date-time-input>
                                <x-date-time-input x-model="destination.start_time" disapled type="time">
                                </x-date-time-input>
                            </div>
                            <div class="w-1/2 px-3">
                                <x-label>
                                    Date et Heure de départ lieu de mission:<span class="text-red-500">*</span>
                                </x-label>
                                <x-date-time-input x-model="destination.end_date" disapled type="date">
                                </x-date-time-input>
                                <x-date-time-input x-model="destination.end_time" disapled type="time">
                                </x-date-time-input>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
        @if($tournee->has_weekend)
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 py-1 px-2 mb-1">
            <p>Attention: Votre mission comprend un weekend (samedi ou dimanche). Veuillez fournir une justification dans la
                description.</p>
        </div>
        @endif
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('destinationManager', (initialDestinations = null) => ({
                    destinations: initialDestinations ?
                        initialDestinations.map(dest => ({
                            ...dest,
                            start_date: dest.start_date ? dest.start_date.split('T')[0] : '',
                            end_date: dest.end_date ? dest.end_date.split('T')[0] : '',
                            //start_time: dest.start_time ? dest.start_time.substring(0, 5) : '',
                            //end_time: dest.end_time ? dest.end_time.substring(0, 5) : ''
                        })) :
                        [{
                            departure_location: '',
                            arrive_location: '',
                            start_date: '',
                            start_time: '',
                            end_date: '',
                            end_time: ''
                        }],

                    init() {
                        // Initialize with old input if available (form validation errors)
                        @if (old('destinations'))
                            this.destinations = @json(old('destinations'));
                        @endif
                    }
                }));
            });
        </script>

        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full px-3">
                <x-label>
                    Pays de Tournee<span class="text-red-500">*</span>
                </x-label>
                <x-disabled-select-input>
                    <option value="{{ $tournee->bareme->id }}">
                        {{ $tournee->bareme->pays }}
                        (Montant:{{ $tournee->bareme->pays_per_day . ' ' . $tournee->bareme->currency }} /
                        Repas:{{ $tournee->bareme->meal_cost }} /
                        Hebergement:{{ $tournee->bareme->accomodation_cost }})
                    </option>
                </x-disabled-select-input>
            </div>
        </div>
        <x-form-divider>Frais Tournee</x-form-divider>
        @include('partials.modals._tournee-expensestable')
        {{-- <x-form-divider><!-- Hebergement --></x-form-divider>
        @include('partials.modals._tournee-ijmtable') --}}
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
                                    {{ __('Save Tournee as Draft or Submit it') }}
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
                                        <li>{{ __('When save the tournee as draft, you can edit or delete it later.') }}
                                        </li>
                                        <li>{{ __('When submit the tournee, you can not edit or delete it, and the tournee will go to the approve process.') }}
                                        </li>
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
                                    <div>
                                        <button
                                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                                            type="button" data-modal-toggle="deleteModal-{{ $tournee->id }}">
                                            {{ __('Delete') }}
                                        </button>
                                    </div>
                                    @include('partials.modals._tournee-delete-memoier')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
    @include('partials.modals._tournee-create-expense')

@endsection

@extends('layouts.app')
@section('title', __('Edit Tournee'))

@section('content')
    <h2 class="text-2xl font-bold mb-6 text-blue-700">Edit Tournee</h2>
    <form action="{{ route('tournees.update', $tournee->id) }}" method="POST" class="w-full">
        @csrf
        @method('PUT')
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-1/3 px-3">
                <x-label>
                    Tournee #
                </x-label>
                <x-readonly-text-input name="order_number" value="{{ $tournee->order_number }}" />
            </div>
            <div class="w-1/3 px-3">
                <x-label>
                    Date le Ordre:<span class="text-red-500">*</span>
                </x-label>
                <x-date-time-input
                    class="appearance-none block h-12 w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                    name="order_date" value="{{ $tournee->order_date->format('Y-m-d') }}" type="date" readonly>
                </x-date-time-input>
            </div>
            <div class="w-1/3 px-3">
                <x-label>
                    Etat de la demande
                </x-label>
                <x-disabled-select-input name="status" required>
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
                <input type="hidden" name="employee_id" value="{{ $tournee->employee->id }}">
                <x-readonly-text-input name="full_name"
                    value="{{ $tournee->employee->first_name }} {{ $tournee->employee->last_name }}" />
            </div>
            <div class="w-1/2 px-3">
                <x-label>
                    Fonction
                </x-label>
                <x-readonly-text-input name="position" value="{{ $tournee->employee->position }}" />
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-1/2 px-3">
                <x-label>
                    Résidence administrative
                </x-label>
                <x-readonly-text-input name="administrativ_residence"
                    value="{{ $tournee->employee->administrativ_residence }}" />
            </div>
            <div class="w-1/2 px-3">
                <x-label>
                    Dép / Antenne
                </x-label>
                <input type="hidden" name="department_id" value="{{ $tournee->employee->department_id }}">
                <x-readonly-text-input name="department_name" value="{{ $tournee->employee->department->name }}" />
            </div>
        </div>
        <x-form-divider>Tournee</x-form-divider>
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full px-3">
                <x-label>
                    Objet<span class="text-red-500">*</span>
                </x-label>
                <textarea name="purpose" rows="2" required
                    class="appearance-none block w-full bg-white text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none border border-blue-700 focus:bg-white focus:border-blue-900">{{ old('purpose', $tournee->purpose) }}</textarea>
            </div>
        </div>
        <div x-data="destinationManager({{ $tournee->tourneeDestinations->toJson() }})">
            <!-- Destinations container -->
            <div class="border rounded-md p-2 border-gray-300 mb-4">
                <template x-for="(destination, index) in destinations" :key="index">
                    <div class="destination-field border rounded-md p-2 border-gray-300 mb-4">
                        <!-- Include hidden ID field for existing destinations -->
                        <template x-if="destination.id">
                            <input type="hidden" x-bind:name="`destinations[${index}][id]`" x-model="destination.id">
                        </template>

                        <div class="flex flex-wrap -mx-3 mb-[2px]">
                            <div class="w-1/2 px-3">
                                <x-label>
                                    Lieu de départ<span class="text-red-500">*</span>
                                </x-label>
                                <x-text-input x-model="destination.departure_location"
                                    x-bind:name="`destinations[${index}][departure_location]`" required />
                            </div>
                            <div class="w-1/2 px-3">
                                <x-label>
                                    Lieu de mission<span class="text-red-500">*</span>
                                </x-label>
                                <x-text-input x-model="destination.arrive_location"
                                    x-bind:name="`destinations[${index}][arrive_location]`" required />
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-3 mb-[2px]">
                            <div class="w-1/2 px-3">
                                <x-label>
                                    Date et Heure d'arrivée lieu de mission:<span class="text-red-500">*</span>
                                </x-label>
                                <x-date-time-input x-model="destination.start_date"
                                    x-bind:name="`destinations[${index}][start_date]`" required type="date">
                                </x-date-time-input>
                                <x-date-time-input x-model="destination.start_time"
                                    x-bind:name="`destinations[${index}][start_time]`" required type="time">
                                </x-date-time-input>
                            </div>
                            <div class="w-1/2 px-3">
                                <x-label>
                                    Date et Heure de départ lieu de mission:<span class="text-red-500">*</span>
                                </x-label>
                                <x-date-time-input x-model="destination.end_date"
                                    x-bind:name="`destinations[${index}][end_date]`" required type="date">
                                </x-date-time-input>
                                <x-date-time-input x-model="destination.end_time"
                                    x-bind:name="`destinations[${index}][end_time]`" required type="time">
                                </x-date-time-input>
                            </div>
                        </div>
                        <button x-show="destinations.length > 1" x-on:click="removeDestination(index)" type="button"
                            class="mt-2 bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                            Supprimer cette destination
                        </button>
                    </div>
                </template>
            </div>

            <!-- Add destination button -->
            <div class="mt-4">
                <button x-on:click="addDestination()" type="button"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Ajouter une autre destination
                </button>
            </div>
        </div>

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

                    addDestination() {
                        const lastDestination = this.destinations[this.destinations.length - 1];
                        this.destinations.push({
                            departure_location: lastDestination.arrive_location,
                            arrive_location: '',
                            start_date: lastDestination.end_date,
                            start_time: lastDestination.end_time,
                            end_date: '',
                            end_time: ''
                        });
                    },

                    removeDestination(index) {
                        if (this.destinations.length > 1) {
                            // // Mark for deletion if it's an existing destination
                            // if (this.destinations[index].id) {
                            //     this.destinations[index]._destroy = true;
                            // } else {
                                this.destinations.splice(index, 1);
                            //}
                        }
                    },

                    init() {
                        // Initialize with old input if available (form validation errors)
                        @if (old('destinations'))
                            this.destinations = @json(old('destinations'));
                        @endif
                    }
                }));
            });
        </script>
        <x-form-divider>Frais Tournee</x-form-divider>
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full px-3">
                <x-label>
                    Pays de Tournee<span class="text-red-500">*</span>
                </x-label>
                <x-select-input name="bareme_id" required>
                    @foreach ($baremes as $b)
                        <option selected value="{{ $b->id }}">
                            {{ $b->pays }} (Montant:{{ $b->pays_per_day . ' ' . $b->currency }} /
                            Repas:{{ $b->meal_cost }} /
                            Hebergement:{{ $b->accomodation_cost }})
                        </option>
                    @endforeach
                </x-select-input>
            </div>
        </div>
        <!-- Add the new advance payment section here -->
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full px-3 py-1">
                <x-label class="w-1/3 inline-flex">
                    Demande d'avance<span class="text-red-500">*</span>
                </x-label>
                <input required @checked(old('advance', $tournee->advance) > 0) type="radio" value="1" name="needs_advance"
                    id="needs_advance_yes"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border border-blue-700 focus:ring-blue-500 dark:focus:ring-blue-600 mr-0 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 advance-radio">
                <label for="needs_advance_yes"
                    class="ms-1 text-sm font-medium text-blue-500 dark:text-gray-500 mr-5">OUI</label>
                <input required @checked(old('advance', $tournee->advance) == 0) type="radio" value="0" name="needs_advance"
                    id="needs_advance_no"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border border-blue-700 focus:ring-blue-500 dark:focus:ring-blue-600 mr-0 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 advance-radio">
                <label for="needs_advance_no"
                    class="ms-1 text-sm font-medium text-blue-400 dark:text-gray-500 mr-10">NON</label>
            </div>
        </div>

        <!-- Add this after your existing advance amount field -->
        <div class="flex flex-wrap -mx-3 mb-2" id="advance_amount_container" style="display: none;">
            <div class="w-1/2 px-3">
                <x-label>
                    Montant de l'avance (INR Roupie indienne)<span class="text-red-500">*</span>
                </x-label>
                <x-text-input name="advance" value="{{ old('advance', $tournee->advance) }}"
                    id="advance_amount_input" />
                {{-- <small class="text-gray-500">Maximum autorisé: <span id="max_advance">0</span> (75% du total
                    hébergement)</small>
                <p id="advance_error" class="text-red-500 hidden">Le montant demandé dépasse 75% du total hébergement.</p> --}}
            </div>
        </div>

        <!-- Add this JavaScript to calculate and validate the advance amount -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const advanceRadios = document.querySelectorAll('.advance-radio');
                const advanceAmountContainer = document.getElementById('advance_amount_container');
                const advanceAmountInput = document.getElementById('advance_amount_input');
/*                const maxAdvanceSpan = document.getElementById('max_advance');
                const advanceError = document.getElementById('advance_error');
                const baremeSelect = document.querySelector('select[name="bareme_id"]');
                const startDateInput = document.querySelector('input[name="start_date"]');
                const endDateInput = document.querySelector('input[name="end_date"]');
                const startTimeInput = document.querySelector('input[name="start_time"]');
                const endTimeInput = document.querySelector('input[name="end_time"]');

                // Store bareme data for calculation
                const baremes = {!! json_encode(
                    $baremes->keyBy('id')->map(function ($item) {
                        return [
                            'accomodation_cost' => $item->accomodation_cost,
                            'currency' => $item->currency,
                        ];
                    }),
                ) !!};

                // Function to calculate days difference with 5 AM rule
                function calculateDays() {
                    if (!startDateInput.value || !endDateInput.value) return 0;

                    const startDate = new Date(`${startDateInput.value}T${startTimeInput.value || '00:00'}`);
                    const endDate = new Date(`${endDateInput.value}T${endTimeInput.value || '00:00'}`);

                    // Calculate full calendar days difference
                    const timezoneOffset = startDate.getTimezoneOffset() * 60000;
                    const normalizedStart = new Date(startDate - timezoneOffset);
                    const normalizedEnd = new Date(endDate - timezoneOffset);

                    // Get date parts only (ignoring time)
                    const startDateOnly = new Date(normalizedStart.toISOString().split('T')[0]);
                    const endDateOnly = new Date(normalizedEnd.toISOString().split('T')[0]);

                    // Difference in full calendar days
                    const diffDays = Math.round((endDateOnly - startDateOnly) / (1000 * 60 * 60 * 24));


                    let totalDays = diffDays;

                    // Add extra day if start time is before 5 AM
                    if (startDate.getHours() < 5) {
                        totalDays += 1;
                    }

                    return totalDays;
                }

                // Function to calculate max advance amount
                function calculateMaxAdvance() {
                    const selectedBareme = baremeSelect.value;
                    if (!selectedBareme) return 0;

                    const days = calculateDays();
                    const dailyCost = baremes[selectedBareme]?.accomodation_cost || 0;
                    const totalCost = days * dailyCost;
                    const maxAdvance = totalCost * 0.75; // 75% of total
                    const maxAdvanceInLocal = maxAdvance * {{ $chancellery_rate }};
                    return maxAdvanceInLocal.toFixed(2);
                }

                // Function to update max advance display
                function updateMaxAdvance() {
                    const maxAdvance = calculateMaxAdvance();
                    maxAdvanceSpan.textContent = maxAdvance + ' Roupie indienne (INR)';
                }

                // Function to validate advance amount
                function validateAdvanceAmount() {
                    const maxAdvance = parseFloat(calculateMaxAdvance());
                    const requestedAdvance = parseFloat(advanceAmountInput.value) || 0;

                    if (requestedAdvance > maxAdvance) {
                        advanceError.classList.remove('hidden');
                        return false;
                    } else {
                        advanceError.classList.add('hidden');
                        return true;
                    }
                }
*/
                // Toggle advance amount visibility
                function toggleAdvanceAmount() {
                    const needsAdvance = document.querySelector('input[name="needs_advance"]:checked')?.value;
                    if (needsAdvance === '1') {
                        advanceAmountContainer.style.display = 'flex';
                        advanceAmountInput.required = true;
                        // updateMaxAdvance();
                    } else {
                        advanceAmountContainer.style.display = 'none';
                        advanceAmountInput.required = false;
                    }
                }

                // Set initial state
                toggleAdvanceAmount();

                // Add event listeners
                advanceRadios.forEach(radio => {
                    radio.addEventListener('change', toggleAdvanceAmount);
                });

                // baremeSelect.addEventListener('change', updateMaxAdvance);
                // startDateInput.addEventListener('change', updateMaxAdvance);
                // endDateInput.addEventListener('change', updateMaxAdvance);
                // startTimeInput.addEventListener('change', updateMaxAdvance);
                // endTimeInput.addEventListener('change', updateMaxAdvance);
                // advanceAmountInput.addEventListener('input', validateAdvanceAmount);

                // // Also validate before form submission
                // document.querySelector('form').addEventListener('submit', function(e) {
                //     const needsAdvance = document.querySelector('input[name="needs_advance"]:checked')?.value;

                //     if (needsAdvance === '1' && !validateAdvanceAmount()) {
                //         e.preventDefault();
                //         alert(
                //             'Le montant demandé dépasse 75% du total hébergement. Veuillez ajuster votre demande.'
                //         );
                //     }
                // });
            });
        </script>

        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full px-3 py-1">
                <x-label class="w-1/3 inline-flex">
                    Prise en charge des frais de transport<span class="text-red-500">*</span>
                </x-label>
                <input required @checked(old('charge', $tournee->charge) == 1) type="radio" value="1" name="charge"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border border-blue-700 focus:ring-blue-500 dark:focus:ring-blue-600 mr-0 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label class="ms-1 text-sm font-medium text-blue-500 dark:text-gray-500 mr-5">OUI</label>
                <input required @checked(old('charge', $tournee->charge) == 0) type="radio" value="0" name="charge"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border border-blue-700 focus:ring-blue-500 dark:focus:ring-blue-600 mr-0 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label class="ms-1 text-sm font-medium text-blue-400 dark:text-gray-500 mr-10">NON</label>
            </div>
            <div class="w-full px-3 py-1">
                <x-label class="w-1/3 inline-flex">
                    Prise en charge des indemnités journalières de mission<span class="text-red-500">*</span>
                </x-label>
                <input required @checked(old('ijm', $tournee->ijm) == 1) type="radio" value="1" name="ijm"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border border-blue-700 focus:ring-blue-500 dark:focus:ring-blue-600 mr-0 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label class="ms-1 text-sm font-medium mr-5 text-blue-400 dark:text-gray-500">OUI</label>
                <input required @checked(old('ijm', $tournee->ijm) == 0) type="radio" value="0" name="ijm"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border border-blue-700 focus:ring-blue-500 dark:focus:ring-blue-600 mr-0 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label class="ms-1 text-sm font-medium text-blue-400 dark:text-gray-500 mr-5">NON</label>

            </div>
        </div>
        {{-- Reception Fees --}}
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full px-3 py-1">
                <x-label class="w-1/3 inline-flex">
                    Frais de réception<span class="text-red-500">*</span>
                </x-label>
                <input required @checked(Str::length(old('reception_fees', $tournee->reception_fees)) > 0) type="radio" value="1" name="needs_reception_fees" id="needs_reception_fees_yes"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border border-blue-700 focus:ring-blue-500 dark:focus:ring-blue-600 mr-0 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 reception_fees-radio">
                <label for="needs_reception_fees_yes"
                    class="ms-1 text-sm font-medium text-blue-500 dark:text-gray-500 mr-5">OUI</label>
                <input required @checked(Str::length(old('reception_fees', $tournee->reception_fees)) == 0) type="radio" value="0" name="needs_reception_fees"
                    id="needs_reception_fees_no"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border border-blue-700 focus:ring-blue-500 dark:focus:ring-blue-600 mr-0 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 reception_fees-radio">
                <label for="needs_reception_fees_no"
                    class="ms-1 text-sm font-medium text-blue-400 dark:text-gray-500 mr-10">NON</label>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-2" id="reception_fees_container" style="display: none;">
            <div class="w-1/2">
                <x-text-input name="reception_fees" value="{{ old('reception_fees', $tournee->reception_fees) }}" id="reception_fees_input" />
                <small class="text-gray-500">Si coché: (nombre de personnes et motifs)</small>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const reception_feesRadios = document.querySelectorAll('.reception_fees-radio');
                const reception_feesContainer = document.getElementById('reception_fees_container');
                const reception_feesInput = document.getElementById('reception_fees_input');

                function togglereception_fees() {
                    const needsreception_fees = document.querySelector('input[name="needs_reception_fees"]:checked')
                        ?.value;
                    if (needsreception_fees === '1') {
                        reception_feesContainer.style.display = 'flex';
                        reception_feesInput.required = true;
                    } else {
                        reception_feesContainer.style.display = 'none';
                        reception_feesInput.required = false;
                    }
                }

                // Set initial state
                togglereception_fees();

                reception_feesRadios.forEach(radio => {
                    radio.addEventListener('change', togglereception_fees);
                });
            });
        </script>
        <x-form-divider>Observations</x-form-divider>
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full px-3">
                <x-label>
                    Observation
                </x-label>
                <textarea name="description" rows="4"
                    class="appearance-none block w-full bg-white text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none border border-blue-700 focus:bg-white focus:border-blue-900">{{ old('description', $tournee->description) }}</textarea>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full px-3">
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
                                <div class="text-base leading-relaxed text-gray-500">
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
                                            class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 focus:z-10">
                                            {{ __('Save as Draft') }}
                                        </button>
                                    </div>
                                    <div>
                                        <button name="action" value="submit"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                                            data-modal-toggle="draftOrSubmitModal">{{ __('Submit Tournee') }}
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
@endsection

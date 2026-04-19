@extends('layouts.app')
@section('title', __('Create Mission'))
@section('content')
    <h2 class="text-2xl font-bold mb-2 text-blue-700">Demander une mission</h2>
    <form id="mainForm" action="{{ route('mission_orders.store') }}" method="POST" class="w-11/12 items-center">
        @csrf
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-1/3 px-3">
                <x-label>
                    Mission #
                </x-label>
                <x-readonly-text-input name="order_number" value="{{ $mission_number }}" />
            </div>
            <div class="w-1/3 px-3">
                <x-label>
                    Date le Ordre:<span class="text-red-500">*</span>
                </x-label>
                <x-date-time-input
                    class="appearance-none block h-12 w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none"
                    name="order_date" value="{{ (new \DateTime())->format('Y-m-d') }}" type="date" readonly>
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
        <x-form-divider>Missionaire</x-form-divider>
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-1/2 px-3">
                <x-label>
                    Nom, Prénom
                </x-label>
                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                <x-readonly-text-input name="full_name"
                    value="{{ auth()->user()->employee->first_name }} {{ auth()->user()->employee->last_name }}" />
            </div>
            <div class="w-1/2 px-3">
                <x-label>
                    Fonction
                </x-label>
                <x-readonly-text-input name="position" value="{{ auth()->user()->employee->position }}" />
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-1/2 px-3">
                <x-label>
                    Ville de résidence administrative
                </x-label>
                <x-readonly-text-input name="administrativ_residence"
                    value="{{ auth()->user()->employee->administrativ_residence }}" />
            </div>
            <div class="w-1/2 px-3">
                <x-label>
                    Secteur
                </x-label>
                <input type="hidden" name="department_id" value="{{ auth()->user()->employee->department_id }}">
                <x-readonly-text-input name="department_name" value="{{ auth()->user()->employee->department->name }}" />
            </div>
        </div>
        <x-form-divider>Mission</x-form-divider>
        {{-- <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full px-3">
                <x-label>
                    Objet/Motif<span class="text-red-500">*</span>
                </x-label>
                <textarea name="purpose" rows="2" required minlength="100"
                    class="appearance-none block w-full bg-white text-gray-700 rounded py-3 px-4 mb-1 leading-tight focus:outline-none border border-blue-700 focus:bg-white focus:border-blue-900"
                    oninput="updateCharCounter(this)">{{ old('purpose') }}</textarea>
                <div class="flex justify-between items-center">
                    <small class="text-gray-500">Minimum 100 caractères requis</small>
                    <small id="char-counter" class="text-gray-500">0/100</small>
                </div>
                <div id="purpose-error" class="text-red-500 hidden mt-1">Le texte doit contenir au moins 100 caractères.
                </div>
            </div>
            <script>
                // Initialize counter on page load
                document.addEventListener('DOMContentLoaded', function() {
                    const textarea = document.querySelector('textarea[name="purpose"]');
                    updateCharCounter(textarea);
                });
                function updateCharCounter(textarea) {
                    const charCount = textarea.value.length;
                    const counterElement = document.getElementById('char-counter');
                    counterElement.textContent = `${charCount}/100`;
                    if (charCount < 100) {
                        counterElement.classList.add('text-red-500');
                        counterElement.classList.remove('text-gray-500', 'text-green-500');
                    } else {
                        counterElement.classList.add('text-green-500');
                        counterElement.classList.remove('text-gray-500', 'text-red-500');
                    }
                }
                // Validate on form submission
                document.querySelector('#mainForm')?.addEventListener('submit', function(e) {
                    const textarea = document.querySelector('textarea[name="purpose"]');
                    const errorElement = document.getElementById('purpose-error');

                    if (textarea.value.length < 100) {
                        e.preventDefault();
                        errorElement.textContent = "Le texte doit contenir au moins 100 caractères."; // French message
                        errorElement.classList.remove('hidden');
                        textarea.focus();
                    } else {
                        errorElement.classList.add('hidden');
                    }
                });
            </script>
        </div> --}}
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full px-3">
                <x-label>
                    Objet/Motif<span class="text-red-500">*</span>
                </x-label>
                <textarea name="purpose" rows="2" required minlength="100" maxlength="500"
                    class="appearance-none block w-full bg-white text-gray-700 rounded py-3 px-4 mb-1 leading-tight focus:outline-none border border-blue-700 focus:bg-white focus:border-blue-900"
                    oninput="updateCharCounter(this)">{{ old('purpose') }}</textarea>
                <div class="flex justify-between items-center">
                    <small class="text-gray-500">Minimum 100 caractères, Maximum 500 caractères</small>
                    <small id="char-counter" class="text-gray-500">0/500</small>
                </div>
                <div id="purpose-error" class="text-red-500 hidden mt-1"></div>
            </div>
            <script>
                // Initialize counter on page load
                document.addEventListener('DOMContentLoaded', function() {
                    const textarea = document.querySelector('textarea[name="purpose"]');
                    updateCharCounter(textarea);
                });

                function updateCharCounter(textarea) {
                    let charCount = textarea.value.length;
                    const maxLength = 500;
                    const minLength = 100;

                    // Reject input beyond max length
                    if (charCount > maxLength) {
                        // Trim the text to max length
                        textarea.value = textarea.value.substring(0, maxLength);
                        charCount = maxLength;
                    }

                    const counterElement = document.getElementById('char-counter');
                    counterElement.textContent = `${charCount}/${maxLength}`;

                    // Update color based on character count
                    if (charCount < minLength) {
                        counterElement.classList.add('text-red-500');
                        counterElement.classList.remove('text-gray-500', 'text-green-500', 'text-orange-500');
                    } else if (charCount >= minLength && charCount <= maxLength - 50) {
                        counterElement.classList.add('text-green-500');
                        counterElement.classList.remove('text-gray-500', 'text-red-500', 'text-orange-500');
                    } else if (charCount > maxLength - 50) {
                        counterElement.classList.add('text-orange-500');
                        counterElement.classList.remove('text-gray-500', 'text-red-500', 'text-green-500');
                    }
                }

                // Prevent paste that exceeds max length
                document.querySelector('textarea[name="purpose"]')?.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                    const currentText = this.value;
                    const maxLength = 500;
                    const remainingChars = maxLength - currentText.length;

                    if (remainingChars > 0) {
                        const textToPaste = pastedText.substring(0, remainingChars);
                        this.value = currentText + textToPaste;
                        updateCharCounter(this);
                    }
                });

                // Validate on form submission
                document.querySelector('#mainForm')?.addEventListener('submit', function(e) {
                    const textarea = document.querySelector('textarea[name="purpose"]');
                    const errorElement = document.getElementById('purpose-error');
                    const charCount = textarea.value.length;
                    const minLength = 100;
                    const maxLength = 500;

                    if (charCount < minLength) {
                        e.preventDefault();
                        errorElement.textContent = "Le texte doit contenir au moins 100 caractères.";
                        errorElement.classList.remove('hidden');
                        textarea.focus();
                    } else if (charCount > maxLength) {
                        e.preventDefault();
                        errorElement.textContent = "Le texte ne peut pas dépasser 500 caractères.";
                        errorElement.classList.remove('hidden');
                        textarea.focus();
                    } else {
                        errorElement.classList.add('hidden');
                    }
                });
            </script>
        </div>
        {{-- CONGE PENDANT MISSION --}}
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full px-3 py-1">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="if_conge" id="if_conge"
                        value="1" @checked(old('if_conge', 0) > 0)
                        class="w-4 h-4 text-blue-600 bg-gray-100 border border-blue-700 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <span class="ms-2 text-sm font-medium text-blue-500 dark:text-gray-300">Conge pendant mission</span>
                </label>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-2" id="conge_container" style="display: none;">
            <div class="w-1/2 px-3">
                <textarea class="appearance-none block w-full bg-white text-gray-700 rounded py-3 px-4 mb-1 leading-tight focus:outline-none border border-blue-700 focus:bg-white focus:border-blue-900"
                        name="conge"
                        id="conge_input" disabled>{{ old('conge') }}</textarea>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const congeCheckbox = document.getElementById('if_conge');
                const congeContainer = document.getElementById('conge_container');
                const congeInput = document.getElementById('conge_input');
                function toggleconge() {
                    if (congeCheckbox.checked) {
                        congeContainer.style.display = 'flex';
                        congeInput.disabled = false;
                        congeInput.required = true;
                    } else {
                        congeContainer.style.display = 'none';
                        congeInput.disabled = true;
                        congeInput.required = false;
                        congeInput.value = '';
                    }
                }
                toggleconge();
                congeCheckbox.addEventListener('change', toggleconge);
            });
        </script>

        <x-form-divider>Détail du déplacement résidence administrative - lieu de la mission</x-form-divider>
        <div class="flex flex-wrap -mx-3 mb-0 w-full">
            <div class="w-1/4 pr-1 pl-3">
                <x-label>Lieu de départ<span class="text-red-500">*</span></x-label>
                <x-text-input required name="departure_location" id="departure_location" value="{{ old('departure_location') }}" onblur="returnLocationValue();" />
            </div>
            <div class="w-1/4 px-1">
                <x-label>Lieu de mission<span class="text-red-500">*</span></x-label>
                <x-text-input required name="arrive_location" id="arrive_location" value="{{ old('arrive_location') }}"  onblur="endMissionLocationValue();" />
            </div>
            <div class="w-1/4 px-1">
                <x-label>Date de départ<span class="text-red-500">*</span></x-label>
                <x-date-time-input class="w-full h-12" name="start_date" id="start_date" value="{{ old('start_date') }}" type="date" required></x-date-time-input>
            </div>
            <div class="w-1/8 px-1">
                <x-label>Heure de départ<span class="text-red-500">*</span></x-label>
                <x-date-time-input class="w-full h-12" name="start_time" value="{{ old('start_time') }}" type="time" required></x-date-time-input>
            </div>
            <div class="w-1/8 pl-1 pr-3">
                <x-label>Heure d'arrivée<span class="text-red-500">*</span></x-label>
                <x-date-time-input class="w-full h-12" name="start_time2" value="{{ old('start_time2') }}" type="time" required></x-date-time-input>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-2 w-full">
            <div class="w-1/4 pr-1 pl-3">
                <x-label>Lieu de mission<span class="text-red-500">*</span></x-label>
                <x-text-input required name="endMission_location" id="endMission_location" value="{{ old('endMission_location') }}"/>
            </div>
            <div class="w-1/4 px-1">
                <x-label>Lieu de retour<span class="text-red-500">*</span></x-label>
                <x-text-input required name="return_location" id="return_location" value="{{ old('return_location') }}" />
                <script>
                    function returnLocationValue() {
                        document.getElementById('return_location').value = document.getElementById('departure_location').value;
                    }
                    function endMissionLocationValue() {
                        document.getElementById('endMission_location').value = document.getElementById('arrive_location').value;
                    }
                </script>
            </div>
            <div class="w-1/4 px-1">
                <x-label>Date de départ<span class="text-red-500">*</span></x-label>
                <x-date-time-input class="w-full h-12" name="end_date" id="end_date" value="{{ old('end_date') }}" type="date" required></x-date-time-input>
            </div>
            <div class="w-1/8 px-1">
                <x-label>Heure de départ<span class="text-red-500">*</span></x-label>
                <x-date-time-input class="w-full h-12" name="end_time2" value="{{ old('end_time2') }}" type="time" required></x-date-time-input>
            </div>
            <div class="w-1/8 pl-1 pr-3">
                <x-label>Heure d'arrivée<span class="text-red-500">*</span></x-label>
                <x-date-time-input class="w-full h-12" name="end_time" value="{{ old('end_time') }}" type="time" required></x-date-time-input>
            </div>
        </div>
        <!-- Hidden input to send weekend flag to backend -->
        <input type="hidden" name="has_weekend" id="has_weekend" value="{{old('has_weekend', 0)}}">
        <div id="weekend-warning" class="hidden bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-3">
            <p>Attention: Votre mission comprend un weekend (samedi ou dimanche). Veuillez fournir une justification dans la zone Objet/Motif de la mission.</p>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');
                const weekendWarning = document.getElementById('weekend-warning');
                const hasWeekendInput = document.getElementById('has_weekend');

                function checkForWeekend() {
                    const startDate = new Date(startDateInput.value);
                    const endDate = new Date(endDateInput.value);

                    if (!startDateInput.value || !endDateInput.value) {
                        // Reset flag if dates are not set
                        hasWeekendInput.value = '0';
                        return;
                    }

                    // Check if any day in the range is Saturday (6) or Sunday (0)
                    let hasWeekend = false;
                    const currentDate = new Date(startDate);

                    while (currentDate <= endDate) {
                        const day = currentDate.getDay();
                        if (day === 0 || day === 6) {
                            hasWeekend = true;
                            break;
                        }
                        currentDate.setDate(currentDate.getDate() + 1);
                    }

                    if (hasWeekend) {
                        weekendWarning.classList.remove('hidden');
                        hasWeekendInput.value = '1'; // Set flag to true
                    } else {
                        weekendWarning.classList.add('hidden');
                        hasWeekendInput.value = '0'; // Set flag to false
                    }
                }

                // Check on page load if there are existing values
                if (startDateInput.value && endDateInput.value) {
                    checkForWeekend();
                }

                startDateInput.addEventListener('change', checkForWeekend);
                endDateInput.addEventListener('change', checkForWeekend);

                // Also check when dates are cleared
                startDateInput.addEventListener('input', checkForWeekend);
                endDateInput.addEventListener('input', checkForWeekend);
            });
        </script>


        <x-form-divider>Frais Mission</x-form-divider>
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full px-3">
                <x-label>
                    Pays de Mission<span class="text-red-500">*</span>
                </x-label>
                <div class="select-container">
                    <x-select-input name="bareme_id" required class="select2">
                        @foreach ($baremes as $bareme)
                            <option {{ old('bareme_id', 94) == $bareme->id ? 'selected' : '' }}
                                value="{{ $bareme->id }}">
                                {{ $bareme->pays }} (Montant:{{ $bareme->pays_per_day . ' ' . $bareme->currency }} /
                                Repas:{{ $bareme->meal_cost }} /
                                Hebergement:{{ $bareme->accomodation_cost }})
                            </option>
                        @endforeach
                    </x-select-input>
                    <script>
                        $(document).ready(function() {
                            $('.select2').select2({
                                placeholder: "Select an option", // Optional placeholder
                                allowClear: true // Optional clear button
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
        <!-- Add the new advance payment section -->
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full px-3 py-1">
                <x-label class="w-1/2 inline-flex">
                    Demande d'avance<span class="text-red-500">*</span>
                </x-label>
                <input required @checked(old('advance', 0) > 0) type="radio" value="1" name="needs_advance"
                    id="needs_advance_yes"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border border-blue-700 focus:ring-blue-500 dark:focus:ring-blue-600 mr-0 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 advance-radio">
                <label for="needs_advance_yes"
                    class="ms-1 text-sm font-medium text-blue-500 dark:text-gray-500 mr-5">OUI</label>
                <input required @checked(old('advance', 0) == 0) type="radio" value="0" name="needs_advance"
                    id="needs_advance_no"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border border-blue-700 focus:ring-blue-500 dark:focus:ring-blue-600 mr-0 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 advance-radio">
                <label for="needs_advance_no"
                    class="ms-1 text-sm font-medium text-blue-400 dark:text-gray-500 mr-10">NON</label>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-2" id="advance_amount_container" style="display: none;">
            <div class="w-1/2 px-3">
                <x-label>
                    Montant de l'avance (INR Roupie indienne)<span class="text-red-500">*</span>
                </x-label>
                <x-text-input name="advance" value="{{ old('advance') }}" id="advance_amount_input" />
                <small class="text-gray-500">Maximum autorisé: <span id="max_advance">0</span> (75% du total
                    hébergement)</small>
                <p id="advance_error" class="text-red-500 hidden">Le montant demandé dépasse 75% du total hébergement.</p>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const advanceRadios = document.querySelectorAll('.advance-radio');
                const advanceAmountContainer = document.getElementById('advance_amount_container');
                const advanceAmountInput = document.getElementById('advance_amount_input');
                const maxAdvanceSpan = document.getElementById('max_advance');
                const advanceError = document.getElementById('advance_error');
                const baremeSelect = document.querySelector('select[name="bareme_id"]');
                const startDateInput = document.querySelector('input[name="start_date"]');
                const endDateInput = document.querySelector('input[name="end_date"]');
                const startTimeInput = document.querySelector('input[name="start_time2"]');
                const endTimeInput = document.querySelector('input[name="end_time2"]');

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
                    const maxAdvanceInLocal = maxAdvance / ({{ $chancellery_rate ? $chancellery_rate->eur_rate : 1 }});
                    return maxAdvanceInLocal.toFixed(2);
                }

                // Function to update max advance display
                function updateMaxAdvance() {
                    const needsAdvance = document.querySelector('input[name="needs_advance"]:checked')?.value;
                    const maxAdvance = calculateMaxAdvance();
                    maxAdvanceSpan.textContent = maxAdvance + ' Roupie indienne (INR)';
                    advanceAmountInput.value = advanceAmountInput.value == 0 && maxAdvance && needsAdvance === '1' ? maxAdvance : advanceAmountInput.value;
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

                // Toggle advance amount visibility
                function toggleAdvanceAmount() {
                    const needsAdvance = document.querySelector('input[name="needs_advance"]:checked')?.value;
                    if (needsAdvance === '1') {
                        advanceAmountContainer.style.display = 'flex';
                        advanceAmountInput.required = true;
                        updateMaxAdvance();
                    } else {
                        advanceAmountContainer.style.display = 'none';
                        advanceAmountInput.required = false;
                        advanceAmountInput.value = 0;
                    }
                }

                // Set initial state
                toggleAdvanceAmount();

                // Add event listeners
                advanceRadios.forEach(radio => {
                    radio.addEventListener('change', toggleAdvanceAmount);
                });

                baremeSelect.addEventListener('change', updateMaxAdvance);
                startDateInput.addEventListener('change', updateMaxAdvance);
                endDateInput.addEventListener('change', updateMaxAdvance);
                startTimeInput.addEventListener('change', updateMaxAdvance);
                endTimeInput.addEventListener('change', updateMaxAdvance);
                advanceAmountInput.addEventListener('input', validateAdvanceAmount);

                // Also validate before form submission
                document.querySelector('form').addEventListener('submit', function(e) {
                    const needsAdvance = document.querySelector('input[name="needs_advance"]:checked')?.value;

                    if (needsAdvance === '1' && !validateAdvanceAmount()) {
                        e.preventDefault();
                        alert(
                            'Le montant demandé dépasse 75% du total hébergement. Veuillez ajuster votre demande.'
                        );
                    }
                });
            });
        </script>

        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full px-3 py-1">
                <x-label class="w-1/2 inline-flex">
                    Prise en charge des frais de transport<span class="text-red-500">*</span> (Avion, Train)
                </x-label>
                <input required @checked(old('charge', 1) == 1) type="radio" value="1" name="charge"
                    id="charge_yes"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border border-blue-700 focus:ring-blue-500 dark:focus:ring-blue-600 mr-0 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="charge_yes" class="ms-1 text-sm font-medium text-blue-500 dark:text-gray-500 mr-5">OUI</label>
                <input required @checked(old('charge', 1) == 0) type="radio" value="0" name="charge" id="charge_no"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border border-blue-700 focus:ring-blue-500 dark:focus:ring-blue-600 mr-0 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="charge_no" class="ms-1 text-sm font-medium text-blue-400 dark:text-gray-500 mr-10">NON</label>
            </div>
            <div class="w-full px-3 py-1">
                <x-label class="w-1/2 inline-flex">
                    Prise en charge des frais de transport<span class="text-red-500">*</span> (Taxi/Uber, Transport public, etc..)
                </x-label>
                <input required @checked(old('charge1', 1) == 1) type="radio" value="1" name="charge1"
                    id="charge1_yes"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border border-blue-700 focus:ring-blue-500 dark:focus:ring-blue-600 mr-0 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="charge1_yes" class="ms-1 text-sm font-medium text-blue-500 dark:text-gray-500 mr-5">OUI</label>
                <input required @checked(old('charge1', 1) == 0) type="radio" value="0" name="charge1" id="charge1_no"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border border-blue-700 focus:ring-blue-500 dark:focus:ring-blue-600 mr-0 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="charge1_no" class="ms-1 text-sm font-medium text-blue-400 dark:text-gray-500 mr-10">NON</label>
            </div>
            <div class="w-full px-3 py-1">
                <x-label class="w-1/2 inline-flex">
                    Prise en charge frais d'hébergement<span class="text-red-500">*</span>
                </x-label>
                <input required @checked(old('ijm', 1) == 1) type="radio" value="1" name="ijm"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border border-blue-700 focus:ring-blue-500 dark:focus:ring-blue-600 mr-0 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label class="ms-1 text-sm font-medium mr-5 text-blue-400 dark:text-gray-500">OUI</label>
                <input required @checked(old('ijm', 1) == 0) type="radio" value="0" name="ijm"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border border-blue-700 focus:ring-blue-500 dark:focus:ring-blue-600 mr-0 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label class="ms-1 text-sm font-medium text-blue-400 dark:text-gray-500 mr-5">NON</label>
            </div>
            <div class="w-full px-3 py-1">
                <x-label class="w-1/2 inline-flex">
                    Prise en charge frais de repas<span class="text-red-500">*</span>
                </x-label>
                <input required @checked(old('repas', 1) == 1) type="radio" value="1" name="repas"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border border-blue-700 focus:ring-blue-500 dark:focus:ring-blue-600 mr-0 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label class="ms-1 text-sm font-medium mr-5 text-blue-400 dark:text-gray-500">OUI</label>
                <input required @checked(old('repas', 1) == 0) type="radio" value="0" name="repas"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border border-blue-700 focus:ring-blue-500 dark:focus:ring-blue-600 mr-0 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label class="ms-1 text-sm font-medium text-blue-400 dark:text-gray-500 mr-5">NON</label>
            </div>
        </div>

        {{-- Pre Expenses --}}
        <x-form-divider>Dépenses prévues supplémentaires</x-form-divider>
        <div class="flex flex-col" x-data="expensesManager()">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Nature de la dépense</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(expense, index) in expenses" :key="index">
                                    <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                                        <td class="px-6 text-center border border-gray-200 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                            <x-select-input x-bind:name="`expenses[${index}][type]`" x-model="expense.type" x-on:change="expense.nature = ''">
                                                <option value="">--sélectionner le type--</option>
                                                <option value="transport">transport</option>
                                                <option value="extra_meal">repas</option>
                                                <option value="visa">visa</option>
                                                <option value="Receptions">Receptions</option>
                                                <option value="other">autre</option>
                                            </x-select-input>
                                        </td>
                                        <td class="px-6 text-center border border-gray-200 py-4 whitespace-nowrap text-sm text-gray-800">
                                            <template x-if="expense.type === 'transport'">
                                                <div>
                                                    <x-select-input x-bind:name="`expenses[${index}][transport_type]`" x-model="expense.transport_type" required x-on:change="handleTransportTypeChange(index)">
                                                        <option value="">--sélectionner--</option>
                                                        <option value="car_rental_with_driver">Location de voiture avec chauffeur</option>
                                                        <option value="autre">autre</option>
                                                    </x-select-input>
                                                    <div x-show="expense.transport_type === 'car_rental_with_driver'" x-transition class="mt-4 p-3 border border-blue-200 rounded bg-blue-50">
                                                        <x-label class="border border-gray-200 px-5 py-2">Pour les raisons suivantes: (cocher les cases correspondantes)</x-label>
                                                        <table class="w-full mt-2">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center text-gray-600 border border-blue-600 px-2 py-1 text-xs">
                                                                        <x-label>{{ __('passenger') }}</x-label>
                                                                    </th>
                                                                    <th class="text-center text-gray-600 border border-blue-600 px-2 py-1 text-xs">
                                                                        <x-label>{{ __('distance') }}</x-label>
                                                                    </th>
                                                                    <th class="text-center text-gray-600 border border-blue-600 px-2 py-1 text-xs">
                                                                        <x-label>{{ __('material') }}</x-label>
                                                                    </th>
                                                                    <th class="text-center text-gray-600 border border-blue-600 px-2 py-1 text-xs">
                                                                        <x-label>{{ __('visits') }}</x-label>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="py-2 text-center text-gray-600 border border-blue-600 text-xs">
                                                                        <input type="checkbox" value="1"
                                                                            x-bind:name="`expenses[${index}][passenger]`"
                                                                            x-model="expense.passenger"
                                                                            :checked="expense.passenger === 1 || expense.passenger === true">
                                                                    </td>
                                                                    <td class="py-2 text-center text-gray-600 border border-blue-600 text-xs">
                                                                        <input type="checkbox" value="1"
                                                                            x-bind:name="`expenses[${index}][distance]`"
                                                                            x-model="expense.distance"
                                                                            :checked="expense.distance === 1 || expense.distance === true">
                                                                    </td>
                                                                    <td class="py-2 text-center text-gray-600 border border-blue-600 text-xs">
                                                                        <input type="checkbox" value="1"
                                                                            x-bind:name="`expenses[${index}][material]`"
                                                                            x-model="expense.material"
                                                                            :checked="expense.material === 1 || expense.material === true">
                                                                    </td>
                                                                    <td class="py-2 text-center text-gray-600 border border-blue-600 text-xs">
                                                                        <input type="checkbox" value="1"
                                                                            x-bind:name="`expenses[${index}][visits]`"
                                                                            x-model="expense.visits"
                                                                            :checked="expense.visits === 1 || expense.visits === true">
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="expense.type === 'extra_meal'">
                                                <x-select-input x-bind:name="`expenses[${index}][meal_location]`"
                                                    x-model="expense.meal_location" required>
                                                    <option value="">--sélectionner--</option>
                                                    <option value="temps de transport">temps de transport</option>
                                                </x-select-input>
                                            </template>
                                            <template x-if="expense.type === 'visa'">
                                                <x-select-input x-bind:name="`expenses[${index}][meal_location]`"
                                                    x-model="expense.meal_location" required>
                                                    <option value="">--sélectionner--</option>
                                                    <option value="Frais de Visa">Frais de Visa</option>
                                                </x-select-input>
                                            </template>
                                            <template x-if="expense.type === 'Receptions'">
                                                <x-select-input x-bind:name="`expenses[${index}][meal_location]`"
                                                    x-model="expense.meal_location" required>
                                                    <option value="">--sélectionner--</option>
                                                    <option value="au lieu de Frais d'receptions">au lieu de Frais d'receptions</option>
                                                </x-select-input>
                                            </template>
                                            <template x-if="expense.type === 'other'">
                                                <textarea x-bind:name="`expenses[${index}][description]`" x-model="expense.description" rows="2" required
                                                    class="appearance-none block w-full bg-white text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none border border-blue-700 focus:bg-white focus:border-blue-900"
                                                    placeholder="Décrivez la nature de la dépense"></textarea>
                                            </template>
                                        </td>
                                        <td class="px-6 text-center border border-gray-200 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex justify-center space-x-2">
                                                <button type="button"
                                                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center"
                                                    x-on:click="removeExpense(index)">{{ __('Delete') }}</button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <button x-on:click="addExpense()" type="button"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Ajouter depense
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('expensesManager', () => ({
                    expenses: [],

                    addExpense() {
                        this.expenses.push({
                            type: '',
                            description: '',
                            transport_type: '',
                            meal_location: '',
                            passenger: false,
                            distance: false,
                            material: false,
                            visits: false
                        });
                    },

                    removeExpense(index) {
                        if (this.expenses.length > 0) {
                            this.expenses.splice(index, 1);
                        }
                    },

                    handleTransportTypeChange(index) {
                        // Reset reason checkboxes when transport type changes
                        if (this.expenses[index].transport_type !== 'car_rental_with_driver') {
                            this.expenses[index].passenger = false;
                            this.expenses[index].distance = false;
                            this.expenses[index].material = false;
                            this.expenses[index].visits = false;
                        }
                    },

                    normalizeCheckboxValues(expenses) {
                        // Convert checkbox values from "1"/"0"/null to boolean
                        return expenses.map(expense => ({
                            ...expense,
                            passenger: expense.passenger == 1 || expense.passenger === true,
                            distance: expense.distance == 1 || expense.distance === true,
                            material: expense.material == 1 || expense.material === true,
                            visits: expense.visits == 1 || expense.visits === true
                        }));
                    },

                    init() {
                        // Initialize with old input if available
                        @if (old('expenses'))
                            const oldExpenses = @json(old('expenses'));
                            this.expenses = this.normalizeCheckboxValues(oldExpenses);
                            console.log('Loaded from old:', this.expenses);
                        @else
                            // Default empty state - add one empty expense row
                            this.expenses = [{
                                type: '',
                                description: '',
                                transport_type: '',
                                meal_location: '',
                                passenger: false,
                                distance: false,
                                material: false,
                                visits: false
                            }];
                        @endif
                    }
                }));
            });
        </script>

        <x-form-divider>Observations</x-form-divider>
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full px-3">
                <x-label>
                    Observation/facultatife
                </x-label>
                <textarea name="description" id="description" rows="4"
                    class="appearance-none block w-full bg-white text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none border border-blue-700 focus:bg-white focus:border-blue-900">{{ old('description') }}</textarea>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full px-3 py-3">
                <div class="flex justify-center items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                    <div>
                        <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-md font-bold px-5 py-2.5 focus:z-10"
                            name="action" value="draft">{{ __('Save as Draft') }}
                        </button>
                    </div>
                    <div>
                        <button class="text-blue-700 bg-white hover:bg-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-lg text-md w-48 px-5 py-2.5 text-center border border-gray-200"
                            name="action" value="submit">{{ __('Submit Mission') }}
                        </button>
                    </div>
                    <div>
                        <a href="{{route('mission_orders.index')}}">
                        <button class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-bold rounded-lg text-md w-48 px-5 py-2.5 text-center"
                            type="button">{{ __('Delete') }}
                        </button></a>
                    </div>
                </div>
                {{-- <x-primary-button data-modal-toggle="draftOrSubmitModal" type="button">Soumettre</x-primary-button>
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
                                <div class="text-base leading-relaxed text-gray-500">
                                    <ul>
                                        <li>{{ __('When save the mission as draft, you can edit or delete it later.') }}
                                        </li>
                                        <li>{{ __('When submit the mission, you can not edit or delete it, and the mission will go to the approve process.') }}
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
                                            data-modal-toggle="draftOrSubmitModal">{{ __('Submit Mission') }}
                                        </button>
                                    </div>
                                    <div>
                                        <a href="{{route('mission_orders.index')}}">
                                        <button
                                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                                            type="button">
                                            {{ __('Delete') }}
                                        </button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </form>
@endsection

@extends('layouts.app')
@section('title', __('Create Mission'))
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Demander une mission</h1>
                <p class="text-sm text-gray-600 mt-1">Remplissez le formulaire pour créer une nouvelle demande de mission</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Brouillon
                </span>
            </div>
        </div>
    </div>

    <form id="mainForm" action="{{ route('mission_orders.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Basic Information Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Informations de base</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <x-label class="block text-sm font-medium text-gray-700 mb-2">
                            Mission #
                        </x-label>
                        <x-readonly-text-input name="order_number" value="{{ $mission_number }}" />
                    </div>
                    <div>
                        <x-label class="block text-sm font-medium text-gray-700 mb-2">
                            Date le Ordre:<span class="text-red-500 ml-1">*</span>
                        </x-label>
                        <x-date-time-input
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            name="order_date" value="{{ (new \DateTime())->format('Y-m-d') }}" type="date" readonly>
                        </x-date-time-input>
                    </div>
                    <div>
                        <x-label class="block text-sm font-medium text-gray-700 mb-2">
                            Etat de la demande
                        </x-label>
                        <x-disabled-select-input name="status" required>
                            <option value="draft">Brouillon</option>
                        </x-disabled-select-input>
                    </div>
                </div>
            </div>
        </div>
        <!-- Missionaire Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Missionaire</h2>
            </div>
            <div class="p-6">
                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-label class="block text-sm font-medium text-gray-700 mb-2">
                            Nom, Prénom
                        </x-label>
                        <x-readonly-text-input name="full_name"
                            value="{{ auth()->user()->employee->first_name }} {{ auth()->user()->employee->last_name }}" />
                    </div>
                    <div>
                        <x-label class="block text-sm font-medium text-gray-700 mb-2">
                            Fonction
                        </x-label>
                        <x-readonly-text-input name="position" value="{{ auth()->user()->employee->position }}" />
                    </div>
                    <div>
                        <x-label class="block text-sm font-medium text-gray-700 mb-2">
                            Ville de résidence administrative
                        </x-label>
                        <x-readonly-text-input name="administrativ_residence"
                            value="{{ auth()->user()->employee->administrativ_residence }}" />
                    </div>
                    <div>
                        <x-label class="block text-sm font-medium text-gray-700 mb-2">
                            Secteur
                        </x-label>
                        <input type="hidden" name="department_id" value="{{ auth()->user()->employee->department_id }}">
                        <x-readonly-text-input name="department_name" value="{{ auth()->user()->employee->department->name }}" />
                    </div>
                </div>
            </div>
        </div>
        <!-- Mission Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Mission</h2>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <x-label class="block text-sm font-medium text-gray-700 mb-2">
                        Objet/Motif<span class="text-red-500 ml-1">*</span>
                    </x-label>
                    <textarea name="purpose" rows="4" required minlength="100" maxlength="500"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                        oninput="updateCharCounter(this)"
                        placeholder="Décrivez en détail l'objet et le motif de votre mission...">{{ old('purpose') }}</textarea>
                    <div class="flex justify-between items-center mt-2">
                        <small class="text-gray-500">Minimum 100 caractères, Maximum 500 caractères</small>
                        <small id="char-counter" class="text-gray-500">0/500</small>
                    </div>
                    <div id="purpose-error" class="text-red-500 text-sm mt-1"></div>
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
        <div class="mb-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="if_conge" id="if_conge"
                            value="1" @checked(old('if_conge', 0) > 0)
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm font-medium text-gray-700">Conge pendant mission</span>
                    </label>
                </div>
                <div id="conge_container" class="hidden">
                    <div>
                        <x-label class="block text-sm font-medium text-gray-700 mb-2">
                            Détails du congé
                        </x-label>
                        <textarea name="conge" id="conge_input" disabled
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                            rows="3"
                            placeholder="Précisez les détails du congé pendant la mission...">{{ old('conge') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const congeCheckbox = document.getElementById('if_conge');
                const congeContainer = document.getElementById('conge_container');
                const congeInput = document.getElementById('conge_input');
                function toggleconge() {
                    if (congeCheckbox.checked) {
                        congeContainer.classList.remove('hidden');
                        congeInput.disabled = false;
                        congeInput.required = true;
                    } else {
                        congeContainer.classList.add('hidden');
                        congeInput.disabled = true;
                        congeInput.required = false;
                        congeInput.value = '';
                    }
                }
                toggleconge();
                congeCheckbox.addEventListener('change', toggleconge);
            });
        </script>

        <!-- Travel Details Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Détail du déplacement résidence administrative - lieu de la mission</h2>
            </div>
            <div class="p-6">
        <div class="space-y-4">
                    <!-- Outbound Journey -->
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Trajet aller</h3>
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <x-label class="block text-sm font-medium text-gray-700 mb-1">
                                    Lieu de départ<span class="text-red-500">*</span>
                                </x-label>
                                <x-text-input required name="departure_location" id="departure_location"
                                    value="{{ old('departure_location') }}" onblur="returnLocationValue();"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <div>
                                <x-label class="block text-sm font-medium text-gray-700 mb-1">
                                    Lieu de mission<span class="text-red-500">*</span>
                                </x-label>
                                <x-text-input required name="arrive_location" id="arrive_location"
                                    value="{{ old('arrive_location') }}" onblur="endMissionLocationValue();"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <div>
                                <x-label class="block text-sm font-medium text-gray-700 mb-1">
                                    Date de départ<span class="text-red-500">*</span>
                                </x-label>
                                <x-date-time-input class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    name="start_date" id="start_date" value="{{ old('start_date') }}" type="date" required></x-date-time-input>
                            </div>
                            <div>
                                <x-label class="block text-sm font-medium text-gray-700 mb-1">
                                    Heure de départ<span class="text-red-500">*</span>
                                </x-label>
                                <x-date-time-input class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    name="start_time" value="{{ old('start_time') }}" type="time" required></x-date-time-input>
                            </div>
                            <div>
                                <x-label class="block text-sm font-medium text-gray-700 mb-1">
                                    Heure d'arrivée<span class="text-red-500">*</span>
                                </x-label>
                                <x-date-time-input class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    name="start_time2" value="{{ old('start_time2') }}" type="time" required></x-date-time-input>
                            </div>
                        </div>
                    </div>

                    <!-- Return Journey -->
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Trajet retour</h3>
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <x-label class="block text-sm font-medium text-gray-700 mb-1">
                                    Lieu de mission<span class="text-red-500">*</span>
                                </x-label>
                                <x-text-input required name="endMission_location" id="endMission_location"
                                    value="{{ old('endMission_location') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <div>
                                <x-label class="block text-sm font-medium text-gray-700 mb-1">
                                    Lieu de retour<span class="text-red-500">*</span>
                                </x-label>
                                <x-text-input required name="return_location" id="return_location"
                                    value="{{ old('return_location') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <div>
                                <x-label class="block text-sm font-medium text-gray-700 mb-1">
                                    Date de retour<span class="text-red-500">*</span>
                                </x-label>
                                <x-date-time-input class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    name="end_date" id="end_date" value="{{ old('end_date') }}" type="date" required></x-date-time-input>
                            </div>
                            <div>
                                <x-label class="block text-sm font-medium text-gray-700 mb-1">
                                    Heure de départ<span class="text-red-500">*</span>
                                </x-label>
                                <x-date-time-input class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    name="end_time2" value="{{ old('end_time2') }}" type="time" required></x-date-time-input>
                            </div>
                            <div>
                                <x-label class="block text-sm font-medium text-gray-700 mb-1">
                                    Heure d'arrivée<span class="text-red-500">*</span>
                                </x-label>
                                <x-date-time-input class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    name="end_time" value="{{ old('end_time') }}" type="time" required></x-date-time-input>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function returnLocationValue() {
                        document.getElementById('return_location').value = document.getElementById('departure_location').value;
                    }
                    function endMissionLocationValue() {
                        document.getElementById('endMission_location').value = document.getElementById('arrive_location').value;
                    }
                </script>

                <!-- Weekend Warning -->
                <div class="mt-4">
                    <input type="hidden" name="has_weekend" id="has_weekend" value="{{old('has_weekend', 0)}}">
                    <div id="weekend-warning" class="hidden bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-amber-800">Attention</h3>
                                <p class="text-sm text-amber-700 mt-1">
                                    Votre mission comprend un weekend (samedi ou dimanche). Veuillez fournir une justification dans la zone Objet/Motif de la mission.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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


        <!-- Mission Fees Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Frais Mission</h2>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <x-label class="block text-sm font-medium text-gray-700 mb-2">
                        Pays de Mission<span class="text-red-500 ml-1">*</span>
                    </x-label>
                    <x-select-input name="bareme_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                            $('select[name="bareme_id"]').select2({
                                placeholder: "Sélectionner un pays",
                                allowClear: true
                            });
                        });
                    </script>
                </div>
        <!-- Advance Payment Section -->
                <div class="mb-6">
                    <x-label class="block text-sm font-medium text-gray-700 mb-3">
                        Demande d'avance<span class="text-red-500 ml-1">*</span>
                    </x-label>
                    <div class="flex space-x-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" value="1" name="needs_advance" id="needs_advance_yes"
                                @checked(old('advance', 0) > 0) required
                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 advance-radio">
                            <span class="ml-2 text-sm font-medium text-gray-700">OUI</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" value="0" name="needs_advance" id="needs_advance_no"
                                @checked(old('advance', 0) == 0) required
                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 advance-radio">
                            <span class="ml-2 text-sm font-medium text-gray-700">NON</span>
                        </label>
                    </div>
                </div>

                <div id="advance_amount_container" class="hidden">
                    <div class="border border-blue-200 rounded-lg p-4 bg-blue-50">
                        <x-label class="block text-sm font-medium text-gray-700 mb-2">
                            Montant de l'avance (INR - Roupie indienne)<span class="text-red-500 ml-1">*</span>
                        </x-label>
                        <x-text-input name="advance" value="{{ old('advance') }}" id="advance_amount_input"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        <p class="text-sm text-gray-600 mt-2">
                            Maximum autorisé: <span id="max_advance" class="font-semibold">0</span> INR (75% du total hébergement)
                        </p>
                        <p id="advance_error" class="text-red-500 text-sm mt-1 hidden">Le montant demandé dépasse 75% du total hébergement.</p>
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

        <!-- Fee Coverage Options -->
                <div class="space-y-4">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <x-label class="block text-sm font-medium text-gray-700 mb-3">
                            Prise en charge des frais de transport<span class="text-red-500 ml-1">*</span> (Avion, Train)
                        </x-label>
                        <div class="flex space-x-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" value="1" name="charge" id="charge_yes" required
                                    @checked(old('charge', 1) == 1)
                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">OUI</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" value="0" name="charge" id="charge_no" required
                                    @checked(old('charge', 1) == 0)
                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">NON</span>
                            </label>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4">
                        <x-label class="block text-sm font-medium text-gray-700 mb-3">
                            Prise en charge des frais de transport<span class="text-red-500 ml-1">*</span> (Taxi/Uber, Transport public, etc..)
                        </x-label>
                        <div class="flex space-x-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" value="1" name="charge1" id="charge1_yes" required
                                    @checked(old('charge1', 1) == 1)
                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">OUI</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" value="0" name="charge1" id="charge1_no" required
                                    @checked(old('charge1', 1) == 0)
                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">NON</span>
                            </label>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4">
                        <x-label class="block text-sm font-medium text-gray-700 mb-3">
                            Prise en charge frais d'hébergement<span class="text-red-500 ml-1">*</span>
                        </x-label>
                        <div class="flex space-x-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" value="1" name="ijm" required
                                    @checked(old('ijm', 1) == 1)
                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">OUI</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" value="0" name="ijm" required
                                    @checked(old('ijm', 1) == 0)
                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">NON</span>
                            </label>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4">
                        <x-label class="block text-sm font-medium text-gray-700 mb-3">
                            Prise en charge frais de repas<span class="text-red-500 ml-1">*</span>
                        </x-label>
                        <div class="flex space-x-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" value="1" name="repas" required
                                    @checked(old('repas', 1) == 1)
                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">OUI</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" value="0" name="repas" required
                                    @checked(old('repas', 1) == 0)
                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">NON</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Expenses Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Dépenses prévues supplémentaires</h2>
            </div>
            <div class="p-6">
                <div class="flex flex-col" x-data="expensesManager()">
                    <div class="-m-1.5 overflow-x-auto">
                        <div class="p-1.5 min-w-full inline-block align-middle">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200 border border-gray-300 rounded-lg">
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
                                                        {{-- <option value="Transport Avion">Transport Avion</option>
                                                        <option value="Transport en commun / Taxi(uber)">Transport en commun / Taxi(uber)</option> --}}
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
                                                    <option value="Sur lieu de mission">Sur lieu de mission</option>
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
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Ajouter une dépense
                        </button>
                    </div>
                </div>
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

        <!-- Observations Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Observations</h2>
            </div>
            <div class="p-6">
                <div>
                    <x-label class="block text-sm font-medium text-gray-700 mb-2">
                        Observations (facultatif)
                    </x-label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                        placeholder="Ajoutez des observations ou des notes supplémentaires...">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4">
                <div class="flex flex-col sm:flex-row justify-center items-center space-y-3 sm:space-y-0 sm:space-x-4">
                    <button type="submit" name="action" value="draft"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Enregistrer comme brouillon
                    </button>

                    <button type="submit" name="action" value="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Soumettre la mission
                    </button>

                    <a href="{{ route('mission_orders.index') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
@endsection

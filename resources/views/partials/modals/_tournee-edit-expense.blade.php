<div id="editExpenseModal-{{ $expense->id }}" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-auto fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full mt-40 max-w-2xl h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow p-2">
            <!-- Modal header -->
            <div class="flex justify-between items-center p-2 rounded-t border-b">
                <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left blue-color">
                    {{ __('Edit Expense') }}
                </div>
                <div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-toggle="editExpenseModal-{{ $expense->id }}">
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
            <div class="p-4 overflow-y-auto" style="max-height: 700px">
                <form id="editExpenseForm-{{ $expense->id }}" method="POST"
                    action="{{ route('tournee_expenses.update', $expense->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-2/3 px-3">
                            <div class="flex flex-wrap -mx-3 mb-0">
                                <x-label>Type de dépense<span class="text-red-500">*</span></x-label>
                                <x-select-input id="type-edit-{{ $expense->id }}" name="type"
                                    onchange="updateExpenseFieldsEdit('{{ $expense->id }}')">
                                    <option value="">--sélectionner le type--</option>
                                    <option value="transport" @selected(old('type', $expense->type) == 'transport')>Transport</option>
                                    {{-- <option value="extra_accomodation" @selected(old('type', $expense->type) == 'extra_accomodation')>Hébergement</option> --}}
                                    <option value="extra_meal" @selected(old('type', $expense->type) == 'extra_meal')>Repas</option>
                                    <option value="visa" @selected(old('type', $expense->type) == 'visa')>Visa</option>
                                    <option value="Receptions" @selected(old('type', $expense->type) == 'Receptions')>Receptions</option>
                                    <option value="other" @selected(old('type', $expense->type) == 'other')>Autre</option>
                                </x-select-input>
                            </div>

                            <!-- Transport Type Fields -->
                            <div id="transportFields-edit-{{ $expense->id }}" class="{{ $expense->type == 'transport' ? '' : 'hidden' }}">
                                <div class="flex flex-wrap -mx-3 mb-0">
                                    <x-label>Type de transport<span class="text-red-500">*</span></x-label>
                                    <x-select-input id="transport_type-edit-{{ $expense->id }}" name="transport_type">
                                        <option value="">--sélectionner le type de transport--</option>
                                        <option value="plane" @selected(old('transport_type', $expense->transport_type) == 'plane')>Avion</option>
                                        <option value="train" @selected(old('transport_type', $expense->transport_type) == 'train')>Train</option>
                                        <option value="taxi_uber" @selected(old('transport_type', $expense->transport_type) == 'taxi_uber')>Taxi/Uber</option>
                                        <option value="public_transport" @selected(old('transport_type', $expense->transport_type) == 'public_transport')>Transport public</option>
                                        <option value="car_rental_with_driver" @selected(old('transport_type', $expense->transport_type) == 'car_rental_with_driver')>Location de voiture avec chauffeur</option>
                                        <option value="autre" @selected(old('transport_type', $expense->transport_type) == 'autre')>autre</option>
                                    </x-select-input>
                                </div>
                                <div class="flex flex-wrap -mx-3 mb-0">
                                    <x-label>Détails du transport</x-label>
                                    <textarea id="transport_details-edit-{{ $expense->id }}" name="transport_details" rows="2" placeholder="Numéro de vol, numéro de train, etc."
                                        class="appearance-none block w-full bg-white text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none border border-blue-700 focus:bg-white focus:border-blue-900">{{ old('transport_details', $expense->transport_details) }}</textarea>
                                </div>
                                <div class="flex flex-wrap -mx-3 mb-0" id="reasonsTable-{{ $expense->id }}">
                                    <x-label class="border border-gray-200 px-5 py-2">Pour les raisons suivantes: (cocher les cases correspondantes)</x-label>
                                    <table class="w-full" style="table-layout: fixed;width: 100%;">
                                        <thead>
                                            <tr>
                                                <th style="word-wrap: break-word;text-wrap: wrap;" class="text-center text-gray-600 border border-blue-600 px-2 py-1">
                                                    <x-label class="text-xs">{{ __('passenger') }}</x-label>
                                                </th>
                                                <th style="word-wrap: break-word;text-wrap: wrap;" class="text-center text-gray-600 border border-blue-600 px-2 py-1">
                                                    <x-label class="text-xs">{{ __('distance') }}</x-label>
                                                </th>
                                                <th style="word-wrap: break-word;text-wrap: wrap;" class="text-center text-gray-600 border border-blue-600 px-2 py-1">
                                                    <x-label class="text-xs">{{ __('material') }}</x-label>
                                                </th>
                                                <th style="word-wrap: break-word;text-wrap: wrap;" class="text-center text-gray-600 border border-blue-600 px-2 py-1">
                                                    <x-label class="text-xs">{{ __('visits') }}</x-label>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="word-wrap: break-word;text-wrap: wrap;" class="py-2 text-center text-gray-600 border border-blue-600 text-xs">
                                                    <input type="checkbox" value="1" name="passenger" @checked(old('passenger', $expense->passenger))>
                                                </td>
                                                <td style="word-wrap: break-word;text-wrap: wrap;" class="py-2 text-center text-gray-600 border border-blue-600 text-xs">
                                                    <input type="checkbox" value="1" name="distance" @checked(old('distance', $expense->distance))>
                                                </td>
                                                <td style="word-wrap: break-word;text-wrap: wrap;" class="py-2 text-center text-gray-600 border border-blue-600 text-xs">
                                                    <input type="checkbox" value="1" name="material" @checked(old('material', $expense->material))>
                                                </td>
                                                <td style="word-wrap: break-word;text-wrap: wrap;" class="py-2 text-center text-gray-600 border border-blue-600 text-xs">
                                                    <input type="checkbox" value="1" name="visits" @checked(old('visits', $expense->visits))>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Meal Type Fields (hidden by default) -->
                            <div id="mealFields-edit-{{ $expense->id }}" class="hidden">
                                <div class="flex flex-wrap -mx-3 mb-0">

                                    <x-select-input id="meal_location-edit-{{ $expense->id }}" name="meal_location">
                                        <option value="">--sélectionner--</option>
                                        <option @selected(old('meal_location', $expense->meal_location) == 'temps de transport') value="temps de transport">temps de transport</option>
                                    </x-select-input>
                                </div>
                                {{-- <div class="flex flex-wrap -mx-3 mb-0">
                                    <x-label>Nombre de personnes<span class="text-red-500">*</span></x-label>
                                    <x-text-input type="number" id="meal_participants" name="meal_participants"
                                        value="{{ old('meal_participants', 1) }}" min="1" />
                                </div> --}}
                            </div>

                            <div id="visaFields-edit-{{ $expense->id }}" class="hidden">
                                <div class="flex flex-wrap -mx-3 mb-0">
                                    <x-select-input id="meal_location-edit-{{ $expense->id }}" name="meal_location">
                                        <option value="">--sélectionner--</option>
                                        <option @selected(old('meal_location', $expense->meal_location) == 'Frais de Visa') value="Frais de Visa">Frais de Visa</option>
                                    </x-select-input>
                                </div>
                            </div>
                            <div id="ReceptionsFields-edit-{{ $expense->id }}" class="hidden">
                                <div class="flex flex-wrap -mx-3 mb-0">
                                    <x-select-input id="meal_location-edit-{{ $expense->id }}" name="meal_location">
                                        <option value="">--sélectionner--</option>
                                        <option @selected(old('meal_location', $expense->meal_location) == 'Frais d’Receptions') value="Frais d’Receptions">Frais d’Receptions</option>
                                    </x-select-input>
                                </div>
                            </div>
                            {{-- <div id="herFields-edit-{{ $expense->id }}" class="hidden">
                                <div class="flex flex-wrap -mx-3 mb-0">

                                    <x-select-input id="meal_location-edit-{{ $expense->id }}" name="meal_location">
                                        <option value="">--sélectionner--</option>
                                        <option @selected(old('meal_location', $expense->meal_location) == 'Frais hébergement') value="Frais hébergement">Frais hébergement</option>
                                    </x-select-input>
                                </div>
                            </div> --}}
                            <div id="other-edit-{{ $expense->id }}" class="hidden">
                                <div class="flex flex-wrap -mx-3 mb-0">
                                    <x-text-input type="text" id="description-edit-{{ $expense->id }}" name="description"
                                        value="{{ old('description', $expense->description) }}" />

                                </div>

                            </div>

                            {{-- <div class="flex flex-wrap -mx-3 mb-0">
                                <x-label>Nature de dépense<span class="text-red-500">*</span></x-label>
                                <textarea id="description-edit-{{ $expense->id }}" name="description" rows="4" required
                                    class="appearance-none block w-full bg-white text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none border border-blue-700 focus:bg-white focus:border-blue-900">{{ old('description', $expense->description) }}</textarea>
                            </div> --}}

                            <div class="-mx-3 w-full mb-0">
                                <x-label>Date de dépense<span class="text-red-500">*</span></x-label>
                                <x-date-time-input class="w-full" id="expense_date-edit-{{ $expense->id }}" name="expense_date"
                                    value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" type="date" required>
                                </x-date-time-input>
                            </div>
                            <div class="flex flex-wrap -mx-3 mb-0">
                                <x-label>Montant<span class="text-red-500">*</span></x-label>
                                <x-text-input type="number" step="any" required id="amount-edit-{{ $expense->id }}" name="amount"
                                    value="{{ old('amount', $expense->amount) }}" />
                            </div>
                            <div class="flex flex-wrap -mx-3 mb-0">
                                <x-select-currency :selectedCurrency="old('currency', $expense->currency)" />
                            </div>
                        </div>
                        <!-- Expense Document -->
                        <div class="w-1/3 h-1/2 px-3">
                            <div class="relative w-full h-full mx-auto">
                                <!-- PDF Preview -->
                                <div id="pdfPreview-edit-{{ $expense->id }}"
                                    class="{{ pathinfo($expense->expense_document, PATHINFO_EXTENSION) === 'pdf' ? '' : 'hidden' }} w-full h-full">
                                    @if (pathinfo($expense->expense_document, PATHINFO_EXTENSION) === 'pdf')
                                        <embed src="{{ asset('storage/' . $expense->expense_document) }}"
                                            type="application/pdf" width="100%" height="100%">
                                        <div class="text-center mt-2 text-sm text-gray-600">
                                            Current PDF: {{ basename($expense->expense_document) }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Image Preview -->
                                <img id="expenseDocumentPreview-edit-{{ $expense->id }}"
                                    src="{{ pathinfo($expense->expense_document, PATHINFO_EXTENSION) === 'pdf' ? Vite::asset('resources/images/blank-expense.jpg') : asset('storage/' . $expense->expense_document) }}"
                                    alt="Document de dépenses"
                                    class="{{ pathinfo($expense->expense_document, PATHINFO_EXTENSION) === 'pdf' ? 'hidden' : '' }} object-cover w-full h-full">

                                <!-- File Upload Controls -->
                                <div
                                    class="rounded-xl absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-50 hover:opacity-100 transition-opacity">
                                    <input type="file" name="expense_document"
                                        id="expense_document-edit-{{ $expense->id }}" class="hidden"
                                        accept=".pdf,.jpg,.jpeg,.png,.gif">
                                    <button type="button"
                                        onclick="document.getElementById('expense_document-edit-{{ $expense->id }}').click()"
                                        class="text-white bg-blue-600 hover:bg-blue-700 rounded-xl w-1/2">
                                        <img src="{{ Vite::asset('resources/images/browse-image.png') }}"
                                            alt="Browse Files">
                                    </button>
                                </div>

                                <!-- File Info Display -->
                                <div id="fileInfo-edit-{{ $expense->id }}" class="mt-2 text-sm text-gray-600 hidden">
                                    New file: <span id="fileName-edit-{{ $expense->id }}"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                        <button type="button" data-modal-toggle="editExpenseModal-{{ $expense->id }}"
                            class="text-gray-500 bg-white hover:bg-gray-100 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit"
                            class="text-white blue-bg hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5">
                            {{ __('Update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Define functions for this modal instance
    function handleFileSelectEdit_{{ $expense->id }}(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Update file info display
        document.getElementById('fileName-edit-{{ $expense->id }}').textContent = file.name;
        document.getElementById('fileInfo-edit-{{ $expense->id }}').classList.remove('hidden');

        // Handle preview based on file type
        if (file.type === 'application/pdf') {
            // Show PDF preview
            document.getElementById('expenseDocumentPreview-edit-{{ $expense->id }}').classList.add('hidden');
            document.getElementById('pdfPreview-edit-{{ $expense->id }}').classList.remove('hidden');

            // Create or update PDF embed
            let pdfEmbed = document.querySelector('#pdfPreview-edit-{{ $expense->id }} embed');
            if (!pdfEmbed) {
                const container = document.getElementById('pdfPreview-edit-{{ $expense->id }}');
                container.innerHTML = `<embed src="${URL.createObjectURL(file)}" type="application/pdf" width="100%" height="100%">
                                  <div class="text-center mt-2 text-sm text-gray-600">PDF Preview</div>`;
            } else {
                pdfEmbed.src = URL.createObjectURL(file);
            }
        } else if (file.type.startsWith('image/')) {
            // Show image preview
            document.getElementById('pdfPreview-edit-{{ $expense->id }}').classList.add('hidden');
            document.getElementById('expenseDocumentPreview-edit-{{ $expense->id }}').classList.remove('hidden');

            // Update image preview
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('expenseDocumentPreview-edit-{{ $expense->id }}').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    function updateExpenseFieldsEdit(expenseId) {
        const expenseType = document.getElementById('type-edit-' + expenseId).value;
        const transportFields = document.getElementById('transportFields-edit-' + expenseId);
        const mealFields = document.getElementById('mealFields-edit-' + expenseId);
        const visaFields = document.getElementById('visaFields-edit-' + expenseId);
        const ReceptionsFields = document.getElementById('ReceptionsFields-edit-' + expenseId);
        // const herFields = document.getElementById('herFields-edit-' + expenseId);
        const other = document.getElementById('other-edit-' + expenseId);
        const reasonsTable = document.getElementById('reasonsTable-'+expenseId);

        // Hide all fields first
        transportFields.classList.add('hidden');
        mealFields.classList.add('hidden');
        visaFields.classList.add('hidden');
        ReceptionsFields.classList.add('hidden');
        // herFields.classList.add('hidden');
        other.classList.add('hidden');
        reasonsTable.classList.add('hidden'); // Hide reasons table by default


        // Show relevant fields based on selected type
        if (expenseType === 'transport') {
            transportFields.classList.remove('hidden');
            // Set required attributes for transport fields
            document.getElementById('transport_type-edit-' + expenseId).required = true;
            document.getElementById('meal_location-edit-' + expenseId).required = false;
            //document.getElementById('meal_participants-edit-' + expenseId).required = false;
            // Show reasons table only if "car_rental_with_driver" is selected
            const transportType = document.getElementById('transport_type-edit-'+expenseId).value;
            if (transportType === 'car_rental_with_driver') {
                reasonsTable.classList.remove('hidden');
            }
        } else if (expenseType === 'extra_meal') {
            mealFields.classList.remove('hidden');
            // Set required attributes for meal fields
            document.getElementById('transport_type-edit-' + expenseId).required = false;
            document.getElementById('meal_location-edit-' + expenseId).required = true;
            //document.getElementById('meal_participants-edit-' + expenseId).required = true;
        // } else if (expenseType === 'extra_accomodation') {
        //     herFields.classList.remove('hidden');
        //     // Set required attributes for meal fields
        //     document.getElementById('transport_type-edit-' + expenseId).required = false;
        //     document.getElementById('meal_location-edit-' + expenseId).required = true;
        //     //document.getElementById('meal_participants').required = true;
        } else if (expenseType === 'visa') {
            visaFields.classList.remove('hidden');
            // Set required attributes for meal fields
            document.getElementById('transport_type-edit-' + expenseId).required = false;
            document.getElementById('meal_location-edit-' + expenseId).required = true;
            //document.getElementById('meal_participants-edit-' + expenseId).required = true;
        }else if (expenseType === 'Receptions') {
            ReceptionsFields.classList.remove('hidden');
            // Set required attributes for meal fields
            document.getElementById('transport_type-edit-' + expenseId).required = false;
            document.getElementById('meal_location-edit-' + expenseId).required = true;
            //document.getElementById('meal_participants-edit-' + expenseId).required = true;
        } else {
                        other.classList.remove('hidden');
            document.getElementById('transport_type-edit-' + expenseId).required = false;
            document.getElementById('meal_location-edit-' + expenseId).required = false;
            //document.getElementById('meal_participants').required = false;
        }
    }

    // Set up event listeners when DOM is ready
    document.addEventListener("DOMContentLoaded", function() {
        const fileInput = document.getElementById('expense_document-edit-{{ $expense->id }}');
        if (fileInput) {
            fileInput.addEventListener('change', handleFileSelectEdit_{{ $expense->id }});
        }
document.getElementById('transport_type-edit-{{$expense->id}}').addEventListener('change', function() {
            const reasonsTable = document.getElementById('reasonsTable-{{$expense->id}}');
            if (this.value === 'car_rental_with_driver') {
                reasonsTable.classList.remove('hidden');
            } else {
                reasonsTable.classList.add('hidden');
            }
            checkRequiredFields();
        });
        // Initialize fields based on current expense type
        updateExpenseFieldsEdit('{{ $expense->id }}');
    });
</script>

<!-- Expenses Table and Modals -->
<h2 class="pb-2 text-sm font-bold text-blue-700">Transport et Frais divers</h2>
<div class="flex flex-col">
    <div class="-m-1.5 overflow-x-auto">
        <div class="p-1.5 min-w-full inline-block align-middle">
            <div class="overflow-hidden">
                <!-- Updated Upload Modal -->
                <div id="photo-upload-modal"
                    class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
                    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
                        <h3 class="text-lg font-semibold mb-4">Télécharger le justificatif de dépenses</h3>
                        <!-- File Preview -->
                        <div id="file-preview-container" class="mb-4 hidden">
                            <img id="image-preview" class="w-full h-64 object-contain border rounded-md hidden">
                            <iframe id="pdf-preview" class="w-full h-64 border rounded-md hidden"></iframe>
                        </div>
                        <!-- Upload Controls -->
                        <div class="flex flex-col items-center mb-4">
                            <label for="expense-receipt"
                                class="cursor-pointer bg-blue-100 text-blue-600 px-4 py-2 rounded-md hover:bg-blue-200 mb-2">
                                <i class="fas fa-file-upload mr-2"></i>Sélectionner le fichier
                            </label>
                            <input type="file" id="expense-receipt" accept="image/*,.pdf" class="hidden">
                            <p id="file-name" class="text-sm text-gray-500 mt-2"></p>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" id="cancel-upload"
                                class="px-4 py-2 bg-gray-300 rounded">Annuler</button>
                            <button type="button" id="confirm-upload"
                                class="px-4 py-2 bg-blue-600 text-white rounded">Télécharger</button>
                        </div>
                    </div>
                </div>

                <!-- Updated View Modal -->
                <div id="file-view-modal"
                    class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
                    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-2xl">
                        <h3 class="text-lg font-semibold mb-4">Reçu de dépenses</h3>
                        <div class="mb-4 flex justify-center">
                            <img id="viewed-image" class="max-w-full max-h-96 object-contain border rounded-md hidden">
                            <iframe id="viewed-pdf" class="w-full h-96 border rounded-md hidden"></iframe>
                        </div>
                        <div class="flex justify-between">
                            <div class="flex space-x-2">
                                <button type="button" id="replace-file"
                                    class="px-4 py-2 bg-yellow-500 text-white rounded">
                                    <i class="fas fa-sync-alt mr-2"></i>Remplacer
                                </button>
                                <button type="button" id="delete-file" class="px-4 py-2 bg-red-600 text-white rounded">
                                    <i class="fas fa-trash mr-2"></i>Supprimer
                                </button>
                            </div>
                            <div class="flex space-x-2">
                                <button type="button" id="close-viewer"
                                    class="px-4 py-2 bg-gray-300 rounded">Fermer</button>
                                <button type="button" id="download-file"
                                    class="px-4 py-2 bg-green-600 text-white rounded">
                                    <i class="fas fa-download mr-2"></i>Télécharger
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md">
                    <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                        <h2 class="text-lg font-semibold text-blue-800">Taux de change
                            {{ $current_rate->month_year->format('F Y') }}</h2>
                        <div class="grid grid-cols-3 md:grid-cols-3 gap-4 mt-2">
                            <div class="flex items-center">
                                <label class="mr-2 text-gray-700 w-32">EUR → INR</label>
                                <input type="number" id="eurToInr" value="{{ $current_rate->eur_rate }}" readonly
                                    class="w-32 px-2 py-1 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="flex items-center">
                                <label class="mr-2 text-gray-700 w-32">USD → INR</label>
                                <input type="number" id="usdToInr" value="{{ $current_rate->usd_rate }}" readonly
                                    class="w-32 px-2 py-1 border border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                        <thead>
                            <tr>
                                <th scope="col" colspan="2"
                                    class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase bg-gray-50">
                                    Libelle des dépenses à prendre en charge</th>
                                <th scope="col"
                                    class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase bg-gray-50">
                                    A rembourser à l'agent</th>
                                <th scope="col"
                                    class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase bg-gray-50">
                                    Devise</th>
                                <th scope="col"
                                    class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase bg-gray-50">
                                    Prise en charge directe</th>
                                <th scope="col"
                                    class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase bg-gray-50">
                                    Devise</th>
                                <th scope="col"
                                    class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase bg-gray-50">
                                    Total INR</th>
                                <th scope="col"
                                    class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase bg-gray-50">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            <!-- Repas Row -->
                            <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100">
                                <td
                                    class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800">
                                    <span class="expense-badge bg-green-100 text-green-800">Repas</span>
                                </td>
                                <td
                                    class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                    <div class="flex flex-col items-center">
                                        <span class="text-xs text-gray-500 mb-1">Réduire le nombre</span>
                                        <div class="flex items-center justify-center">
                                            <span class="mr-2 font-medium">{{ $tournee->no_meals }}</span>
                                            <span class="mr-1">-</span>
                                            <input type="number" name="no_ded_meals"
                                                value="{{ $tournee->no_ded_meals }}" min="0"
                                                max="{{ $tournee->no_meals }}"
                                                class="reduced-meals-input w-16 px-2 py-1 border border-gray-300 rounded-md text-sm">
                                            <span class="ml-2 font-medium">= <span
                                                    class="actual-meals">{{ $tournee->no_meals }}</span></span>
                                        </div>
                                    </div>
                                    <input type="hidden" id="max-meals" value="{{ $tournee->no_meals }}">
                                    <input type="hidden" id="meal-cost"
                                        value="{{ $tournee->bareme->meal_cost }}">
                                </td>
                                <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800 reimbursement-amount"
                                    data-currency="INR"
                                    data-amount="{{ $tournee->no_meals * $tournee->bareme->meal_cost * $current_rate->eur_rate }}">
                                    {{ $tournee->no_meals * $tournee->bareme->meal_cost * $current_rate->eur_rate }}
                                </td>
                                <td
                                    class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                    INR
                                </td>
                                <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800 direct-amount"
                                    data-currency="INR" data-amount="0">--
                                </td>
                                <td
                                    class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                    --
                                </td>
                                <td
                                    class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800 total-inr">
                                    {{ $tournee->no_meals * $tournee->bareme->meal_cost * $current_rate->eur_rate }}
                                </td>
                            </tr>

                            <!-- Hébergement Row -->
                            <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100">
                                <td
                                    class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800">
                                    <span class="expense-badge bg-blue-100 text-blue-800">Hébergement</span>
                                    <input type="hidden" name="expenses[1][type]" value="accommodation">
                                </td>
                                <td
                                    class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                    <div class="flex flex-col items-center">
                                        <span class="text-xs text-gray-500 mb-1">Réduire le nombre</span>
                                        <div class="flex items-center justify-center">
                                            <span class="mr-2 font-medium">{{ $tournee->no_accomodation }}</span>
                                            <span class="mr-1">-</span>
                                            <input type="number" name="no_ded_accomodation"
                                                value="{{ $tournee->no_ded_accomodation }}" min="0"
                                                class="reduced-accommodation-input w-16 px-2 py-1 border border-gray-300 rounded-md text-sm">
                                            <span class="ml-2 font-medium">= <span
                                                    class="actual-accommodation">{{ $tournee->no_accomodation }}</span></span>
                                        </div>
                                    </div>
                                    <input type="hidden" id="max-accommodation"
                                        value="{{ $tournee->no_accomodation * $tournee->bareme->accomodation_cost * $current_rate->eur_rate }}">
                                    <input type="hidden" id="max-accommodation-eur"
                                        value="{{ $tournee->no_accomodation * $tournee->bareme->accomodation_cost }}">
                                    <input type="hidden" id="accommodation-cost"
                                        value="{{ $tournee->bareme->accomodation_cost }}">
                                    <input type="hidden" id="original-accommodation"
                                        value="{{ $tournee->no_accomodation }}">
                                </td>
                                <td
                                    class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                    <input type="number" name="expenses[1][reimbursement_amount]"
                                        value="{{ $tournee->acc_reimbursement_amount }}" step="0.01"
                                        min="0"
                                        class="reimbursement-input accommodation-input w-full px-2 py-1 border border-gray-300 rounded-md text-sm"
                                        data-currency="{{ $tournee->acc_reimbursement_currency ?? 'INR' }}"
                                        data-max="{{ $tournee->no_accomodation * $tournee->bareme->accomodation_cost * $current_rate->eur_rate }}">
                                </td>
                                <td
                                    class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                    <select name="expenses[1][reimbursement_currency]"
                                        class="reimbursement-currency accommodation-currency currency-select w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
                                        <option value="INR" @selected($tournee->acc_reimbursement_currency === 'INR')>INR</option>
                                        <option value="EUR" @selected($tournee->acc_reimbursement_currency === 'EUR')>EUR</option>
                                        <option value="USD" @selected($tournee->acc_reimbursement_currency === 'USD')>USD</option>
                                    </select>
                                </td>
                                <td
                                    class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                    <input type="number" name="expenses[1][direct_amount]"
                                        value="{{ $tournee->acc_direct_amount }}" step="0.01" min="0"
                                        class="direct-input accommodation-input w-full px-2 py-1 border border-gray-300 rounded-md text-sm"
                                        data-currency="{{ $tournee->acc_direct_currency ?? 'INR' }}"
                                        data-max="{{ $tournee->no_accomodation * $tournee->bareme->accomodation_cost * $current_rate->eur_rate }}">
                                </td>
                                <td
                                    class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                    <select name="expenses[1][direct_currency]"
                                        class="direct-currency accommodation-currency currency-select w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
                                        <option value="INR" @selected($tournee->acc_direct_currency === 'INR')>INR</option>
                                        <option value="EUR" @selected($tournee->acc_direct_currency === 'EUR')>EUR</option>
                                        <option value="USD" @selected($tournee->acc_direct_currency === 'USD')>USD</option>
                                    </select>
                                </td>
                                <td
                                    class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800 total-td">
                                    <input type="hidden" name="expenses[1][total_inr]"
                                        value="{{ $tournee->no_accomodation * $tournee->bareme->accomodation_cost * $current_rate->eur_rate }}">
                                    <span
                                        class="total-inr">{{ $tournee->no_accomodation * $tournee->bareme->accomodation_cost * $current_rate->eur_rate }}</span>
                                </td>
                                <td
                                    class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                    <div class="flex flex-col space-y-1">
                                        @if ($tournee->acc_expense_document)
                                            <button type="button"
                                                class="view-receipt-btn px-2 py-1 bg-green-100 text-green-600 rounded-md hover:bg-blue-200 text-xs"
                                                data-expense-type="accommodation" data-expense-id="1"
                                                data-expense-index="1" data-has-receipt="true"
                                                data-receipt-path="{{ $tournee->acc_expense_document }}">
                                                <i class="fas fa-receipt"></i>
                                            </button>
                                            <!-- Hidden field for existing receipt path -->
                                            <input type="hidden" name="expenses[1][existing_receipt]"
                                                value="{{ $tournee->acc_expense_document }}">
                                        @else
                                            <button type="button"
                                                class="view-receipt-btn px-2 py-1 bg-blue-100 text-blue-600 rounded-md hover:bg-blue-200 text-xs"
                                                data-expense-type="accommodation" data-expense-id="1"
                                                data-expense-index="1" data-has-receipt="false">
                                                <i class="fas fa-camera"></i>
                                            </button>
                                        @endif
                                        <!-- This will be dynamically added when a new file is uploaded -->
                                        <div class="file-input-container"></div>
                                    </div>
                                </td>
                            </tr>

                            <!-- Repas #2 Row -->
                            @php $index = 2; @endphp
                            @foreach ($tournee->expenses->where('type', 'extra_meal') as $expense)
                                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100">
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800">
                                        <span class="expense-badge bg-green-100 text-green-800">Repas
                                            #{{ $loop->iteration }}</span>
                                        <input type="hidden" name="expenses[{{ $index }}][type]"
                                            value="extra_meal">
                                        <input type="hidden" name="expenses[{{ $index }}][expense_id]"
                                            value="{{ $expense->id }}">
                                    </td>
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                        <input type="number" name="expenses[{{ $index }}][meal_participants]"
                                            value="{{ $expense->meal_participants ?? 0 }}" min="0"
                                            class="no-meals-input w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
                                    </td>
                                    <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800 reimbursement-amount"
                                        data-currency="INR"
                                        data-amount="{{ ($expense->meal_participants ?? 0) * $tournee->bareme->meal_cost * $current_rate->eur_rate }}">
                                        {{ ($expense->meal_participants ?? 0) * $tournee->bareme->meal_cost * $current_rate->eur_rate }}
                                    </td>
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                        INR
                                    </td>
                                    <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800 direct-amount"
                                        data-currency="INR" data-amount="0">
                                        --
                                    </td>
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                        --
                                    </td>
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800 total-td">
                                        <input type="hidden" name="expenses[{{ $index }}][total_inr]"
                                            value="{{ ($expense->meal_participants ?? 0) * $tournee->bareme->meal_cost * $current_rate->eur_rate }}">
                                        <span
                                            class="total-inr">{{ ($expense->meal_participants ?? 0) * $tournee->bareme->meal_cost * $current_rate->eur_rate }}</span>
                                    </td>
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                        <div class="flex flex-col space-y-1">
                                            @if ($expense->expense_document)
                                                <button type="button"
                                                    class="view-receipt-btn px-2 py-1 bg-green-100 text-green-600 rounded-md hover:bg-blue-200 text-xs"
                                                    data-expense-type="extra_meal"
                                                    data-expense-id="{{ $expense->id }}"
                                                    data-expense-index="{{ $index }}" data-has-receipt="true"
                                                    data-receipt-path="{{ $expense->expense_document }}">
                                                    <i class="fas fa-receipt"></i>
                                                </button>
                                                <!-- Hidden field for existing receipt path -->
                                                <input type="hidden"
                                                    name="expenses[{{ $index }}][existing_receipt]"
                                                    value="{{ $expense->expense_document }}">
                                            @else
                                                <button type="button"
                                                    class="view-receipt-btn px-2 py-1 bg-blue-100 text-blue-600 rounded-md hover:bg-blue-200 text-xs"
                                                    data-expense-type="extra_meal"
                                                    data-expense-id="{{ $expense->id }}"
                                                    data-expense-index="{{ $index }}"
                                                    data-has-receipt="false">
                                                    <i class="fas fa-camera"></i>
                                                </button>
                                            @endif
                                            <!-- This will be dynamically added when a new file is uploaded -->
                                            <div class="file-input-container"></div>
                                            <button type="button"
                                                class="delete-row px-2 py-1 bg-red-100 text-red-600 rounded-md hover:bg-red-200 text-xs">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <input type="hidden" name="expenses[{{ $index }}][reimbursement_amount]"
                                        value="0">
                                    <input type="hidden"
                                        name="expenses[{{ $index }}][reimbursement_currency]" value="INR">
                                    <input type="hidden" name="expenses[{{ $index }}][direct_amount]"
                                        value="0">
                                    <input type="hidden" name="expenses[{{ $index }}][direct_currency]"
                                        value="INR">
                                </tr>
                                @php $index++; @endphp
                            @endforeach

                            <!-- Transport Row -->
                            @foreach ($tournee->expenses->where('type', 'transport') as $expense)
                                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100">
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800">
                                        <span class="expense-badge bg-red-100 text-red-800">Transport</span>
                                        <input type="hidden" name="expenses[{{ $index }}][type]"
                                            value="transport">
                                        <input type="hidden" name="expenses[{{ $index }}][expense_id]"
                                            value="{{ $expense->id }}">
                                        <input type="hidden" name="expenses[{{ $index }}][transport_type]"
                                            value="{{ $expense->transport_type }}">
                                    </td>
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                        <input type="text" name="expenses[{{ $index }}][transport_type]"
                                            value="{{ __('expense.transport_types.' . $expense->transport_type) }}"
                                            class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm"
                                            disabled>
                                        <p class="text-xs">
                                            @if ($expense->transport_type === 'car_rental_with_driver')
                                                @php
                                                    $details = [];
                                                    if ($expense->passenger == 1) {
                                                        $details[] = __('passenger');
                                                    }
                                                    if ($expense->distance == 1) {
                                                        $details[] = __('distance');
                                                    }
                                                    if ($expense->material == 1) {
                                                        $details[] = __('material');
                                                    }
                                                    if ($expense->visits == 1) {
                                                        $details[] = __('visits');
                                                    }
                                                @endphp
                                                @if (!empty($details))
                                                    @foreach ($details as $detail)
                                                        &nbsp;&nbsp;{{ $detail }}@if (!$loop->last)
                                                            <br>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endif
                                        </p>
                                    </td>
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                        <input type="number"
                                            name="expenses[{{ $index }}][reimbursement_amount]"
                                            value="{{ $expense->reimbursement_amount ?? 0 }}" step="0.01"
                                            min="0"
                                            class="reimbursement-input w-full px-2 py-1 border border-gray-300 rounded-md text-sm"
                                            data-currency="INR">
                                    </td>
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                        <select name="expenses[{{ $index }}][reimbursement_currency]"
                                            class="reimbursement-currency currency-select w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
                                            <option value="INR" @selected($expense->reimbursement_currency === 'INR')>INR</option>
                                            <option value="EUR" @selected($expense->reimbursement_currency === 'EUR')>EUR</option>
                                            <option value="USD" @selected($expense->reimbursement_currency === 'USD')>USD</option>
                                        </select>
                                    </td>
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                        <input type="number" name="expenses[{{ $index }}][direct_amount]"
                                            value="{{ $expense->direct_amount }}" step="0.01" min="0"
                                            class="direct-input w-full px-2 py-1 border border-gray-300 rounded-md text-sm"
                                            data-currency="INR">
                                    </td>
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                        <select name="expenses[{{ $index }}][direct_currency]"
                                            class="direct-currency currency-select w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
                                            <option value="INR" @selected($expense->reimbursement_currency === 'INR')>INR</option>
                                            <option value="EUR" @selected($expense->reimbursement_currency === 'EUR')>EUR</option>
                                            <option value="USD" @selected($expense->reimbursement_currency === 'USD')>USD</option>
                                        </select>
                                    </td>
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800 total-td">
                                        <input type="hidden" name="expenses[{{ $index }}][total_inr]"
                                            value="0">
                                        <span class="total-inr">{{ $expense->total_inr }}</span>
                                    </td>
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                        <div class="flex flex-col space-y-1">
                                            @if ($expense->expense_document)
                                                <button type="button"
                                                    class="view-receipt-btn px-2 py-1 bg-green-100 text-green-600 rounded-md hover:bg-blue-200 text-xs"
                                                    data-expense-type="transport"
                                                    data-expense-id="{{ $expense->id }}"
                                                    data-expense-index="{{ $index }}" data-has-receipt="true"
                                                    data-receipt-path="{{ $expense->expense_document }}">
                                                    <i class="fas fa-receipt"></i>
                                                </button>
                                                <!-- Hidden field for existing receipt path -->
                                                <input type="hidden"
                                                    name="expenses[{{ $index }}][existing_receipt]"
                                                    value="{{ $expense->expense_document }}">
                                            @else
                                                <button type="button"
                                                    class="view-receipt-btn px-2 py-1 bg-blue-100 text-blue-600 rounded-md hover:bg-blue-200 text-xs"
                                                    data-expense-type="transport"
                                                    data-expense-id="{{ $expense->id }}"
                                                    data-expense-index="{{ $index }}"
                                                    data-has-receipt="false">
                                                    <i class="fas fa-camera"></i>
                                                </button>
                                            @endif
                                            <!-- This will be dynamically added when a new file is uploaded -->
                                            <div class="file-input-container"></div>
                                            <button type="button"
                                                class="delete-row px-2 py-1 bg-red-100 text-red-600 rounded-md hover:bg-red-200 text-xs">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @php $index++; @endphp
                            @endforeach

                            <!-- Other Expenses Header -->
                            <tr>
                                <td colspan="8" class="px-3 py-2 bg-blue-800 text-center font-bold text-white">
                                    AUTRES DEPENSES</td>
                            </tr>

                            <!-- Other Expenses -->
                            @foreach ($tournee->expenses->whereIn('type', ['visa', 'Receptions', 'other']) as $expense)
                                <tr
                                    class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 {{ $loop->last ? 'last-row' : '' }}">
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800">
                                        <span class="expense-badge bg-purple-100 text-purple-800">
                                            @switch($expense->type)
                                                @case('visa')
                                                    Frais de visa
                                                @break

                                                @case('Receptions')
                                                    Frais d'Receptions
                                                @break

                                                @case('other')
                                                    Autre
                                                @break
                                            @endswitch
                                        </span>
                                        <input type="hidden" name="expenses[{{ $index }}][type]"
                                            value="{{ $expense->type }}">
                                        <input type="hidden" name="expenses[{{ $index }}][expense_id]"
                                            value="{{ $expense->id }}">
                                    </td>
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                        @switch($expense->type)
                                            @case('visa')
                                            @case('Receptions')
                                                <input type="text" name="expenses[{{ $index }}][meal_location]"
                                                    value="{{ $expense->meal_location }}"
                                                    class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
                                            @break

                                            @case('other')
                                                <input type="text" name="expenses[{{ $index }}][description]"
                                                    value="{{ $expense->description }}"
                                                    class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
                                            @break
                                        @endswitch
                                    </td>
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                        <input type="number"
                                            name="expenses[{{ $index }}][reimbursement_amount]"
                                            value="{{ $expense->reimbursement_amount ?? 0 }}" step="0.01"
                                            min="0"
                                            class="reimbursement-input w-full px-2 py-1 border border-gray-300 rounded-md text-sm"
                                            data-currency="INR">
                                    </td>
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                        <select name="expenses[{{ $index }}][reimbursement_currency]"
                                            class="reimbursement-currency currency-select w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
                                            <option value="INR" @selected($expense->reimbursement_currency === 'INR')>INR</option>
                                            <option value="EUR" @selected($expense->reimbursement_currency === 'EUR')>EUR</option>
                                            <option value="USD" @selected($expense->reimbursement_currency === 'USD')>USD</option>
                                        </select>
                                    </td>
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                        <input type="number" name="expenses[{{ $index }}][direct_amount]"
                                            value="{{ $expense->direct_amount }}" step="0.01" min="0"
                                            class="direct-input w-full px-2 py-1 border border-gray-300 rounded-md text-sm"
                                            data-currency="INR">
                                    </td>
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                        <select name="expenses[{{ $index }}][direct_currency]"
                                            class="direct-currency currency-select w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
                                            <option value="INR" @selected($expense->reimbursement_currency === 'INR')>INR</option>
                                            <option value="EUR" @selected($expense->reimbursement_currency === 'EUR')>EUR</option>
                                            <option value="USD" @selected($expense->reimbursement_currency === 'USD')>USD</option>
                                        </select>
                                    </td>
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800 total-td">
                                        <input type="hidden" name="expenses[{{ $index }}][total_inr]"
                                            value="0">
                                        <span class="total-inr">{{ $expense->total_inr }}</span>
                                    </td>
                                    <td
                                        class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                        <div class="flex flex-col space-y-1">
                                            @if ($expense->expense_document)
                                                <button type="button"
                                                    class="view-receipt-btn px-2 py-1 bg-green-100 text-green-600 rounded-md hover:bg-blue-200 text-xs"
                                                    data-expense-type="{{ $expense->type }}"
                                                    data-expense-id="{{ $expense->id }}"
                                                    data-expense-index="{{ $index }}" data-has-receipt="true"
                                                    data-receipt-path="{{ $expense->expense_document }}">
                                                    <i class="fas fa-receipt"></i>
                                                </button>
                                                <!-- Hidden field for existing receipt path -->
                                                <input type="hidden"
                                                    name="expenses[{{ $index }}][existing_receipt]"
                                                    value="{{ $expense->expense_document }}">
                                            @else
                                                <button type="button"
                                                    class="view-receipt-btn px-2 py-1 bg-blue-100 text-blue-600 rounded-md hover:bg-blue-200 text-xs"
                                                    data-expense-type="{{ $expense->type }}"
                                                    data-expense-id="{{ $expense->id }}"
                                                    data-expense-index="{{ $index }}"
                                                    data-has-receipt="false">
                                                    <i class="fas fa-camera"></i>
                                                </button>
                                            @endif
                                            <!-- This will be dynamically added when a new file is uploaded -->
                                            <div class="file-input-container"></div>
                                            <button type="button"
                                                class="delete-row px-2 py-1 bg-red-100 text-red-600 rounded-md hover:bg-red-200 text-xs">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @php $index++; @endphp
                            @endforeach

                            <!-- New Expense Template (Hidden) -->
                            {{-- <tr id="new-expense-template" class="hidden odd:bg-white even:bg-gray-50 hover:bg-gray-100">
                                <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800">
                                    <span class="expense-badge bg-purple-100 text-purple-800">Nouvelle Dépense</span>
                                    <input type="hidden" name="expenses[INDEX][type]" value="other">
                                </td>
                                <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                    <input type="text" name="expenses[INDEX][description]" value="" placeholder="Description" class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
                                </td>
                                <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                    <input type="number" name="expenses[INDEX][reimbursement_amount]" value="0" step="0.01" min="0"
                                        class="reimbursement-input w-full px-2 py-1 border border-gray-300 rounded-md text-sm" data-currency="INR">
                                </td>
                                <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                    <select name="expenses[INDEX][reimbursement_currency]" class="reimbursement-currency currency-select w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
                                        <option value="INR">INR</option>
                                        <option value="EUR">EUR</option>
                                        <option value="USD">USD</option>
                                    </select>
                                </td>
                                <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                    <input type="number" name="expenses[INDEX][direct_amount]" value="0" step="0.01" min="0"
                                        class="direct-input w-full px-2 py-1 border border-gray-300 rounded-md text-sm" data-currency="INR">
                                </td>
                                <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                    <select name="expenses[INDEX][direct_currency]" class="direct-currency currency-select w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
                                        <option value="INR">INR</option>
                                        <option value="EUR">EUR</option>
                                        <option value="USD">USD</option>
                                    </select>
                                </td>
                                <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800 total-td">
                                    <input type="hidden" name="expenses[INDEX][total_inr]" value="0">
                                    <span class="total-inr">0.00</span>
                                </td>
                                <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                    <div class="flex flex-col space-y-1">
                                        <button type="button" class="view-receipt-btn px-2 py-1 bg-blue-100 text-blue-600 rounded-md hover:bg-blue-200 text-xs"
                                            data-expense-type="other" data-expense-id="INDEX" data-expense-index="INDEX" data-has-receipt="false">
                                            <i class="fas fa-camera"></i>
                                        </button>
                                        <!-- This will be dynamically added when a new file is uploaded -->
                                        <div class="file-input-container"></div>
                                        <button type="button" class="delete-row px-2 py-1 bg-red-100 text-red-600 rounded-md hover:bg-red-200 text-xs">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr> --}}

                            <!-- Total Row -->
                            <tr class="bg-gray-200 font-bold">
                                {{-- <td class="px-3 py-3 text-right border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                    <!-- Add New Expense Button -->
                                    <div class="mb-4 flex justify-end">
                                        <button type="button" id="add-expense-btn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                            <i class="fas fa-plus mr-2"></i>Ajouter une dépense
                                        </button>
                                    </div>
                                </td> --}}
                                <td colspan="2"
                                    class="px-3 py-3 text-right border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                    SUBTOTALS (INR)
                                </td>
                                <td id="reimbursement-total"
                                    class="px-3 py-3 text-center border border-gray-200 whitespace-nowrap text-sm text-blue-800">
                                    <input type="hidden" id="reimbursement-total-input" name="totals[reimbursement]"
                                        value="0">
                                    0.00
                                </td>
                                <td
                                    class="px-3 py-3 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                </td>
                                <td id="direct-total"
                                    class="px-3 py-3 text-center border border-gray-200 whitespace-nowrap text-sm text-blue-800">
                                    <input type="hidden" id="direct-total-input" name="totals[direct]"
                                        value="0">
                                    0.00
                                </td>
                                <td
                                    class="px-3 py-3 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                </td>
                                <td id="grand-total"
                                    class="px-3 py-3 text-center border border-gray-200 whitespace-nowrap text-lg text-blue-800">
                                    <input type="hidden" id="grand-total-input" name="totals[grand_total]"
                                        value="0">
                                    0.00
                                </td>
                                <td
                                    class="px-3 py-3 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                                </td>
                                <input type="hidden" id="reimbursement-total-input" name="totals[reimbursement]"
                                    value="0">
                                <input type="hidden" id="direct-total-input" name="totals[direct]" value="0">
                                <input type="hidden" id="grand-total-input" name="totals[grand_total]"
                                    value="0">
                            </tr>
                        </tbody>
                    </table>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const eurToInrInput = document.getElementById('eurToInr');
                        const usdToInrInput = document.getElementById('usdToInr');
                        const grandTotalElement = document.getElementById('grand-total');
                        const reimbursementTotalElement = document.getElementById('reimbursement-total');
                        const directTotalElement = document.getElementById('direct-total');
                        //const addExpenseBtn = document.getElementById('add-expense-btn');
                        const otherExpensesHeader = document.querySelector('.last-row');
                        //const newExpenseTemplate = document.getElementById('new-expense-template');

                        // File Upload Modal Elements
                        const photoModal = document.getElementById('photo-upload-modal');
                        const filePreviewContainer = document.getElementById('file-preview-container');
                        const imagePreview = document.getElementById('image-preview');
                        const pdfPreview = document.getElementById('pdf-preview');
                        const expenseReceiptInput = document.getElementById('expense-receipt');
                        const fileName = document.getElementById('file-name');
                        const cancelUploadBtn = document.getElementById('cancel-upload');
                        const confirmUploadBtn = document.getElementById('confirm-upload');

                        // File View Modal Elements
                        const fileViewModal = document.getElementById('file-view-modal');
                        const viewedImage = document.getElementById('viewed-image');
                        const viewedPdf = document.getElementById('viewed-pdf');
                        const replaceFileBtn = document.getElementById('replace-file');
                        const deleteFileBtn = document.getElementById('delete-file');
                        const closeViewerBtn = document.getElementById('close-viewer');
                        const downloadFileBtn = document.getElementById('download-file');

                        // Buttons
                        const viewReceiptBtns = document.querySelectorAll('.view-receipt-btn');

                        let currentExpenseType = null;
                        let currentExpenseId = null;
                        let currentExpenseIndex = null;
                        let currentFile = null;

                        // Update the file viewer to handle existing receipts
                        viewReceiptBtns.forEach(btn => {
                            btn.addEventListener('click', function() {
                                const hasReceipt = this.getAttribute('data-has-receipt') === 'true';

                                if (hasReceipt) {
                                    // Show the file viewer modal
                                    currentExpenseType = this.getAttribute('data-expense-type');
                                    currentExpenseId = this.getAttribute('data-expense-id');
                                    currentExpenseIndex = this.getAttribute('data-expense-index');
                                    const receiptPath = this.getAttribute('data-receipt-path');

                                    // Show/hide delete button based on whether it's a new upload or existing file
                                    if (receiptPath === 'new-upload') {
                                        deleteFileBtn.classList.remove('hidden');
                                    } else {
                                        deleteFileBtn.classList.remove('hidden');
                                    }

                                    if (receiptPath === 'new-upload') {
                                        // This is a newly uploaded file, get it from the file input
                                        const fileInput = document.querySelector(
                                            `input[name="expenses[${currentExpenseIndex}][receipt]"]`);
                                        if (fileInput && fileInput.files && fileInput.files[0]) {
                                            const file = fileInput.files[0];
                                            displayFileInViewer(file);
                                            fileViewModal.classList.remove('hidden');
                                        }
                                    } else {
                                        // This is an existing file from the server
                                        // Check if it's a PDF or image
                                        if (receiptPath.toLowerCase().endsWith('.pdf')) {
                                            viewedPdf.src = '/storage/' + receiptPath;
                                            viewedPdf.classList.remove('hidden');
                                            viewedImage.classList.add('hidden');
                                        } else {
                                            viewedImage.src = '/storage/' + receiptPath;
                                            viewedImage.classList.remove('hidden');
                                            viewedPdf.classList.add('hidden');
                                        }
                                        fileViewModal.classList.remove('hidden');
                                    }
                                } else {
                                    // Show the upload modal
                                    currentExpenseType = this.getAttribute('data-expense-type');
                                    currentExpenseId = this.getAttribute('data-expense-id');
                                    currentExpenseIndex = this.getAttribute('data-expense-index');

                                    photoModal.classList.remove('hidden');
                                }
                            });
                        });
                        // File input change handler
                        expenseReceiptInput.addEventListener('change', function() {
                            const file = this.files[0];
                            if (file) {
                                currentFile = file;
                                fileName.textContent = file.name;

                                // Show preview based on file type
                                displayFilePreview(file);
                            }
                        });

                        // Function to display file preview
                        function displayFilePreview(file) {
                            const reader = new FileReader();

                            if (file.type === 'application/pdf') {
                                // Handle PDF preview
                                reader.onload = function(e) {
                                    pdfPreview.src = e.target.result;
                                    pdfPreview.classList.remove('hidden');
                                    imagePreview.classList.add('hidden');
                                    filePreviewContainer.classList.remove('hidden');
                                };
                                reader.readAsDataURL(file);
                            } else if (file.type.startsWith('image/')) {
                                // Handle image preview
                                reader.onload = function(e) {
                                    imagePreview.src = e.target.result;
                                    imagePreview.classList.remove('hidden');
                                    pdfPreview.classList.add('hidden');
                                    filePreviewContainer.classList.remove('hidden');
                                };
                                reader.readAsDataURL(file);
                            } else {
                                // Unsupported file type
                                alert('Please select an image or PDF file');
                                this.value = '';
                                filePreviewContainer.classList.add('hidden');
                                fileName.textContent = '';
                                currentFile = null;
                            }
                        }

                        // Function to display file in viewer
                        function displayFileInViewer(file) {
                            if (file.type === 'application/pdf') {
                                viewedPdf.src = URL.createObjectURL(file);
                                viewedPdf.classList.remove('hidden');
                                viewedImage.classList.add('hidden');
                            } else {
                                viewedImage.src = URL.createObjectURL(file);
                                viewedImage.classList.remove('hidden');
                                viewedPdf.classList.add('hidden');
                            }
                        }

                        // Confirm upload button handler
                        confirmUploadBtn.addEventListener('click', function() {
                            if (!currentFile) {
                                alert('Please select a file to upload');
                                return;
                            }

                            // Remove any existing file input for this expense
                            const existingFileInput = document.querySelector(
                                `input[name="expenses[${currentExpenseIndex}][receipt]"]`);
                            if (existingFileInput) {
                                existingFileInput.remove();
                            }

                            // Create a new file input
                            const fileInput = document.createElement('input');
                            fileInput.type = 'file';
                            fileInput.name = `expenses[${currentExpenseIndex}][receipt]`;
                            fileInput.hidden = true;

                            // Create a DataTransfer object to hold the file
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(currentFile);
                            fileInput.files = dataTransfer.files;

                            // Add the file input to the container
                            const container = document.querySelector(
                                    `.view-receipt-btn[data-expense-index="${currentExpenseIndex}"]`)
                                .closest('td')
                                .querySelector('.file-input-container');
                            container.appendChild(fileInput);

                            // Remove any existing receipt hidden field
                            const existingReceiptInput = document.querySelector(
                                `input[name="expenses[${currentExpenseIndex}][existing_receipt]"]`);
                            if (existingReceiptInput) {
                                existingReceiptInput.remove();
                            }

                            // Update the button to show it has a receipt
                            const viewBtn = document.querySelector(
                                `.view-receipt-btn[data-expense-index="${currentExpenseIndex}"]`);
                            viewBtn.setAttribute('data-has-receipt', 'true');
                            viewBtn.setAttribute('data-receipt-path', 'new-upload');
                            viewBtn.classList.remove('bg-blue-100', 'text-blue-600');
                            viewBtn.classList.add('bg-green-100', 'text-green-600');
                            viewBtn.innerHTML = '<i class="fas fa-receipt"></i>';

                            // Show the uploaded file in the viewer
                            displayFileInViewer(currentFile);

                            // Show delete button for new uploads
                            deleteFileBtn.classList.remove('hidden');

                            // Close upload modal and open viewer
                            photoModal.classList.add('hidden');
                            fileViewModal.classList.remove('hidden');

                            // Reset upload modal
                            expenseReceiptInput.value = '';
                            filePreviewContainer.classList.add('hidden');
                            fileName.textContent = '';
                            currentFile = null;
                        });
                        // Cancel upload button
                        cancelUploadBtn.addEventListener('click', function() {
                            photoModal.classList.add('hidden');
                            expenseReceiptInput.value = '';
                            filePreviewContainer.classList.add('hidden');
                            fileName.textContent = '';
                            currentFile = null;
                        });

                        // Replace file button
                        replaceFileBtn.addEventListener('click', function() {
                            fileViewModal.classList.add('hidden');
                            photoModal.classList.remove('hidden');
                            // Ensure delete button is visible when replacing
                            deleteFileBtn.classList.remove('hidden');
                        });

                        // Delete file button handler
                        deleteFileBtn.addEventListener('click', function() {
                            if (confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?')) {
                                // Remove the file input if it exists (for new uploads)
                                const fileInput = document.querySelector(
                                    `input[name="expenses[${currentExpenseIndex}][receipt]"]`);
                                if (fileInput) {
                                    fileInput.remove();
                                }

                                // Remove the existing receipt hidden field
                                const existingReceiptInput = document.querySelector(
                                    `input[name="expenses[${currentExpenseIndex}][existing_receipt]"]`);
                                if (existingReceiptInput) {
                                    existingReceiptInput.remove();
                                }

                                // Update the button to show no receipt
                                const viewBtn = document.querySelector(
                                    `.view-receipt-btn[data-expense-index="${currentExpenseIndex}"]`);
                                viewBtn.setAttribute('data-has-receipt', 'false');
                                viewBtn.removeAttribute('data-receipt-path');
                                viewBtn.classList.remove('bg-green-100', 'text-green-600');
                                viewBtn.classList.add('bg-blue-100', 'text-blue-600');
                                viewBtn.innerHTML = '<i class="fas fa-camera"></i>';

                                // Close the modal
                                fileViewModal.classList.add('hidden');

                                // Show success message
                                alert('Fichier supprimé avec succès.');
                            }
                        });

                        // Close viewer button
                        closeViewerBtn.addEventListener('click', function() {
                            fileViewModal.classList.add('hidden');
                        });

                        // Download file button
                        downloadFileBtn.addEventListener('click', function() {
                            let downloadUrl;
                            let fileName;

                            if (viewedImage.classList.contains('hidden') && !viewedPdf.classList.contains('hidden')) {
                                // PDF is being viewed
                                downloadUrl = viewedPdf.src;
                                fileName = `expense-receipt-${currentExpenseType}-${currentExpenseId}.pdf`;
                            } else {
                                // Image is being viewed
                                downloadUrl = viewedImage.src;
                                fileName = `expense-receipt-${currentExpenseType}-${currentExpenseId}.jpg`;
                            }

                            if (downloadUrl) {
                                const link = document.createElement('a');
                                link.href = downloadUrl;
                                link.download = fileName;
                                document.body.appendChild(link);
                                link.click();
                                document.body.removeChild(link);
                            }
                        });

                        // Close modals when clicked outside
                        window.addEventListener('click', function(event) {
                            if (event.target === photoModal) {
                                photoModal.classList.add('hidden');
                                expenseReceiptInput.value = '';
                                filePreviewContainer.classList.add('hidden');
                                fileName.textContent = '';
                                currentFile = null;
                            }
                            if (event.target === fileViewModal) {
                                fileViewModal.classList.add('hidden');
                            }
                        });

                        // Track the current index for new expenses
                        let currentIndex = 10; // Starting index

                        let exchangeRates = {
                            EUR: parseFloat(eurToInrInput.value),
                            USD: parseFloat(usdToInrInput.value)
                        };

                        // Add event listeners to all input and select elements for dynamic calculation
                        document.querySelectorAll(
                            '.reimbursement-input, .direct-input, .reimbursement-currency, .direct-currency').forEach(
                            element => {
                                element.addEventListener('input', updateRowCalculation);
                                element.addEventListener('change', updateRowCalculation);
                            });

                        // Add delete row functionality
                        document.querySelectorAll('.delete-row').forEach(button => {
                            button.addEventListener('click', function() {
                                const row = this.closest('tr');
                                if (row && confirm('Are you sure you want to delete this expense?')) {
                                    row.remove();
                                    updateAllTotals();
                                }
                            });
                        });

                        // Add event listener for meal quantity changes
                        document.querySelectorAll('.no-meals-input').forEach(input => {
                            input.addEventListener('input', function() {
                                const row = this.closest('tr');
                                const noMeals = parseInt(this.value) || 0;
                                const mealCost = {{ $tournee->bareme->meal_cost }};
                                const eurRate = parseFloat(eurToInrInput.value);

                                // Calculate reimbursement amount
                                const reimbursementAmount = noMeals * mealCost * eurRate;

                                // Update reimbursement amount cell
                                const reimbursementCell = row.querySelector('.reimbursement-amount');
                                reimbursementCell.textContent = reimbursementAmount.toFixed(2);
                                reimbursementCell.setAttribute('data-amount', reimbursementAmount);

                                // Update total INR
                                const totalCell = row.querySelector('.total-inr');
                                const totalInput = row.querySelector('input[name$="[total_inr]"]');
                                totalCell.textContent = reimbursementAmount.toFixed(2);
                                totalInput.value = reimbursementAmount.toFixed(2);

                                const reimbursement_amount = row.querySelector(
                                    'input[name$="[reimbursement_amount]"]');
                                reimbursement_amount.value = reimbursementAmount.toFixed(2);

                                // Update all totals
                                updateAllTotals();
                            });
                        });

                        // Add new expense functionality
                        // addExpenseBtn.addEventListener('click', function() {
                        //     // Clone the template
                        //     const newRow = newExpenseTemplate.cloneNode(true);
                        //     newRow.classList.remove('hidden');
                        //     newRow.removeAttribute('id');

                        //     // Update all the INDEX placeholders with the current index
                        //     newRow.querySelectorAll('input, select').forEach(element => {
                        //         if (element.name) {
                        //             element.name = element.name.replace('INDEX', currentIndex);
                        //         } else {
                        //             element.setAttribute('data-expense-id', currentIndex);
                        //             element.setAttribute('data-expense-index', currentIndex);
                        //         }
                        //     });

                        //     // Add event listeners to the new inputs
                        //     newRow.querySelectorAll(
                        //             '.reimbursement-input, .direct-input, .reimbursement-currency, .direct-currency')
                        //         .forEach(
                        //             element => {
                        //                 element.addEventListener('input', updateRowCalculation);
                        //                 element.addEventListener('change', updateRowCalculation);
                        //             }
                        //         );

                        //     // Add delete functionality to the new row
                        //     newRow.querySelector('.delete-row').addEventListener('click', function() {
                        //         if (confirm('Are you sure you want to delete this expense?')) {
                        //             newRow.remove();
                        //             updateAllTotals();
                        //         }
                        //     });

                        //     // Add receipt upload functionality to the new row
                        //     newRow.querySelector('.view-receipt-btn').addEventListener('click', function() {
                        //         currentExpenseType = this.getAttribute('data-expense-type');
                        //         currentExpenseId = this.getAttribute('data-expense-id');
                        //         currentExpenseIndex = this.getAttribute('data-expense-index');
                        //         photoModal.classList.remove('hidden');
                        //     });

                        //     // Insert the new row before the total row
                        //     otherExpensesHeader.insertAdjacentElement('afterend', newRow);
                        //     // Increment the index for the next new row
                        //     currentIndex++;

                        //     // Update totals
                        //     updateAllTotals();
                        // });

                        // Function to convert amount from one currency to INR
                        function convertToINR(amount, fromCurrency) {
                            if (fromCurrency === 'INR') return amount;
                            return amount * exchangeRates[fromCurrency];
                        }

                        // Function to update calculation for a single row
                        function updateRowCalculation() {
                            const row = this.closest('tr');
                            if (!row) return;

                            const reimbursementInput = row.querySelector('.reimbursement-input');
                            const reimbursementCurrency = row.querySelector('.reimbursement-currency');
                            const directInput = row.querySelector('.direct-input');
                            const directCurrency = row.querySelector('.direct-currency');
                            const totalTD = row.querySelector('.total-td');
                            const totalElement = row.querySelector('.total-inr');
                            const totalInput = totalTD.querySelector('input[type="hidden"]');

                            if (!reimbursementInput || !reimbursementCurrency || !directInput || !directCurrency || !
                                totalElement) return;

                            let reimbursementValue = parseFloat(reimbursementInput.value) || 0;
                            let directValue = parseFloat(directInput.value) || 0;

                            // Update data attributes for currency
                            reimbursementInput.setAttribute('data-currency', reimbursementCurrency.value);
                            directInput.setAttribute('data-currency', directCurrency.value);

                            // Convert values to INR
                            const reimbursementINR = convertToINR(reimbursementValue, reimbursementCurrency.value);
                            const directINR = convertToINR(directValue, directCurrency.value);

                            const totalINR = reimbursementINR + directINR;
                            totalElement.textContent = totalINR.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            if (totalInput) totalInput.value = totalINR.toFixed(2);
                            updateAllTotals();
                        }

                        // Function to update all calculations
                        function updateAllCalculations() {
                            document.querySelectorAll('.reimbursement-input').forEach(input => {
                                input.dispatchEvent(new Event('input'));
                            });
                        }

                        // Function to update all totals (reimbursement, direct, and grand total)
                        function updateAllTotals() {
                            let reimbursementTotal = 0;
                            let directTotal = 0;
                            let grandTotal = 0;

                            // Calculate reimbursement total
                            document.querySelectorAll('.reimbursement-input').forEach(input => {
                                const value = parseFloat(input.value) || 0;
                                const currency = input.getAttribute('data-currency');
                                reimbursementTotal += convertToINR(value, currency);
                            });

                            // Add fixed reimbursement amounts
                            document.querySelectorAll('.reimbursement-amount').forEach(element => {
                                const value = parseFloat(element.getAttribute('data-amount')) || 0;
                                const currency = element.getAttribute('data-currency');
                                reimbursementTotal += convertToINR(value, currency);
                            });

                            // Calculate direct payment total
                            document.querySelectorAll('.direct-input').forEach(input => {
                                const value = parseFloat(input.value) || 0;
                                const currency = input.getAttribute('data-currency');
                                directTotal += convertToINR(value, currency);
                            });

                            // Add fixed direct amounts
                            document.querySelectorAll('.direct-amount').forEach(element => {
                                const value = parseFloat(element.getAttribute('data-amount')) || 0;
                                const currency = element.getAttribute('data-currency');
                                directTotal += convertToINR(value, currency);
                            });

                            // Calculate grand total
                            document.querySelectorAll('.total-inr').forEach(element => {
                                // Skip the grand total cell itself
                                if (element.id !== 'grand-total') {
                                    const value = parseFloat(element.textContent.replace(/,/g, '')) || 0;
                                    grandTotal += value;
                                }
                            });

                            // Update the total elements
                            reimbursementTotalElement.textContent = reimbursementTotal.toFixed(2).replace(
                                /\B(?=(\d{3})+(?!\d))/g, ",");
                            directTotalElement.textContent = directTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            grandTotalElement.textContent = grandTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                            // Update the hidden input fields for form submission
                            document.getElementById('reimbursement-total-input').value = reimbursementTotal.toFixed(2);
                            document.getElementById('direct-total-input').value = directTotal.toFixed(2);
                            document.getElementById('grand-total-input').value = grandTotal.toFixed(2);
                        }

                        // Function to validate accommodation amounts with currency conversion
                        function validateAccommodationAmounts() {
                            const accommodationInputs = document.querySelectorAll('.accommodation-input');
                            const accommodationCurrencies = document.querySelectorAll('.accommodation-currency');
                            const accommodationReductionInput = document.querySelector('.reduced-accommodation-input');

                            // Function to validate the accommodation total
                            function validateAccommodationTotal() {
                                const reimbursementInput = document.querySelector(
                                    'input[name="expenses[1][reimbursement_amount]"]');
                                const directInput = document.querySelector('input[name="expenses[1][direct_amount]"]');
                                const reimbursementCurrency = document.querySelector(
                                    'select[name="expenses[1][reimbursement_currency]"]').value;
                                const directCurrency = document.querySelector('select[name="expenses[1][direct_currency]"]')
                                    .value;

                                // Get the CURRENT maximum values (after reduction)
                                const maxAmountINR = parseFloat(document.getElementById('max-accommodation').value);
                                const maxAmountEUR = parseFloat(document.getElementById('max-accommodation-eur').value);

                                const reimbursementValue = parseFloat(reimbursementInput.value) || 0;
                                const directValue = parseFloat(directInput.value) || 0;

                                // Convert both amounts to INR for comparison with the total
                                const reimbursementINR = convertToINR(reimbursementValue, reimbursementCurrency);
                                const directINR = convertToINR(directValue, directCurrency);
                                const totalINR = reimbursementINR + directINR;

                                if (totalINR > maxAmountINR) {
                                    // Calculate how much to reduce from the current input
                                    const excess = totalINR - maxAmountINR;

                                    // Determine which input was just changed
                                    const activeElement = document.activeElement;
                                    if (activeElement === reimbursementInput) {
                                        // Reduce reimbursement amount
                                        const reductionINR = excess;
                                        const reductionOriginal = reimbursementCurrency === 'INR' ?
                                            reductionINR :
                                            reductionINR / exchangeRates[reimbursementCurrency];

                                        const newValue = Math.max(0, reimbursementValue - reductionOriginal);
                                        reimbursementInput.value = newValue.toFixed(2);
                                    } else if (activeElement === directInput) {
                                        // Reduce direct amount
                                        const reductionINR = excess;
                                        const reductionOriginal = directCurrency === 'INR' ?
                                            reductionINR :
                                            reductionINR / exchangeRates[directCurrency];

                                        const newValue = Math.max(0, directValue - reductionOriginal);
                                        directInput.value = newValue.toFixed(2);
                                    } else {
                                        // If neither input is focused (e.g., currency changed or reduction changed), reduce both proportionally
                                        const reimbursementRatio = reimbursementINR / totalINR;
                                        const directRatio = directINR / totalINR;

                                        const reimbursementReduction = reimbursementCurrency === 'INR' ?
                                            excess * reimbursementRatio :
                                            (excess * reimbursementRatio) / exchangeRates[reimbursementCurrency];

                                        const directReduction = directCurrency === 'INR' ?
                                            excess * directRatio :
                                            (excess * directRatio) / exchangeRates[directCurrency];

                                        reimbursementInput.value = Math.max(0, reimbursementValue - reimbursementReduction)
                                            .toFixed(2);
                                        directInput.value = Math.max(0, directValue - directReduction).toFixed(2);
                                    }

                                    // Show a warning
                                    alert(
                                        `Le total des montants ne peut pas dépasser ${maxAmountEUR.toFixed(2)} EUR (${maxAmountINR.toFixed(2)} INR).`);

                                    // Update the row calculation
                                    updateRowCalculation.call(reimbursementInput);
                                }
                            }

                            // Add event listeners to all accommodation-related inputs
                            accommodationInputs.forEach(input => {
                                input.addEventListener('input', validateAccommodationTotal);
                            });

                            accommodationCurrencies.forEach(select => {
                                select.addEventListener('change', function() {
                                    // Update the data-currency attribute on the corresponding input
                                    const inputName = this.name.replace('_currency', '_amount');
                                    const correspondingInput = document.querySelector(
                                        `input[name="${inputName}"]`);
                                    if (correspondingInput) {
                                        correspondingInput.setAttribute('data-currency', this.value);
                                    }
                                    validateAccommodationTotal();
                                });
                            });

                            // Also validate when reduction input changes
                            if (accommodationReductionInput) {
                                accommodationReductionInput.addEventListener('input', validateAccommodationTotal);
                            }

                            // Return the validation function so it can be called from other places
                            return validateAccommodationTotal;
                        }

                        // Function to handle accommodation quantity changes
                        function handleAccommodationQuantityChanges() {
                            const accommodationInput = document.querySelector('.reduced-accommodation-input');
                            const accommodationCost = parseFloat(document.getElementById('accommodation-cost').value);
                            const originalAccommodation = parseInt(document.getElementById('original-accommodation').value);
                            const actualAccommodationElement = document.querySelector('.actual-accommodation');
                            const eurRate = parseFloat(eurToInrInput.value);

                            // Calculate initial values from database
                            const initialReducedAccommodation = parseInt(accommodationInput.value) || 0;
                            const initialActualAccommodation = Math.max(0, originalAccommodation - initialReducedAccommodation);

                            // Update the actual accommodation display
                            actualAccommodationElement.textContent = initialActualAccommodation;

                            // Update the maximum values for the accommodation inputs
                            const initialMaxAmountINR = initialActualAccommodation * accommodationCost * eurRate;
                            const initialMaxAmountEUR = initialActualAccommodation * accommodationCost;

                            document.getElementById('max-accommodation').value = initialMaxAmountINR;
                            document.getElementById('max-accommodation-eur').value = initialMaxAmountEUR;

                            // Update the max attributes on the input fields
                            const reimbursementInput = document.querySelector(
                            'input[name="expenses[1][reimbursement_amount]"]');
                            const directInput = document.querySelector('input[name="expenses[1][direct_amount]"]');

                            if (reimbursementInput) reimbursementInput.setAttribute('data-max', initialMaxAmountINR);
                            if (directInput) directInput.setAttribute('data-max', initialMaxAmountINR);

                            // Add event listener for input changes
                            accommodationInput.addEventListener('input', function() {
                                const reducedAccommodation = parseInt(this.value) || 0;

                                // Calculate actual number of accommodation nights (total - reduced)
                                const actualAccommodation = Math.max(0, originalAccommodation - reducedAccommodation);

                                // Update the actual accommodation display
                                actualAccommodationElement.textContent = actualAccommodation;

                                // Update the maximum values for the accommodation inputs
                                const maxAmountINR = actualAccommodation * accommodationCost * eurRate;
                                const maxAmountEUR = actualAccommodation * accommodationCost;

                                document.getElementById('max-accommodation').value = maxAmountINR;
                                document.getElementById('max-accommodation-eur').value = maxAmountEUR;

                                // Update the max attributes on the input fields
                                if (reimbursementInput) reimbursementInput.setAttribute('data-max', maxAmountINR);
                                if (directInput) directInput.setAttribute('data-max', maxAmountINR);

                                // The validation will be triggered automatically by the event listeners
                            });
                        }

                        // Function to handle meal quantity changes
                        function handleMealQuantityChanges() {
                            const mealInput = document.querySelector('.reduced-meals-input');
                            const mealCost = parseFloat(document.getElementById('meal-cost').value);
                            const maxMeals = parseInt(document.getElementById('max-meals').value);
                            const actualMealsElement = document.querySelector('.actual-meals');

                            // Calculate initial values from database
                            const initialReducedMeals = parseInt(mealInput.value) || 0;
                            const initialActualMeals = Math.max(0, maxMeals - initialReducedMeals);
                            const eurRate = parseFloat(eurToInrInput.value);

                            // Update the actual meals display
                            actualMealsElement.textContent = initialActualMeals;

                            // Calculate initial reimbursement amount
                            const initialReimbursementAmount = initialActualMeals * mealCost * eurRate;

                            // Update reimbursement amount cell
                            const reimbursementCell = document.querySelector('.reimbursement-amount');
                            reimbursementCell.textContent = initialReimbursementAmount.toFixed(2);
                            reimbursementCell.setAttribute('data-amount', initialReimbursementAmount);

                            // Update total INR
                            const totalCell = document.querySelector('.total-inr');
                            totalCell.textContent = initialReimbursementAmount.toFixed(2);

                            // Add event listener for input changes
                            mealInput.addEventListener('input', function() {
                                const reducedMeals = parseInt(this.value) || 0;

                                // Calculate actual number of meals (total - reduced)
                                const actualMeals = Math.max(0, maxMeals - reducedMeals);

                                // Update the actual meals display
                                actualMealsElement.textContent = actualMeals;

                                // Calculate reimbursement amount
                                const reimbursementAmount = actualMeals * mealCost * eurRate;

                                // Update reimbursement amount cell
                                reimbursementCell.textContent = reimbursementAmount.toFixed(2);
                                reimbursementCell.setAttribute('data-amount', reimbursementAmount);

                                // Update total INR
                                totalCell.textContent = reimbursementAmount.toFixed(2);

                                // Update all totals
                                updateAllTotals();
                            });
                        }

                        // Initialize functions
                        handleMealQuantityChanges();
                        handleAccommodationQuantityChanges();

                        // Get the validation function and set up event listeners
                        const validateAccommodationTotal = validateAccommodationAmounts();

                        // Also validate accommodation on page load to handle initial database values
                        setTimeout(() => {
                            validateAccommodationTotal();
                        }, 100);
                        // Initialize calculations
                        updateAllCalculations();
                    });
                </script>

            </div>
        </div>
    </div>
</div>

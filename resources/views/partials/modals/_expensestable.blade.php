<!-- Expenses Table and Modals -->
<h2 class="pb-2 text-sm font-bold text-blue-700">Transport et Frais divers</h2>
<div class="flex flex-col">
    <div class="-m-1.5 overflow-x-auto">
        <div class="p-1.5 min-w-full inline-block align-middle">
            <div class="overflow-hidden">

    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <div class="mb-4 p-4 bg-blue-50 rounded-lg">
            <h2 class="text-lg font-semibold text-blue-800">Taux de change {{App\Models\ChancelleryRate::currentRate()->month_year->format('F Y')}}</h2>
            <div class="grid grid-cols-3 md:grid-cols-3 gap-4 mt-2">
                <div class="flex items-center">
                    <label class="mr-2 text-gray-700 w-32">EUR → INR</label>
                    <input type="number" id="eurToInr" value="{{App\Models\ChancelleryRate::currentRate()->eur_rate}}" readonly
                        class="w-32 px-2 py-1 border border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="flex items-center">
                    <label class="mr-2 text-gray-700 w-32">USD → INR</label>
                    <input type="number" id="usdToInr" value="{{App\Models\ChancelleryRate::currentRate()->usd_rate}}" readonly
                        class="w-32 px-2 py-1 border border-gray-300 rounded-md shadow-sm">
                </div>
            </div>
        </div>

        <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
            <thead>
                <tr>
                    <th scope="col" colspan="2" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase bg-gray-50">Libelle des dépenses à prendre en charge</th>
                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase bg-gray-50">A rembourser à l'agent</th>
                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase bg-gray-50">Devise</th>
                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase bg-gray-50">Prise en charge directe</th>
                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase bg-gray-50">Devise</th>
                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase bg-gray-50">Total INR</th>
                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase bg-gray-50">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Repas Row -->
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100">
                    <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800">
                        <span class="expense-badge bg-green-100 text-green-800">Repas</span>
                    </td>
                    <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                       {{$missionOrder->no_meals}}
                    </td>
                    <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800 reimbursement-amount" data-currency="INR" data-amount="{{$missionOrder->no_meals * $missionOrder->bareme->meal_cost * $current_rate->eur_rate}}">
                        {{$missionOrder->no_meals * $missionOrder->bareme->meal_cost * $current_rate->eur_rate}}
                    </td>
                    <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                       INR
                    </td>
                    <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800 direct-amount" data-currency="INR" data-amount="0">
                        --
                    </td>
                    <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">--
                    </td>
                    <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800 total-inr">
                       {{$missionOrder->no_meals * $missionOrder->bareme->meal_cost * $current_rate->eur_rate}}
                    </td>
                    <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                    </td>
                </tr>

                <!-- Hébergement Row -->
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100">
                    <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800">
                        <span class="expense-badge bg-blue-100 text-blue-800">Hébergement</span>
                    </td>
                    <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                        {{$missionOrder->no_accomodation}}
                    </td>
                    <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800 reimbursement-amount" data-currency="INR" data-amount="{{$missionOrder->no_accomodation * $missionOrder->bareme->accomodation_cost * $current_rate->eur_rate}}">
                        {{$missionOrder->no_accomodation * $missionOrder->bareme->accomodation_cost * $current_rate->eur_rate}}
                    </td>
                    <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                       INR
                    </td>
                    <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800 direct-amount" data-currency="INR" data-amount="0">
                        --
                    </td>
                    <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">--
                    </td>
                    <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800 total-inr">
                       {{$missionOrder->no_accomodation * $missionOrder->bareme->accomodation_cost * $current_rate->eur_rate}}
                    </td>
                    <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                    </td>
                </tr>

                      <!-- Repas #2 Row -->
        @php $index = 2; @endphp
        @foreach ($missionOrder->expenses->where('type', 'extra_meal') as $expense)
        <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100">
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800">
                <span class="expense-badge bg-green-100 text-green-800">Repas #{{ $loop->iteration }}</span>
                <input type="hidden" name="expenses[{{ $index }}][type]" value="extra_meal">
                <input type="hidden" name="expenses[{{ $index }}][expense_id]" value="{{ $expense->id }}">
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <input type="text" name="expenses[{{ $index }}][meal_location]" value="{{ $expense->meal_location }}" class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <input type="number" name="expenses[{{ $index }}][reimbursement_amount]" value="{{ $expense->reimbursement_amount ?? 0 }}" step="0.01" min="0" class="reimbursement-input w-full px-2 py-1 border border-gray-300 rounded-md text-sm" data-currency="INR">
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <select name="expenses[{{ $index }}][reimbursement_currency]" class="reimbursement-currency currency-select w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
                    <option value="INR" @selected($expense->reimbursement_currency==='INR')>INR</option>
                    <option value="EUR" @selected($expense->reimbursement_currency==='EUR')>EUR</option>
                    <option value="USD" @selected($expense->reimbursement_currency==='USD')>USD</option>
                </select>
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <input type="number" name="expenses[{{ $index }}][direct_amount]" value="{{ $expense->direct_amount ?? 0 }}" step="0.01" min="0" class="direct-input w-full px-2 py-1 border border-gray-300 rounded-md text-sm" data-currency="INR">
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <select name="expenses[{{ $index }}][direct_currency]" class="direct-currency currency-select w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
                    <option value="INR" @selected($expense->reimbursement_currency==='INR')>INR</option>
                    <option value="EUR" @selected($expense->reimbursement_currency==='EUR')>EUR</option>
                    <option value="USD" @selected($expense->reimbursement_currency==='USD')>USD</option>
                </select>
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800 total-td">
                <input type="hidden" name="expenses[{{ $index }}][total_inr]" value="{{$expense->total_inr}}">
                <span class="total-inr">{{$expense->total_inr}}</span>
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <div class="flex flex-col space-y-1">
                    <button type="button" class="delete-row px-2 py-1 bg-red-100 text-red-600 rounded-md hover:bg-red-200 text-xs">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
        @php $index++; @endphp
        @endforeach

        {{-- <!-- Hébergement #2 Row -->
        <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100">
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800">
                <span class="expense-badge bg-blue-100 text-blue-800">Hébergement #2</span>
                <input type="hidden" name="expenses[{{ $index }}][type]" value="accommodation_extra">
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <input type="text" name="expenses[{{ $index }}][description]" value="" class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <input type="number" name="expenses[{{ $index }}][reimbursement_amount]" value="0" step="0.01" min="0" class="reimbursement-input w-full px-2 py-1 border border-gray-300 rounded-md text-sm" data-currency="INR">
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <select name="expenses[{{ $index }}][reimbursement_currency]" class="reimbursement-currency currency-select w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
                    <option value="INR" selected>INR</option>
                    <option value="EUR">EUR</option>
                    <option value="USD">USD</option>
                </select>
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <input type="number" name="expenses[{{ $index }}][direct_amount]" value="0" step="0.01" min="0" class="direct-input w-full px-2 py-1 border border-gray-300 rounded-md text-sm" data-currency="INR">
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <select name="expenses[{{ $index }}][direct_currency]" class="direct-currency currency-select w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
                    <option value="INR" selected>INR</option>
                    <option value="EUR">EUR</option>
                    <option value="USD">USD</option>
                </select>
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800 total-td">
                <input type="hidden" name="expenses[{{ $index }}][total_inr]" value="0">
                <span class="total-inr">0.00</span>
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <div class="flex flex-col space-y-1">
                    <button type="button" class="upload-receipt px-2 py-1 bg-blue-100 text-blue-600 rounded-md hover:bg-blue-200 text-xs" data-expense-type="accommodation_extra" data-expense-id="{{ $index }}">
                        <i class="fas fa-receipt mr-1"></i> Receipt
                    </button>
                    <button type="button" class="delete-row px-2 py-1 bg-red-100 text-red-600 rounded-md hover:bg-red-200 text-xs">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
        @php $index++; @endphp --}}

        <!-- Transport Row -->
        @foreach ($missionOrder->expenses->where('type', 'transport') as $expense)
        <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100">
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800">
                <span class="expense-badge bg-red-100 text-red-800">Transport</span>
                <input type="hidden" name="expenses[{{ $index }}][type]" value="transport">
                <input type="hidden" name="expenses[{{ $index }}][expense_id]" value="{{ $expense->id }}">
                <input type="hidden" name="expenses[{{ $index }}][transport_type]" value="{{ $expense->transport_type }}">
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <input type="text" name="expenses[{{ $index }}][transport_type]" value="{{  $expense->transport_type }}" class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm" readonly>
                <span class="text-xs">{{ $expense->transport_type === 'car_rental_with_driver' ? '(' . ($expense->passenger == 1 ? __('passenger') . ',' : '') . ($expense->distance == 1 ? __('distance') . ',' : '') . ($expense->material == 1 ? __('material') . ',' : '') . ($expense->visits == 1 ? __('visits') : '') . ')' : '' }}</span>
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <input type="number" name="expenses[{{ $index }}][reimbursement_amount]" value="{{ $expense->reimbursement_amount ?? 0 }}" step="0.01" min="0" class="reimbursement-input w-full px-2 py-1 border border-gray-300 rounded-md text-sm" data-currency="INR">
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <select name="expenses[{{ $index }}][reimbursement_currency]" class="reimbursement-currency currency-select w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
                    <option value="INR" @selected($expense->reimbursement_currency==='INR')>INR</option>
                    <option value="EUR" @selected($expense->reimbursement_currency==='EUR')>EUR</option>
                    <option value="USD" @selected($expense->reimbursement_currency==='USD')>USD</option>
                </select>
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <input type="number" name="expenses[{{ $index }}][direct_amount]" value="{{$expense->direct_amount}}" step="0.01" min="0" class="direct-input w-full px-2 py-1 border border-gray-300 rounded-md text-sm" data-currency="INR">
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <select name="expenses[{{ $index }}][direct_currency]" class="direct-currency currency-select w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
                    <option value="INR" @selected($expense->reimbursement_currency==='INR')>INR</option>
                    <option value="EUR" @selected($expense->reimbursement_currency==='EUR')>EUR</option>
                    <option value="USD" @selected($expense->reimbursement_currency==='USD')>USD</option>
                </select>
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800 total-td">
                <input type="hidden" name="expenses[{{ $index }}][total_inr]" value="0">
                <span class="total-inr">{{$expense->total_inr}}</span>
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <div class="flex flex-col space-y-1">
                    <button type="button" class="delete-row px-2 py-1 bg-red-100 text-red-600 rounded-md hover:bg-red-200 text-xs">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
        @php $index++; @endphp
        @endforeach

        <!-- Other Expenses Header -->
        <tr>
            <td colspan="8" class="px-3 py-2 bg-blue-800 text-center font-bold text-white">AUTRES DEPENSES</td>
        </tr>

        <!-- Other Expenses -->
        @foreach ($missionOrder->expenses->whereIn('type', ['visa','inscription','other']) as $expense)
        <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100">
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800">
                <span class="expense-badge bg-purple-100 text-purple-800">
                    @switch($expense->type)
                        @case('visa')
                            Frais de visa
                            @break
                        @case('inscription')
                            Frais d'inscription
                            @break
                        @case('other')
                            Autre
                            @break
                    @endswitch
                </span>
                <input type="hidden" name="expenses[{{ $index }}][type]" value="{{ $expense->type }}">
                <input type="hidden" name="expenses[{{ $index }}][expense_id]" value="{{ $expense->id }}">
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <input type="text" name="expenses[{{ $index }}][description]" value="{{ $expense->description }}" class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <input type="number" name="expenses[{{ $index }}][reimbursement_amount]" value="{{ $expense->reimbursement_amount ?? 0 }}" step="0.01" min="0" class="reimbursement-input w-full px-2 py-1 border border-gray-300 rounded-md text-sm" data-currency="INR">
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <select name="expenses[{{ $index }}][reimbursement_currency]" class="reimbursement-currency currency-select w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
                    <option value="INR" @selected($expense->reimbursement_currency==='INR')>INR</option>
                    <option value="EUR" @selected($expense->reimbursement_currency==='EUR')>EUR</option>
                    <option value="USD" @selected($expense->reimbursement_currency==='USD')>USD</option>
                </select>
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <input type="number" name="expenses[{{ $index }}][direct_amount]" value="{{$expense->direct_amount}}" step="0.01" min="0" class="direct-input w-full px-2 py-1 border border-gray-300 rounded-md text-sm" data-currency="INR">
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <select name="expenses[{{ $index }}][direct_currency]" class="direct-currency currency-select w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
                    <option value="INR" @selected($expense->reimbursement_currency==='INR')>INR</option>
                    <option value="EUR" @selected($expense->reimbursement_currency==='EUR')>EUR</option>
                    <option value="USD" @selected($expense->reimbursement_currency==='USD')>USD</option>
                </select>
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800 total-td">
                <input type="hidden" name="expenses[{{ $index }}][total_inr]" value="0">
                <span class="total-inr">{{$expense->total_inr}}</span>
            </td>
            <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                <div class="flex flex-col space-y-1">
                    <button type="button" class="delete-row px-2 py-1 bg-red-100 text-red-600 rounded-md hover:bg-red-200 text-xs">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
        @php $index++; @endphp
        @endforeach

        <!-- Total Row -->
        <tr class="bg-gray-200 font-bold">
            <td colspan="2" class="px-3 py-3 text-right border border-gray-200 whitespace-nowrap text-sm text-gray-800">
                SUBTOTALS (INR)
            </td>
            <td id="reimbursement-total" class="px-3 py-3 text-center border border-gray-200 whitespace-nowrap text-sm text-blue-800">
                <input type="hidden" id="reimbursement-total-input" name="totals[reimbursement]" value="0">
                0.00
            </td>
            <td class="px-3 py-3 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
            </td>
            <td id="direct-total" class="px-3 py-3 text-center border border-gray-200 whitespace-nowrap text-sm text-blue-800">
                <input type="hidden" id="direct-total-input" name="totals[direct]" value="0">
                0.00
            </td>
            <td class="px-3 py-3 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
            </td>
            <td id="grand-total" class="px-3 py-3 text-center border border-gray-200 whitespace-nowrap text-lg text-blue-800">
                <input type="hidden" id="grand-total-input" name="totals[grand_total]" value="0">
                0.00
            </td>
            <td class="px-3 py-3 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
            </td>
            <input type="hidden" id="reimbursement-total-input" name="totals[reimbursement]" value="0">
            <input type="hidden" id="direct-total-input" name="totals[direct]" value="0">
            <input type="hidden" id="grand-total-input" name="totals[grand_total]" value="0">
        </tr>
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const eurToInrInput = document.getElementById('eurToInr');
            const usdToInrInput = document.getElementById('usdToInr');
            //const eurToUsdInput = document.getElementById('eurToUsd');
            //const updateRatesButton = document.getElementById('updateRates');
            //const addExpenseButton = document.getElementById('add-expense');
            const grandTotalElement = document.getElementById('grand-total');
            const reimbursementTotalElement = document.getElementById('reimbursement-total');
            const directTotalElement = document.getElementById('direct-total');

            // let exchangeRates = {
            //     EUR: {INR: parseFloat(eurToInrInput.value), USD: parseFloat(eurToUsdInput.value)},
            //     USD: {INR: parseFloat(usdToInrInput.value), EUR: 1/parseFloat(eurToUsdInput.value)},
            //     INR: {EUR: 1/parseFloat(eurToInrInput.value), USD: 1/parseFloat(usdToInrInput.value)}
            // };

            let exchangeRates = {
                EUR: parseFloat(eurToInrInput.value),
                USD: parseFloat(usdToInrInput.value)
            };
console.log(exchangeRates);
            // // Update all exchange rates
            // function updateExchangeRates() {
            //     exchangeRates.EUR.INR = parseFloat(eurToInrInput.value);
            //     exchangeRates.USD.INR = parseFloat(usdToInrInput.value);
            //     exchangeRates.EUR.USD = parseFloat(eurToUsdInput.value);
            //     exchangeRates.USD.EUR = 1/parseFloat(eurToUsdInput.value);
            //     exchangeRates.INR.EUR = 1/parseFloat(eurToInrInput.value);
            //     exchangeRates.INR.USD = 1/parseFloat(usdToInrInput.value);

            //     updateAllCalculations();
            // }

            // // Update all calculations when the exchange rates change
            // updateRatesButton.addEventListener('click', function() {
            //     updateExchangeRates();
            // });

            // Add event listeners to all input and select elements for dynamic calculation
            document.querySelectorAll('.reimbursement-input, .direct-input, .reimbursement-currency, .direct-currency').forEach(element => {
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

            // Add new expense row
            // addExpenseButton.addEventListener('click', function() {
            //     const tbody = document.querySelector('tbody');
            //     const newRow = document.createElement('tr');
            //     newRow.className = 'odd:bg-white even:bg-gray-50 hover:bg-gray-100';
            //     newRow.innerHTML = `
            //         <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800">
            //             <span class="expense-badge bg-yellow-100 text-yellow-800">New Expense</span>
            //         </td>
            //         <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
            //             <input type="text" placeholder="Description" class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
            //         </td>
            //         <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
            //             <input type="number" value="0" step="0.01" min="0" class="reimbursement-input w-full px-2 py-1 border border-gray-300 rounded-md text-sm" data-currency="INR">
            //         </td>
            //         <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
            //             <select class="reimbursement-currency currency-select w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
            //                 <option value="EUR">EUR</option>
            //                 <option value="INR" selected>INR</option>
            //                 <option value="USD">USD</option>
            //             </select>
            //         </td>
            //         <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
            //             <input type="number" value="0" step="0.01" min="0" class="direct-input w-full px-2 py-1 border border-gray-300 rounded-md text-sm" data-currency="INR">
            //         </td>
            //         <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
            //             <select class="direct-currency currency-select w-full px-2 py-1 border border-gray-300 rounded-md text-sm">
            //                 <option value="EUR">EUR</option>
            //                 <option value="INR" selected>INR</option>
            //                 <option value="USD">USD</option>
            //             </select>
            //         </td>
            //         <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm font-medium text-gray-800 total-inr">
            //             0.00
            //         </td>
            //         <td class="px-3 py-2 text-center border border-gray-200 whitespace-nowrap text-sm text-gray-800">
            //             <button class="delete-row px-2 py-1 bg-red-100 text-red-600 rounded-md hover:bg-red-200 text-xs">
            //                 <i class="fas fa-trash"></i>
            //             </button>
            //         </td>
            //     `;

            //     // Insert before the total row
            //     tbody.insertBefore(newRow, tbody.lastElementChild);

            //     // Add event listeners to the new row
            //     newRow.querySelector('.reimbursement-input').addEventListener('input', updateRowCalculation);
            //     newRow.querySelector('.direct-input').addEventListener('input', updateRowCalculation);
            //     newRow.querySelector('.reimbursement-currency').addEventListener('change', updateRowCalculation);
            //     newRow.querySelector('.direct-currency').addEventListener('change', updateRowCalculation);
            //     newRow.querySelector('.delete-row').addEventListener('click', function() {
            //         if (confirm('Are you sure you want to delete this expense?')) {
            //             newRow.remove();
            //             updateAllTotals();
            //         }
            //     });

            //     // Update the row calculation
            //     updateRowCalculation.call(newRow.querySelector('.reimbursement-input'));
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
                //console.log(totalInput);
                if (!reimbursementInput || !reimbursementCurrency || !directInput || !directCurrency || !totalElement) return;

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
                totalInput.value = totalINR.toFixed(2);
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
        reimbursementTotalElement.textContent = reimbursementTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        directTotalElement.textContent = directTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        grandTotalElement.textContent = grandTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        // Update the hidden input fields for form submission
        document.getElementById('reimbursement-total-input').value = reimbursementTotal.toFixed(2);
        document.getElementById('direct-total-input').value = directTotal.toFixed(2);
        document.getElementById('grand-total-input').value = grandTotal.toFixed(2);
    }

            // Initialize calculations
            updateAllCalculations();
        });
    </script>

            </div>
        </div>
    </div>
</div>

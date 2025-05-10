<!-- Expenses Table and Modals -->
<h2 class="pb-2 text-sm font-bold text-blue-700">Transport et Frais divers</h2>
<div class="flex flex-col">
    <div class="-m-1.5 overflow-x-auto">
        <div class="p-1.5 min-w-full inline-block align-middle">
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                    <thead>
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Nature de
                                la dépense</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Date
                                dépense</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Montant</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Devise</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($missionOrder->expenses as $expense)
                            <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                                <td class="px-6 text-center border border-gray-200 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                    {{ $expense->description }}</td>
                                <td class="px-6 text-center border border-gray-200 py-4 whitespace-nowrap text-sm text-gray-800">
                                    {{ $expense->expense_date->format('d/m/Y H:i') }}</td>
                                <td class="px-6 text-center border border-gray-200 py-4 whitespace-nowrap text-sm text-gray-800">
                                    {{ $expense->amount }}</td>
                                <td class="px-6 text-center border border-gray-200 py-4 whitespace-nowrap text-sm text-gray-800">
                                    {{ $expense->currency }}</td>
                                <td class="px-6 text-center border border-gray-200 py-4 whitespace-nowrap text-sm font-medium">
                                    <button type="button"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900"
                                        data-modal-toggle="viewExpenseModal-{{ $expense->id }}">{{ __('View') }}</button>
                                    {{-- <button type="button"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900"
                                        data-modal-toggle="editExpenseModal-{{ $expense->id }}">{{ __('Edit') }}</button> --}}
                                    <button type="button"
                                        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900"
                                        data-modal-toggle="deleteExpenseModal-{{ $expense->id }}">{{ __('Delete') }}</button>
                                </td>
                                @include('partials.modals._view-expense')
                                {{-- @include('partials.modals._edit-expense') --}}
                                @include('partials.modals._delete-expense')
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        @foreach ($missionOrder->getExpensesByCurrency() as $currency=>$currencyAmount)
                        <tr>
                            <th scope="col"></th>
                            <th scope="col"
                                class="px-6 py-3 bg-gray-200 border border-gray-300 text-blue-700 font-bold text-md-center uppercase">
                                SOMME
                            </th>
                            <th scope="col"
                                class="px-6 py-3 bg-gray-200 border border-gray-300 text-blue-700 font-bold text-md-center uppercase">
                                {{ $currencyAmount }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 bg-gray-200 border border-gray-300 text-blue-700 font-bold text-md-center uppercase">
                                {{ $currency }}
                            </th>
                            <th scope="col"></th>
                        </tr>
                        @endforeach
                        <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                            <td colspan="5"
                                class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <x-primary-button type="button" data-modal-toggle="createExpenseModal">Ajouter
                                    Dépense</x-primary-button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>


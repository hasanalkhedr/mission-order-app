<!-- Expenses Table and Modals -->
<h2 class="pb-2 text-sm font-bold text-blue-700">Transport et Frais divers</h2>
<div class="flex flex-col">
    <div class="-m-1.5 overflow-x-auto">
        <div class="p-1.5 min-w-full inline-block align-middle">
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Type</th>
                            {{-- <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Nature de la dépense</th> --}}
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Détails</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Date dépense</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Montant</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Devise</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form></form>
                        @foreach ($missionOrder->expenses as $expense)
                            <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                                <!-- Type Column -->
                                <td class="px-6 text-center border border-gray-200 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                    @if($expense->type === 'transport')
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Transport</span>
                                    @elseif ($expense->type === 'extra_meal')
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Repas</span>
                                    @elseif ($expense->type === 'visa')
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Visa</span>
                                    @elseif ($expense->type === 'inscription')
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Inscription</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">autre</span>
                                    @endif
                                </td>

                                {{-- <!-- Description Column -->
                                <td class="px-6 text-center border border-gray-200 py-4 whitespace-nowrap text-sm text-gray-800">
                                    {{ $expense->description }}
                                </td> --}}

                                <!-- Details Column -->
                                <td class="px-6 text-center border border-gray-200 py-4 whitespace-nowrap text-sm text-gray-800">
                                    @if($expense->type === 'transport')
                                        <div class="text-sm">
                                            <span class="font-semibold">{{ __( 'expense.transport_types.' .$expense->transport_type) }}</span>
                                            @if($expense->transport_details)
                                                <p class="text-xs text-gray-500">{{ $expense->transport_details }}</p>
                                            @endif
                                        </div>
                                    @elseif($expense->type === 'other')
                                        <div class="text-sm">
                                            <span class="font-semibold">{{ $expense->description }}</span>
                                        </div>
                                    @else
                                        <div class="text-sm">
                                            <span class="font-semibold">{{ $expense->meal_location }}</span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Date Column -->
                                <td class="px-6 text-center border border-gray-200 py-4 whitespace-nowrap text-sm text-gray-800">
                                    {{ $expense->expense_date->format('d/m/Y H:i') }}
                                </td>

                                <!-- Amount Column -->
                                <td class="px-6 text-center border border-gray-200 py-4 whitespace-nowrap text-sm text-gray-800">
                                    {{ number_format($expense->amount, 2) }}
                                </td>

                                <!-- Currency Column -->
                                <td class="px-6 text-center border border-gray-200 py-4 whitespace-nowrap text-sm text-gray-800">
                                    {{ $expense->currency }}
                                </td>

                                <!-- Actions Column -->
                                <td class="px-6 text-center border border-gray-200 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex justify-center space-x-2">
                                        <button type="button"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center"
                                            data-modal-toggle="viewExpenseModal-{{ $expense->id }}">{{ __('View') }}</button>
                                            <button
                                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900"
                                                type="button" data-modal-toggle="viewDocumentModal-{{ $expense->id }}">
                                                {{ __('Voir le document') }}
                                            </button>
                                        <button type="button"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center"
                                            data-modal-toggle="editExpenseModal-{{ $expense->id }}">{{ __('Edit') }}</button>
                                        <button type="button"
                                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center"
                                            data-modal-toggle="deleteExpenseModal-{{ $expense->id }}">{{ __('Delete') }}</button>
                                    </div>
                                    @include('partials.modals._view-expense')
                                    @include('partials.modals._view-document')
                                    @include('partials.modals._edit-expense')
                                    @include('partials.modals._delete-expense')
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        @foreach ($missionOrder->getExpensesByCurrency() as $currency=>$currencyAmount)
                        <tr>
                            <th scope="col" colspan="3"></th>
                            <th scope="col"
                                class="px-6 py-3 bg-gray-200 border border-gray-300 text-blue-700 font-bold text-md-center uppercase">
                                SOMME
                            </th>
                            <th scope="col"
                                class="px-6 py-3 bg-gray-200 border border-gray-300 text-blue-700 font-bold text-md-center uppercase">
                                {{ number_format($currencyAmount, 2) }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 bg-gray-200 border border-gray-300 text-blue-700 font-bold text-md-center uppercase">
                                {{ $currency }}
                            </th>
                            <th scope="col"></th>
                        </tr>
                        @endforeach
                        <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                            <td colspan="7"
                                class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <x-primary-button type="button" data-modal-toggle="createExpenseModal">Ajouter Dépense</x-primary-button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

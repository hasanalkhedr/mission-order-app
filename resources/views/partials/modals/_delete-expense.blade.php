<div id="deleteExpenseModal-{{ $expense->id }}" tabindex="-1"
    aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-auto fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full mt-40 max-w-2xl h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow p-2">
            <!-- Modal header -->
            <div class="flex justify-between items-center p-2 rounded-t border-b">
                <div
                    class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left blue-color">
                    {{ __('Delete Expense') }}
                </div>
                <div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-toggle="deleteExpenseModal-{{ $expense->id }}">
                        <svg aria-hidden="true" class="w-5 h-5"
                            fill="currentColor" viewBox="0 0 20 20"
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
            <div class="p-6 space-y-6">
                <div class="text-base leading-relaxed text-gray-500">
                    {{ __('Are you sure you want to delete this expense') }}?
                    {{ __('This action cannot be undone') }}.
                </div>
            </div>
            <!-- Modal footer -->
            <div
                class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                <div>
                    <button
                        data-modal-toggle="deleteExpenseModal-{{ $expense->id }}"
                        type="button"
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                        {{ __('Cancel') }}
                    </button>
                </div>
                <div>
                    <form method="POST" id="deleteExpenseForm-{{ $expense->id }}" key="deleteExpenseForm-{{ $expense->id }}"
                        action="{{ route('expenses.destroy', $expense->id) }}">
                        @csrf
                        @method('DELETE')
                        <button
                            data-modal-toggle="deleteExpenseModal-{{ $expense->id }}"
                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            {{ __('Delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="createRateModal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-auto fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full mt-40 max-w-2xl h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow p-2">
            <!-- Modal header -->
            <div class="flex justify-between items-center p-2 rounded-t border-b">
                <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left blue-color">
                    {{ __('Add Chancellery Rates') }}
                </div>
                <div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-toggle="createRateModal">
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
                <form method="POST" action="{{ route('chancelleryRates.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="month_year" value="{{ now()->format('Y-m-01') }}">
@php
    \Carbon\Carbon::setLocale('fr');
@endphp;
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold mb-4">{{ __('Set Currency Rate for ') }}{{ now()->isoFormat('MMMM YYYY') }}</h2>

                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 w-full">
                            <div class="form-group">
                                <label for="eur_rate" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('INR to EUR Rate') }}
                                </label>
                                <input type="number" step="0.000001" class="form-control" id="eur_rate" name="eur_rate"
                                    required placeholder="{{ __('Enter INR to EUR rate') }}">
                                <small class="form-text text-muted">
                                    {{ __('1 INR = ? EUR') }}
                                </small>
                            </div>

                            {{-- <div class="form-group">
                                <label for="usd_rate" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('USD to INR Rate') }}
                                </label>
                                <input type="number" step="0.0001" class="form-control" id="usd_rate" name="usd_rate"
                                    required placeholder="Enter USD to INR rate">
                                <small class="form-text text-muted">
                                    {{ __('1 USD = ? INR') }}
                                </small>
                            </div> --}}
                        </div>
                    </div>

                    <div class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                        <div>
                            <button
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                                {{ __('Create') }}
                            </button>
                        </div>
                        <div>
                            <button data-modal-toggle="createRateModal" type="button"
                                class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                {{ __('Cancel') }}
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

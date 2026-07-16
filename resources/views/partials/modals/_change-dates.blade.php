<div id="editDatesModal-{{ $missionOrder->id }}" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow ">
            <!-- Modal header -->
            <div class="flex justify-between items-center p-4 rounded-t border-b ">
                <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left blue-color">
                    {{ __('Edit Dates ') }} : {{ $missionOrder->order_number }}|{{$missionOrder->purpose}}
                </div>
                <div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-toggle="editDatesModal-{{ $missionOrder->id }}">
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
                <form method="POST" action="{{ route('mission_orders.changeDates',$missionOrder->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="flex flex-wrap -mx-3 mb-2">
                        <div class="w-2/3 px-3">
                            <x-label>
                                Date le Ordre<span class="text-red-500">*</span>
                            </x-label>
                            <x-date-time-input name="order_date" value="{{ old('order_date', $missionOrder->order_date->format('Y-m-d')) }}"
                                type="date" required>
                            </x-date-time-input>
                        </div>
                    </div>
                    {{-- <div class="flex flex-wrap -mx-3 mb-2">
                        <div class="w-2/3 px-3">
                            <x-label>
                                Débute le : Date & Heure :<span class="text-red-500">*</span>
                            </x-label>
                            <x-date-time-input name="start_date" value="{{ old('start_date', $missionOrder->start_date->format('Y-m-d')) }}"
                                type="date" required>
                            </x-date-time-input>
                            <x-date-time-input name="start_time" value="{{ old('start_time', $missionOrder->start_time) }}"
                                type="time" required>
                            </x-date-time-input>
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-2">
                        <div class="w-2/3 px-3">
                            <x-label>
                                S'achève le : Date & Heure :<span class="text-red-500">*</span>
                            </x-label>
                            <x-date-time-input name="end_date" value="{{ old('end_date', $missionOrder->end_date->format('Y-m-d')) }}" type="date"
                                required>
                            </x-date-time-input>
                            <x-date-time-input name="end_time" value="{{ old('end_time', $missionOrder->end_time) }}" type="time"
                                required>
                            </x-date-time-input>
                        </div>
                    </div> --}}
                    <div class="flex flex-wrap -mx-3 mb-0 w-full">

            <div class="w-1/2 px-1">
                <x-label>Date de départ<span class="text-red-500">*</span></x-label>
                <x-date-time-input class="w-full h-12" name="start_date" id="start_date" value="{{ old('start_date', $missionOrder->start_date->format('Y-m-d')) }}" type="date" required></x-date-time-input>
            </div>
            <div class="w-1/4 px-1">
                <x-label>Heure de départ<span class="text-red-500">*</span></x-label>
                <x-date-time-input class="w-full h-12" name="start_time" value="{{ old('start_time', $missionOrder->start_time) }}" type="time" required></x-date-time-input>
            </div>
            <div class="w-1/4 pl-1 pr-3">
                <x-label>Heure d'arrivée<span class="text-red-500">*</span></x-label>
                <x-date-time-input class="w-full h-12" name="start_time2" value="{{ old('start_time2', $missionOrder->start_time2) }}" type="time" required></x-date-time-input>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-2 w-full">
            <div class="w-1/2 px-1">
                <x-label>Date de départ<span class="text-red-500">*</span></x-label>
                <x-date-time-input class="w-full h-12" name="end_date" id="end_date" value="{{ old('end_date', $missionOrder->end_date->format('Y-m-d')) }}" type="date" required></x-date-time-input>
            </div>
            <div class="w-1/4 px-1">
                <x-label>Heure de départ<span class="text-red-500">*</span></x-label>
                <x-date-time-input class="w-full h-12" name="end_time2" value="{{ old('end_time2', $missionOrder->end_time2) }}" type="time" required></x-date-time-input>
            </div>
            <div class="w-1/4 pl-1 pr-3">
                <x-label>Heure d'arrivée<span class="text-red-500">*</span></x-label>
                <x-date-time-input class="w-full h-12" name="end_time" value="{{ old('end_time', $missionOrder->end_time) }}" type="time" required></x-date-time-input>
            </div>
        </div>
        <!-- Hidden input to send weekend flag to backend -->
        <input type="hidden" name="has_weekend" id="has_weekend" value="{{old('has_weekend', $missionOrder->has_weekend)}}">

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
                    <div class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                        <div>
                            <button data-modal-toggle="editDatesModal-{{ $missionOrder->id }}" type="button"
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                {{ __('Cancel') }}
                            </button>
                        </div>
                        <div>
                            <button
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                                data-modal-toggle="editDatesModal-{{ $missionOrder->id }}">{{ __('Edit') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

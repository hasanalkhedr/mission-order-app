<div id="editModal-{{ $department->id }}" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow ">
            <!-- Modal header -->
            <div class="flex justify-between items-center p-4 rounded-t border-b ">
                <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left blue-color">
                    {{ __('Edit Department') }} : {{ $department->name }}
                </div>
                <div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-toggle="editModal-{{ $department->id }}">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
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
                <form method="POST"
                    action="{{ route('departments.update', ['department' => $department->id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="relative z-0 mb-6 w-full group">
                        <input type="text" name="name"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            value="{{ $department->name }}" required />
                        <label for="name"
                            class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">{{ __('Name') }}</label>
                    </div>
                    <div class="relative z-0 mb-6 w-full group">
                        <label for="manager_id"
                            class="mb-2 text-sm font-medium blue-color">{{ __('Select supervisor') }}</label>
                        <input type="hidden" name="old_manager_id"
                            value="{{ $department->manager_id }}">
                        <select name="manager_id" id="manager_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="" disabled>{{ __('Select Supervisor') }}</option>
                            @if (count($department->employees))
                                @foreach ($department->employees as $employee)
                                    @if ($employee->id === $department->manager_id)
                                        <option value="{{ $employee->id }}" selected>
                                            {{ $employee->first_name }} {{ $employee->last_name }}
                                        </option>
                                    @else
                                        <option value="{{ $employee->id }}">
                                            {{ $employee->first_name }} {{ $employee->last_name }}
                                        </option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="relative z-0 mb-6 w-full group">
                        <label for="controller_id"
                            class="mb-2 text-sm font-medium blue-color">{{ __('Select controller') }}</label>
                        <input type="hidden" name="old_controller_id"
                            value="{{ $department->controller_id }}">
                        <select name="controller_id" id="controller_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">{{ __('No Controller') }}</option>
                            {{-- @if (count($department->employees)) --}}
                                @foreach (\App\Models\Employee::all() as $employee)
                                    @if ($employee->id === $department->controller_id)
                                        <option value="{{ $employee->id }}" selected>
                                            {{ $employee->first_name }} {{ $employee->last_name }}
                                        </option>
                                    @else
                                        <option value="{{ $employee->id }}">
                                            {{ $employee->first_name }} {{ $employee->last_name }}
                                        </option>
                                    @endif
                                @endforeach
                            {{-- @endif --}}
                        </select>
                    </div>
                    <div
                        class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                        <div>
                            <button
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                                data-modal-toggle="editModal-{{ $department->id }}">{{ __('Edit') }}
                            </button>
                        </div>
                        <div>
                            <button data-modal-toggle="editModal-{{ $department->id }}"
                                type="button"
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

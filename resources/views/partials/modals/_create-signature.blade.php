<div id="createSignatureModal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-auto fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full mt-40 max-w-2xl h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow p-2">
            <!-- Modal header -->
            <div class="flex justify-between items-center p-2 rounded-t border-b">
                <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left blue-color">
                    {{ __('Add Signature') }}
                </div>
                <div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-toggle="createSignatureModal">
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
                <form method="POST" action="{{ route('signatures.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-1/2 px-3">
                            <label for="employee_id" class="mb-2 text-sm font-medium blue-color">
                                {{ __('Select Employee') }}
                            </label>
                            <select name="employee_id" id="employee_id_create"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" disabled selected>{{ __('Select Employee') }}</option>
                                @forelse($employees as $employee)
                                    <option value="{{ $employee->id }}" @selected(old('employee_id', optional(auth()->user()->employee)->id) == $employee->id)>
                                        {{ $employee->first_name }} {{ $employee->last_name }}
                                        @if ($employee->department)
                                            ({{ $employee->department->name }})
                                        @endif
                                    </option>
                                @empty
                                    <option value="" disabled>{{ __('No employees available') }}</option>
                                @endforelse
                            </select>
                        </div>
                        {{-- signature image --}}
                        <div class="w-1/2 px-3 h-1/3">
                            <!-- Image upload input and preview with button on top of the image -->
                            <div class="relative w-full h-full mx-auto">
                                <!-- Image preview -->
                                <img id="profileImagePreview"
                                    src="{{ Vite::asset('resources/images/default-avatar.jpg') }}" alt="Image de profil"
                                    class="object-cover w-full h-full">
                                <!-- Browse Files Button positioned on top of the image -->
                                <div
                                    class="rounded-xl absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-50 hover:opacity-100 transition-opacity">
                                    <input type="file" name="signature_path" id="profile_image" class="hidden"
                                        onchange="previewImage(event)">
                                    <button type="button" onclick="document.getElementById('profile_image').click()"
                                        class="text-white bg-blue-600 hover:bg-blue-700 rounded-xl w-1/2">
                                        <img src="{{ Vite::asset('resources/images/browse-image.png') }}"
                                            alt="Browse Image">
                                    </button>
                                </div>
                                <!-- Image Preview Script -->
                                <script>
                                    function previewImage(event) {
                                        const reader = new FileReader();
                                        reader.onload = function() {
                                            const output = document.getElementById('profileImagePreview');
                                            output.src = reader.result;
                                        };
                                        reader.readAsDataURL(event.target.files[0]);
                                    }
                                </script>
                            </div>

                        </div>
                    </div>
                    <div class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                        <div>
                            <button
                                class="text-white hover:bg-blue-800 bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                                {{ __('Create') }}
                            </button>
                        </div>
                        <div>
                            <button data-modal-toggle="createSignatureModal" type="button"
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

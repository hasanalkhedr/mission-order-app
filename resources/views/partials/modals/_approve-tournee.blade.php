<div id="approveModal-{{ $tournee->id }}" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div class="flex justify-between items-center p-2 rounded-t border-b">
                <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left blue-color">
                    {{ __('Approve Tournee ') }}:{{ $tournee->order_number }}:{{ $tournee->purpose }}
                </div>
                <div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-toggle="approveModal-{{ $tournee->id }}">
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
            <div class="p-4 overflow-y-auto" style="max-height: 700px">
                <form method="POST" id="approveForm-{{ $tournee->id }}"
                    action="{{ route('tournee_approves.approve', $tournee->id) }}">
                    @csrf
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="relative z-0 mb-4 w-full group">
                            <input type="text" name="comment"  id="comment-{{ $tournee->id }}"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="" />
                            <label for="comment-{{ $tournee->id }}"
                                class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                                {{ __('Comment') }}
                            </label>
                            @error('comment')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-end items-center space-x-2 rounded-b ">
                        <div>
                            <button type="button" onclick="submitForm('review', {{ $tournee->id }})"
                                class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                {{ __('Review (Back to Draft)') }}
                            </button>
                        </div>
                        <div>
                            <button type="button" onclick="submitForm('approve', {{ $tournee->id }})"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                                >{{ __('Approve (GO Next!)') }}
                            </button>
                        </div>
                        <div>
                            <button  type="button" onclick="submitForm('reject', {{ $tournee->id }})"
                                class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                                >{{ __('Reject (End process)') }}
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="action" id="action-{{ $tournee->id }}" value="">
                </form>
            </div>
        </div>
    </div>
</div>
<script>
function submitForm(action, missionOrderId) {
    const form = document.getElementById(`approveForm-${missionOrderId}`);
    const commentInput = document.getElementById(`comment-${missionOrderId}`);
    const actionInput = document.getElementById(`action-${missionOrderId}`);
console.log(form, commentInput, actionInput);
    // Reset any previous error styling
    commentInput.classList.remove('border-red-500');
    commentInput.classList.add('border-gray-300');

    // Set the action value
    actionInput.value = action;

    // Only require comment if action is 'reject'
    if (action === 'reject') {
        commentInput.required = true;
        if (!commentInput.value.trim()) {
            // Add red border to indicate error
            commentInput.focus();
            commentInput.classList.remove('border-gray-300');
            commentInput.classList.add('border-red-500');
            return false;
        }
    } else {
        commentInput.required = false;
    }

    // Submit the form
    form.submit();
}
</script>

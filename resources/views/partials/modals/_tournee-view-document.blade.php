<div id="viewDocumentModal-{{ $expense->id }}" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-auto fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full mt-40 max-w-2xl h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow p-2">
            <!-- Modal header -->
            <div class="flex justify-between items-center p-2 rounded-t border-b">
                <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left blue-color">
                    {{ __('Voir le document') }}
                </div>
                <div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-toggle="viewDocumentModal-{{ $expense->id }}">
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
            <div class="p-4 overflow-y-auto" style="max-height: 80vh">
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="relative mx-auto w-full">
                        @if(pathinfo($expense->expense_document, PATHINFO_EXTENSION) === 'pdf')
                            <!-- PDF Viewer -->
                            <div class="pdf-viewer-container h-screen">
                                <embed id="pdfViewer-{{ $expense->id }}"
                                    src="{{ asset('storage/'.$expense->expense_document) }}"
                                    type="application/pdf"
                                    class="w-full h-full min-h-[70vh] border border-gray-200 rounded-lg">
                                <div class="flex justify-between items-center mt-2">
                                    <div class="text-sm text-gray-600">
                                        {{ basename($expense->expense_document) }}
                                    </div>
                                    <a href="{{ asset('storage/'.$expense->expense_document) }}"
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-800 text-sm">
                                        {{ __('Ouvrir dans un nouvel onglet') }}
                                    </a>
                                </div>
                            </div>
                        @else
                            <!-- Image Viewer with Zoom -->
                            <div class="image-viewer-container overflow-hidden">
                                <img id="imageModal-{{ $expense->id }}"
                                    src="{{ asset('storage/'.$expense->expense_document) }}"
                                    alt="Expense Document"
                                    class="mx-auto max-w-full max-h-[70vh] object-contain cursor-zoom-in transition-transform duration-300 origin-center">
                                <div class="flex justify-between items-center mt-2">
                                    <div class="text-sm text-gray-600">
                                        {{ basename($expense->expense_document) }}
                                    </div>
                                    <a href="{{ asset('storage/'.$expense->expense_document) }}"
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-800 text-sm">
                                        {{ __('Voir en plein écran') }}
                                    </a>
                                </div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const imageModal = document.getElementById('imageModal-{{ $expense->id }}');
                                    let scale = 1;
                                    let isDragging = false;
                                    let startX, startY, translateX = 0, translateY = 0;

                                    // Zoom functionality
                                    imageModal.addEventListener('wheel', (e) => {
                                        e.preventDefault();
                                        const delta = e.deltaY > 0 ? -0.1 : 0.1;
                                        scale = Math.min(Math.max(0.5, scale + delta), 3);
                                        updateTransform();
                                    });

                                    // Double click to reset zoom
                                    imageModal.addEventListener('dblclick', () => {
                                        scale = 1;
                                        translateX = 0;
                                        translateY = 0;
                                        updateTransform();
                                    });

                                    // Drag functionality
                                    imageModal.addEventListener('mousedown', (e) => {
                                        if (scale > 1) {
                                            isDragging = true;
                                            startX = e.clientX - translateX;
                                            startY = e.clientY - translateY;
                                            imageModal.style.cursor = 'grabbing';
                                        }
                                    });

                                    document.addEventListener('mousemove', (e) => {
                                        if (!isDragging) return;
                                        translateX = e.clientX - startX;
                                        translateY = e.clientY - startY;
                                        updateTransform();
                                    });

                                    document.addEventListener('mouseup', () => {
                                        isDragging = false;
                                        imageModal.style.cursor = scale > 1 ? 'grab' : 'zoom-in';
                                    });

                                    function updateTransform() {
                                        imageModal.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
                                        imageModal.style.cursor = scale > 1 ? 'grab' : 'zoom-in';
                                    }
                                });
                            </script>
                        @endif
                    </div>
                </div>
                <div class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                    <div>
                        <button data-modal-toggle="viewDocumentModal-{{ $expense->id }}" type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                            {{ __('Fermer') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

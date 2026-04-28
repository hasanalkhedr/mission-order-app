@extends('layouts.app')
@section('title', $tournee->order_number . '-' . $tournee->employee->first_name . ' ' . $tournee->employee->last_name)
@section('content')
<div id="report-content">
    <div class="report-page" style="width: 210mm; height: 290mm; margin: 0 auto; padding: 8mm; box-sizing: border-box;">
        <!-- Header -->
        <div class="flex justify-between items-start mb-2">
            <x-application-logo class="h-16" />
            <div class="text-right">
                <p>New Delhi, {{ $tournee->order_date->format('d/m/Y') }}</p>
            </div>
        </div>
        <!-- Title -->
        <h1 class="text-2xl font-bold text-center mb-2">ORDRE DE TOURNEE {{ $tournee->order_number }}</h1>
        <table class="table-auto w-full text-left">
            <thead>
                <tr class="bg-blue-200">
                    <th colspan="2" class="px-4">Tourneeary</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="w-1/3">Nom, Prénom :</td>
                    <td class="w-2/3">{{ $tournee->employee->first_name }} {{ $tournee->employee->last_name }}
                    </td>
                </tr>
                <tr>
                    <td class="w-1/3">Fonction :</td>
                    <td class="w-2/3">{{ $tournee->employee->position }}</td>
                </tr>
                <tr>
                    <td class="w-1/3">Ville de résidence administrative:</td>
                    <td class="w-2/3">{{ $tournee->employee->administrativ_residence }}</td>
                </tr>
            </tbody>
        </table>
        <table class="table-auto w-full text-left">
            <thead>
                <tr class="bg-blue-200">
                    <th colspan="2" class="px-4">Tournee</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="w-1/5">Objet/Motif:</td>
                    <td class="w-4/5">{{ $tournee->purpose }}</td>
                </tr>
                @if($tournee->conge)
                <tr>
                    <td class="w-1/3">Conge pendant mission:</td>
                    <td class="w-2/3">{{ $tournee->conge }}</td>
                </tr>
                @endif
                <tr>
                    <td colspan="2" class="w-full">
                        <h2 class="text-md text-center justify-center text-blue-500">Destinations du Tournee</h2>
                        <table class="w-full text-xs text-center text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <th class="cursor-pointer py-[2px] px-[2px] blue-color">#</th>
                                <th class="cursor-pointer py-[2px] px-[2px] blue-color">Lieu de départ</th>
                                <th class="cursor-pointer py-[2px] px-[2px] blue-color">Date et Heure d'arrivée lieu de
                                    mission
                                </th>
                                <th class="cursor-pointer py-[2px] px-[2px] blue-color">Lieu de mission</th>
                                <th class="cursor-pointer py-[2px] px-[2px] blue-color">Date et Heure de départ lieu de
                                    mission
                                </th>
                            </thead>
                            <tbody>
                                @foreach ($tournee->tourneeDestinations as $index => $destination)
                                    <tr class="bg-white hover:bg-gray-50">
                                        <td
                                            class="border-b py-[2px] px-[2px] font-bold text-gray-900 whitespace-nowrap cursor-pointer">
                                            {{ $index }}</td>
                                        <td
                                            class="border-b py-[2px] px-[2px] font-bold text-gray-900 whitespace-nowrap cursor-pointer">
                                            {{ $destination->departure_location }}</td>
                                        <td
                                            class="border-b py-[2px] px-[2px] font-bold text-gray-900 whitespace-nowrap cursor-pointer">
                                            {{ $destination->start_date->format('d/m/Y') }} at
                                            {{ \Carbon\Carbon::parse($destination->start_time)->format('H:i') }}</td>
                                        <td
                                            class="border-b py-[2px] px-[2px] font-bold text-gray-900 whitespace-nowrap cursor-pointer">
                                            {{ $destination->arrive_location }}</td>
                                        <td
                                            class="border-b py-[2px] px-[2px] font-bold text-gray-900 whitespace-nowrap cursor-pointer">
                                            {{ $destination->end_date->format('d/m/Y') }} at
                                            {{ \Carbon\Carbon::parse($destination->end_time)->format('H:i') }}</td>
                                   </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        @if($tournee->has_weekend)
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 py-1 px-2 mb-1">
            <p>Ce Calendrier inclu un ou plusieurs jours de Weekend.</p>
        </div>
        @endif
        <table class="table-auto w-full text-left">
            <thead>
                <tr class="bg-blue-200">
                    <th class="px-4">Pays de Tournee</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="w-full">
                        {{ $tournee->bareme->pays }}
                        (Montant:{{ $tournee->bareme->pays_per_day . ' ' . $tournee->bareme->currency }}:
                         Repas:{{ $tournee->bareme->meal_cost }} - Hebergement:{{ $tournee->bareme->accomodation_cost }})
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table-auto w-full text-left">
            <thead>
                <tr class="bg-blue-200">
                    <th colspan="2" class="px-4">Frais de tournee</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="w-5/6">Demande d'avance:</td>
                    <td class="w-1/6">{{ $tournee->advance >0 ? number_format($tournee->advance,2,'.',' ') : 'NON' }}</td>
                </tr>
                <tr>
                    <td class="w-5/6">Prise en charge des frais de transport (Avion, Train):</td>
                    <td class="w-1/6">{{ $tournee->charge == 1 ? 'OUI' : 'NON' }}</td>
                </tr>
                <tr>
                    <td class="w-5/6">Prise en charge des frais de transport (Taxi/Uber, Transport public, etc..):</td>
                    <td class="w-1/6">{{ $tournee->charge1 == 1 ? 'OUI' : 'NON' }}</td>
                </tr>
                <tr>
                    <td class="w-5/6">Prise en charge frais d'hébergement:</td>
                    <td class="w-1/6">{{ $tournee->ijm == 1 ? 'OUI' : 'NON' }}</td>
                </tr>
                <tr>
                    <td class="w-5/6">Prise en charge frais de repas:</td>
                    <td class="w-1/6">{{ $tournee->repas == 1 ? 'OUI' : 'NON' }}</td>
                </tr>
                {{-- <tr>
                    <td colspan="2" class="w-full">{{ $tournee->budget_text }}</td>
                </tr> --}}
            </tbody>
        </table>
        <!-- Expense Table -->
        <table class="table-auto w-full text-left">
            <thead>
                <tr class="bg-blue-200">
                    <th class="px-4">Dépenses prévues supplémentaires</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="w-full">
                        <div class="flex flex-col">
                            <div class="overflow-x-auto">
                                <div class="w-full inline-block align-middle">
                                    <div class="overflow-hidden">
<table class="w-full divide-y divide-gray-200 border border-gray-300">
    <thead>
        <tr>
            <th scope="col"
                class="px-1 py-[2px] text-center text-xs font-medium text-gray-500 uppercase">
                Type</th>
            <th scope="col"
                class="px-1 py-[2px] text-center text-xs font-medium text-gray-500 uppercase">
                Nature de la dépense</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($tournee->expenses->whereNotIn('transport_type', ['Transport Avion', 'Transport en commun / Taxi(uber)']) as $expense)
            <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                <td
                    class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                    {{__($expense->type)}}
                </td>
                <td
                    class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                    @if ($expense->type === 'transport')
                        {{ __('expense.transport_types.' . $expense->transport_type) }}<br/>
                        @if($expense->transport_type === 'car_rental_with_driver')
                        ({{$expense->passenger == 1 ? __('passenger').',' : ''}}
                        {{$expense->distance == 1 ? __('distance').',' : ''}}<br/>
                        {{$expense->material == 1 ? __('material').',' : ''}}
                        {{$expense->visits == 1 ? __('visits') : ''}})
                        @endif
                    @else
                        {{ $expense->meal_location }}
                        {{ $expense->description }}
                    @endif
                </td>
            </tr>
        @empty
            <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                <td colspan="6"
                    class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs font-medium text-gray-800">
                    {{ __('No Expenses Found') }}
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        @if($tournee->description && $tournee->description != '')
        <table class="table-auto w-full text-left">
            <thead>
                <tr class="bg-blue-200">
                    <th colspan="2" class="px-4">Observations</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" class="w-full">{{ $tournee->description }}
                    </td>
                </tr>
            </tbody>
        </table>
        @endif
        <table class="table-auto w-full text-left">
            <thead>
                <tr class="bg-blue-200">
                    <th colspan="2" class="px-4">Signature de l'autorité compétente</th>
                </tr>
            </thead>
            {{-- <tbody>
                <tr>
                    <td colspan="2" class="w-full px-28 pt-2 pb-2 justify-end items-end text-right">
                        <span
                            class="font-bold text-lg w-24 text-center">{{ Str::upper($director->first_name) . ' ' . $director->last_name }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="w-full px-24 pt-0 pb-40 text-right">
                        <span class="font-light text-md  w-16 text-center">COCAC - Directrice de l'IFI</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="w-full px-24 pt-0 pb-40 text-right">
                        <div class="flex">
                            <div class="w-2/3"></div>
                            <div class="w-1/3">
                                <span class="font-light text-md text-right">
                                    @if ($director && $director->signature && $director->signature->status == 'approved')
                                        <img src="{{ asset('storage/' . $director->signature->signature_path) }}"
                                            class="w-60 h-auto max-w-60">
                                    @endif
                                </span>
                            </div>
                        </div>

                    </td>
                </tr>
            </tbody> --}}
        </table>
        <table class="table-auto w-full text-left">
            <tbody>
                <tr>
                    <td class="px-2 py-[1px] w-1/3">
                        <span class="font-bold text-lg text-center">Agent: </span>
                    </td>
                    <td class="px-2 py-[1px] w-1/3">
                        <span class="font-bold text-lg text-center">Chef de Service: </span>
                    </td>
                    <td class="px-2 py-[1px] w-1/3">
                        <span class="font-bold text-lg text-center">Ordonnateur: </span>
                    </td>
                </tr>
                <tr>
                    <td class="w-1/3 px-2 py-[1px]">
                        <span class="font-light text-md text-center">Date de Soumission</span>
                    </td>
                    <td class="w-1/3 px-2 py-[1px]">
                        <span class="font-light text-md text-center">Date de Validation</span>
                    </td>
                    <td class="w-1/3 px-2 py-[1px]">
                        <span class="font-light text-md text-center">Date de Validation</span>
                    </td>
                </tr>
                <tr>
                    <td class="w-1/3 px-2 py-[1px]">
                        <span class="font-light text-md text-center">{{$tournee->order_date->format('d/m/Y')}}</span>
                    </td>
                    <td class="w-1/3 px-2 py-[1px]">
                        <span class="font-light text-md text-center">
                            {{$tournee->getTourneeAprroves()->where('approval_role', 'Chef de Service')->first() ?
                                $tournee->getTourneeAprroves()->where('approval_role', 'Chef de Service')->first()->created_at->format('d/m/Y') : ''}}
                        </span>
                    </td>
                    <td class="w-1/3 px-2 py-[1px]">
                        <span class="font-light text-md text-center">{{$tournee->getTourneeAprroves()->where('approval_role', 'Ordonnateur')->first() ?
                            $tournee->getTourneeAprroves()->where('approval_role', 'Ordonnateur')->first()->created_at->format('d/m/Y') : ''}}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- Action Buttons -->
<div class="flex justify-center space-x-4 mb-8 no-print">
    <button id="download-pdf"
        class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-200 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
            </path>
        </svg>
        {{ __('Save as PDF file') }}
    </button>
    @if (auth()->user()->employee->hasRole('supervisor') && in_array($tournee->employee->department_id,App\Models\Department::where('manager_id', Auth::user()->employee->id)->pluck('id')->toArray()) && $tournee->status === 'sup_approve')
        <form method="POST" action="{{ route('tournee_approves.approve', $tournee->id) }}">
            @csrf
            <button
                    class="bg-white text-center hover:bg-white text-blue-800 border border-blue-300 font-bold py-3 px-6 rounded-lg shadow-md transition duration-200 flex items-center justify-center w-48">
                    <svg class="h-6 w-6 text-blue-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Valider') }}
                </button>
            <input type="hidden" name="action" value="approve">
        </form>
        <button
                class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-200 flex items-center justify-center w-48"
                type="button" data-modal-toggle="approveModal-{{ $tournee->id }}">
                {{-- {{ __('AVIS DU SUPÉRIEUR HIÉRARCHIQUE') }} --}}
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                </svg>
                Retour Agent
            </button>
    @elseif (auth()->user()->employee->hasRole('sg') && $tournee->status === 'sg_approve' && Auth::user()->employee->id != $tournee->employee_id)
        <form method="POST" action="{{ route('tournee_approves.approve', $tournee->id) }}">
            @csrf
            <button
                    class="bg-white text-center hover:bg-white text-blue-800 border border-blue-300 font-bold py-3 px-6 rounded-lg shadow-md transition duration-200 flex items-center justify-center w-48">
                    <svg class="h-6 w-6 text-blue-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Valider') }}
                </button>
            <input type="hidden" name="action" value="approve">
        </form>
        <button
                class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-200 flex items-center justify-center w-48"
                type="button" data-modal-toggle="approveModal-{{ $tournee->id }}">
                {{-- {{ __('Approve or Reject') }} --}}
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                </svg>
                Retour Agent
            </button>
    @endif
    @include('partials.modals._approve-tournee')
</div>
<style>
    @media print {
        body {
            margin: 0 !important;
            padding: 0 !important;
            background: white !important;
        }

        .report-page {
            width: 210mm !important;
            height: 297mm !important;
            margin: 0 auto !important;
            padding: 8mm !important;
            transform: scale(1) !important;
        }

        .document-page {
            page-break-before: always !important;
            width: 210mm !important;
            height: 297mm !important;
        }

        .no-print {
            display: none !important;
        }
    }

    /* Screen styles */
    .report-page {
        background: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin: 20px auto;
    }

    .document-page {
        background: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin: 20px auto;
    }

    .btn-blue {
        background: #2563eb;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.375rem;
        font-weight: bold;
        display: flex;
        align-items: center;
    }

    .btn-green {
        background: #059669;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.375rem;
        font-weight: bold;
        display: flex;
        align-items: center;
    }
</style>
<script>
    function renderPDF(pdfUrl, containerId) {
        const container = document.getElementById(containerId);

        pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
            // Get first page
            pdf.getPage(1).then(function(page) {
                const viewport = page.getViewport({
                    scale: 1.0
                });
                const canvas = document.createElement('canvas');
                canvas.className = 'pdf-page';
                container.appendChild(canvas);

                // Calculate scale to fit container width
                const desiredWidth = container.clientWidth;
                const scale = desiredWidth / viewport.width;
                const scaledViewport = page.getViewport({
                    scale
                });

                // Set canvas dimensions
                canvas.height = scaledViewport.height;
                canvas.width = scaledViewport.width;

                // Render PDF page
                page.render({
                    canvasContext: canvas.getContext('2d'),
                    viewport: scaledViewport
                });
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all PDF viewers
        @foreach ($tournee->expenses as $expense)
            @if (pathinfo($expense->expense_document, PATHINFO_EXTENSION) === 'pdf')
                renderPDF(
                    "{{ asset('storage/public/' . $expense->expense_document) }}",
                    "pdf-viewer-{{ $expense->id }}"
                );
            @endif
        @endforeach
    });

    document.getElementById("download-pdf").addEventListener("click", async function() {
        const element = document.getElementById('report-content');
        const loading = createLoadingIndicator();

        try {
            await generatePDF(element);
        } catch (error) {
            alert("Failed to generate PDF. Please try printing instead (Ctrl+P).");
            alert(error);
        } finally {
            loading.remove();
        }
    });

    function createLoadingIndicator() {
        const loading = document.createElement('div');
        loading.style.position = 'fixed';
        loading.style.top = '0';
        loading.style.left = '0';
        loading.style.width = '100%';
        loading.style.height = '100%';
        loading.style.backgroundColor = 'rgba(0,0,0,0.7)';
        loading.style.color = 'white';
        loading.style.display = 'flex';
        loading.style.flexDirection = 'column';
        loading.style.justifyContent = 'center';
        loading.style.alignItems = 'center';
        loading.style.zIndex = '9999';
        loading.innerHTML = `
            <div style="font-size: 24px; margin-bottom: 20px;">Generating PDF...</div>
            <div style="width: 50%; height: 20px; background: #555; border-radius: 10px;">
                <div id="progress-bar" style="width: 0%; height: 100%; background: #4CAF50; border-radius: 10px;"></div>
            </div>
            <p id="progress-text" style="margin-top: 10px;">Initializing...</p>
        `;
        document.body.appendChild(loading);
        return loading;
    }

    function updateProgress(percentage, message) {
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');
        if (progressBar) progressBar.style.width = percentage + '%';
        if (progressText) progressText.textContent = message;
    }

    async function generatePDF(element, customOptions = {}) {
        try {
            updateProgress(10, "Preparing content...");

            const defaultOptions = {
                margin: 1,
                filename: `Tournee-{{ $tournee->order_number }}-{{ $tournee->employee->first_name }}_{{ $tournee->employee->last_name }}.pdf`,
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 3,
                    useCORS: true,
                    allowTaint: true,
                    scrollX: 0,
                    scrollY: 0,
                    onclone: (clonedDoc) => {
                        clonedDoc.querySelectorAll('.no-print').forEach(el => el.remove());
                    },
                    logging: true
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                },
                pagebreak: {
                    before: '.document-page'
                }
            };

            const options = {
                ...defaultOptions,
                ...customOptions
            };

            updateProgress(30, "Generating PDF...");

            // Generate PDF and open in new tab instead of downloading
            const pdf = await html2pdf().set(options).from(element).outputPdf('blob');

            updateProgress(90, "Opening PDF...");

            // Create blob URL with proper filename and open in new tab
            const blob = new Blob([pdf], { type: 'application/pdf' });
            const blobUrl = URL.createObjectURL(blob);

            // Open in new tab
            const newTab = window.open(blobUrl, '_blank');

            if (newTab) {
                    // Add download functionality to the new window
                    newTab.addEventListener('load', function() {
                        // Create a download button in the new window
                        const downloadBtn = newTab.document.createElement('button');
                        downloadBtn.textContent = 'Download PDF';
                        downloadBtn.style.cssText = `
                            position: fixed;
                            top: 10px;
                            left: 25%;
                            transform: translateX(-75%);
                            z-index: 9999;
                            padding: 10px 20px;
                            background: #007bff;
                            color: white;
                            border: none;
                            border-radius: 5px;
                            cursor: pointer;
                            font-size: 14px;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                        `;

                        downloadBtn.onclick = function() {
                            const downloadLink = newTab.document.createElement('a');
                            downloadLink.href = blobUrl;
                            downloadLink.download = filename;
                            downloadLink.style.display = 'none';
                            newTab.document.body.appendChild(downloadLink);
                            downloadLink.click();
                            newTab.document.body.removeChild(downloadLink);
                        };

                        newTab.document.body.appendChild(downloadBtn);
                    });
                }

                // Clean up the blob URL after a reasonable time
                setTimeout(() => {
                    URL.revokeObjectURL(blobUrl);
                }, 30000);

                updateProgress(100, "Done!");

                // Keep success message for a moment
                setTimeout(() => {
                    updateProgress(0, "");
                }, 2000);
        } catch (error) {
            console.error("PDF generation failed:", error);
            updateProgress(0, "Failed to generate PDF");
            throw error; // Re-throw if you want calling code to handle it
        }
    }
</script>
@endsection

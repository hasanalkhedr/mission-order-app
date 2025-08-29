@extends('layouts.app')
@section('title', $tournee->order_number . '-' . $tournee->employee->first_name . ' ' . $tournee->employee->last_name)
@section('content')
    <div id="report-content">
        <div class="report-page" style="width: 210mm; height: 297mm; margin: 0 auto; padding: 8mm; box-sizing: border-box;">
            <!-- Header -->
            <div class="flex justify-between items-start mb-2">
                <x-application-logo class="h-16" />
                <div class="text-right">
                    <p>New Delhi, {{ $tournee->memor_date->format('d/m/Y') }}</p>
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-2xl font-bold text-center mb-2">MÉMOIRE DE FRAIS</h1>

            <!-- Mission Info -->
            <table class="table-auto w-full text-left">
                <thead>
                    <tr class="bg-blue-200">
                        <th colspan="4" class="px-4">Tournee</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="w-1/3">Identité du tourneeaire :</td>
                        <td colspan="3" class="w-2/3">{{ $tournee->employee->first_name }}
                            {{ $tournee->employee->last_name }}, {{ $tournee->employee->position }}</td>
                    </tr>
                    <tr>
                        <td class="w-1/3">Ordre de tournee {{ $tournee->order_date->format('Y') }}
                        </td>
                        <td colspan="3" class="w-2/3">{{ $tournee->order_number }}</td>
                    </tr>
                    <tr>
                        <td class="w-1/3">Objet de la tournee :</td>
                        <td colspan="3" class="w-2/3">{{ $tournee->purpose }}</td>
                    </tr>
                    <tr>
                        <td class="w-1/4">Lieu de la tournee :</td>
                        <td class="w-1/4">{{ $tournee->firstDestination->arrive_location }}</td>
                        <td class="w-1/4">Pays :</td>
                        <td class="w-1/4">{{ $tournee->bareme->pays }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="w-full">
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
                                                {{ $destination->start_time }}</td>
                                            <td
                                                class="border-b py-[2px] px-[2px] font-bold text-gray-900 whitespace-nowrap cursor-pointer">
                                                {{ $destination->arrive_location }}</td>
                                            <td
                                                class="border-b py-[2px] px-[2px] font-bold text-gray-900 whitespace-nowrap cursor-pointer">
                                                {{ $destination->end_date->format('d/m/Y') }} at
                                                {{ $destination->end_time }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr class="bg-gray-200 h-4">
                        <td colspan="4"></td>
                    </tr>
                    <tr>
                        <td class="w-1/4">Nuitées à déduire des IJM :</td>
                        <td class="w-1/4">{{ $tournee->no_ded_accomodation }}</td>
                        <input type="hidden" id="no_ded_accomodation" value="{{ $tournee->no_ded_accomodation }}">
                        <td class="w-1/4">Repas à déduire :</td>
                        <td class="w-1/4">{{ $tournee->no_ded_meals }}</td>
                        <input type="hidden" id="no_ded_meals" value="{{ $tournee->no_ded_meals }}">
                    </tr>
                    <tr>
                        <td class="w-1/4">Avance (Roupie indienne) :</td>
                        <td class="w-1/4">{{ $tournee->advance }}</td>
                        <td class="w-1/4">Restau adm. :</td>
                        <td class="w-1/4">0</td>
                    </tr>
                </tbody>
            </table>

            <!-- IJM Table -->
            <table class="table-auto w-full text-left px-2">
                <thead>
                    <tr class="bg-blue-200">
                        <th colspan="2" class="px-4">Calcul des Indemnités Journalières de Tournee</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="w-full px-[2px]">
                            @include('partials.modals._tournee-ijmtable')
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Expense Table -->
            <table class="table-auto w-full text-left">
                <thead>
                    <tr class="bg-blue-200">
                        <th class="px-4">Remboursement de frais de transport et frais divers sur justificatifs</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="w-full">
                            <div class="flex flex-col">
                                <div class="-m-1.5 overflow-x-auto">
                                    <div class="p-[2px] min-w-full inline-block align-middle">
                                        <div class="overflow-hidden">
                                            <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                                                <thead>
                                                    <tr>
                                                        <th scope="col"
                                                            class="px-1 py-[2px] text-center text-xs font-medium text-gray-500 uppercase">
                                                            Type</th>
                                                        <th scope="col"
                                                            class="px-1 py-[2px] text-center text-xs font-medium text-gray-500 uppercase">
                                                            Nature de
                                                            la dépense</th>
                                                        {{-- <th scope="col"
                                                            class="px-1 py-[2px] text-center text-xs font-medium text-gray-500 uppercase">
                                                            Détails</th> --}}
                                                        <th scope="col"
                                                            class="px-1 py-[2px] text-center text-xs font-medium text-gray-500 uppercase">
                                                            Date
                                                            dépense</th>
                                                        <th scope="col"
                                                            class="px-1 py-[2px] text-center text-xs font-medium text-gray-500 uppercase">
                                                            Montant</th>
                                                        <th scope="col"
                                                            class="px-1 py-[2px] text-center text-xs font-medium text-gray-500 uppercase">
                                                            Devise</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($tournee->expenses as $expense)
                                                        <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                                                            <!-- Type -->
                                                            <td
                                                                class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                                                               {{__($expense->type)}}
                                                            </td>
                                                            <!-- Description -->
                                                            <td
                                                                class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                                                                @if ($expense->type === 'transport')
                                                                    {{ __('expense.transport_types.' . $expense->transport_type) }}
                                                                @else
                                                                    {{ $expense->meal_location }}
                                                                    {{ $expense->description }}
                                                                @endif
                                                            </td>

                                                            <!-- Details -->
                                                            {{-- <td
                                                                class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                                                                @if ($expense->type === 'transport')
                                                                    {{ __('expense.transport_types.' . $expense->transport_type) }}
                                                                    @if ($expense->transport_details)
                                                                        <span
                                                                            class="text-gray-500 block text-xxs">{{ Str::limit($expense->transport_details, 15) }}</span>
                                                                    @endif
                                                                @else
                                                                    {{ Str::limit($expense->meal_location, 15) }}
                                                                    <span
                                                                        class="text-gray-500 block text-xxs">{{ $expense->meal_participants }}
                                                                        pers.</span>
                                                                @endif
                                                            </td> --}}
                                                            <!-- Date -->
                                                            <td
                                                                class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                                                                {{ $expense->expense_date->format('d/m/Y') }}
                                                            </td>

                                                            <!-- Amount -->
                                                            <td
                                                                class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                                                                {{ number_format($expense->amount, 2) }}
                                                            </td>

                                                            <!-- Currency -->
                                                            <td
                                                                class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                                                                {{ $expense->currency }}
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
                                                <tfoot>
                                                    @forelse ($tournee->getExpensesByCurrency() as $currency=>$currencyAmount)
                                                        <tr>
                                                            <th scope="col" colspan="3"></th>
                                                            <th scope="col"
                                                                class="px-1 py-[2px] text-center text-xs font-bold text-blue-600 uppercase border border-gray-500">
                                                                SOMME
                                                            </th>
                                                            <th scope="col"
                                                                class="px-1 py-[2px] text-center text-xs font-bold text-blue-600 uppercase border border-gray-500">
                                                                {{ number_format($currencyAmount, 2) }}
                                                            </th>
                                                            <th scope="col"
                                                                class="px-1 py-[2px] text-center text-xs font-bold text-blue-600 uppercase border border-gray-500">
                                                                {{ $currency }}
                                                            </th>
                                                        </tr>
                                                    @empty
                                                    @endforelse
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Totals Table -->
            <table class="table-auto w-full text-left">
                <thead>
                    <tr class="bg-blue-200">
                        <th colspan="2" class="px-4">Montant total du remboursement (IJM + FRAIS DIVERS - AVANCE)
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="w-full py-1" colspan="2">
                            <table class="border border-gray-500 table-auto text-center">
                                <tr>
                                    <td rowspan="2" class="w-1/12 border border-gray-500 font-bold">Totaux</td>
                                    <td class="border border-gray-500 w-3/12">IJM</td>
                                    <td class="border border-gray-500 w-3/12">Frais divers</td>
                                    <td class="border border-gray-500 w-2/12">Avance</td>
                                    <td class="border border-gray-500 w-3/12">Net à payer</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-500 w-3/12">{{ $tournee->total_amount }}</td>
                                    <td class="border border-gray-500 w-3/12">
                                        <ul>
                                            @forelse ($tournee->getExpensesByCurrency() as $currency=>$currencyAmount)
                                                <li>{{ $currencyAmount }} {{ $currency }}</li>
                                            @empty
                                                <li>0.00</li>
                                            @endforelse
                                        </ul>
                                    </td>
                                    <td class="border border-gray-500 w-2/12">{{ $tournee->advance }}</td>
                                    <td class="border border-gray-500 w-3/12">
                                        <ul>
                                            @forelse ($tournee->getMemoireTotals() as $currency=>$currencyAmount)
                                                <li>{{ $currencyAmount }} {{ $currency }}</li>
                                            @empty
                                                <li>0.00</li>
                                            @endforelse
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                        </td>
                </tbody>
            </table>

            <!-- Net Total Table -->
            <table class="table-auto w-full text-left">
                @if (count($tournee->getMemoireTotals()) === 1)
                    <tr>
                        <td class="w-1/3 py-[1px]">ARRETE ET LIQUIDE LA SOMME DE :</td>
                        @foreach ($tournee->getMemoireTotals() as $currency => $currencyAmount)
                            <td class="w-2/3 py-[1px] font-bold text-red-600">{{ $currencyAmount }} {{ $currency }}
                                <span class="font-normal px-5"> arrondi à </span>{{ round($currencyAmount) }}
                                {{ $currency }}
                            </td>
                        @endforeach
                    </tr>
                @else
                    @foreach ($tournee->getMemoireTotals() as $currency => $currencyAmount)
                        @if ($loop->first)
                            <tr>
                                <td class="w-1/4 py-[1px]" rowspan="{{ count($tournee->getMemoireTotals()) }}">
                                    ARRETE ET LIQUIDE LA SOMME DE:</td>
                                <td class="w-1/4 py-[1px]">{{ $currencyAmount }} {{ $currency }}</td>
                                <td class="w-1/4 py-[1px]"> <span class="font-normal px-5">
                                        arrondi à </span></td>
                                <td class="w-1/4 py-[1px]  font-bold text-red-600">{{ round($currencyAmount) }}
                                    {{ $currency }}</td>
                            </tr>
                        @else
                            <tr>
                                <td class="w-1/4 py-[1px]">{{ $currencyAmount }} {{ $currency }}</td>
                                <td class="w-1/4 py-[1px]"> <span class="font-normal px-5">
                                        arrondi à </span></td>
                                <td class="w-1/4 py-[1px] font-bold text-red-600">{{ round($currencyAmount) }}
                                    {{ $currency }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            </table>

            <!-- Signatures -->
            <table class="table-auto w-full text-left">
                <thead>
                    <tr class="bg-blue-200">
                        <th class="w-5/12 pl-4">Signature du bénéficiaire</th>
                        <th class="w-7/12 pl-12 ">Signature de l'autorité compétente</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="w-7/12">
                            <span>Atteste l'effectitivité des dépenses exposées</span>
                        </td>
                        <td class="w-5/12 text-center">
                            <span>Certifié exact,</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-8/12 pb-2">
                            <span>ci-dessus, en demande le remboursement,</span>
                        </td>
                        <td class="w-4/12 text-center">
                            <span></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-5/12">
                            <span></span>
                        </td>
                        <td class="w-7/12 text-center">
                            <span class="font-bold text-lg w-24 text-center">{{Str::upper($director->first_name) . ' ' . $director->last_name}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-6/12">
                            <span class="font-light text-md  w-16 text-center">
                                {{ $tournee->employee->first_name }} {{ $tournee->employee->last_name }},
                                {{ $tournee->employee->position }}
                            </span>
                        </td>
                        <td class="w-6/12 text-center">
                            <span class="font-light text-md  w-16 text-center">COCAC - Directrice de l'IFI</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-6/12">
                            <div>
                                <span class="font-light text-md text-right">
                                    @if ($tournee->employee->signature && $tournee->employee->signature->status == 'approved')
                                        <img src="{{ asset('storage/' . $tournee->employee->signature->signature_path) }}"
                                            class="w-60 h-auto max-w-60">
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="w-6/12 text-center">
                            <div>
                                <span class="font-light text-md text-right">
                                    @if ($director && $director->signature && $director->signature->status == 'approved')
                                        <img src="{{ asset('storage/' . $director->signature->signature_path) }}"
                                            class="w-60 h-auto max-w-60">
                                    @endif
                                </span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Documents - each will be on separate pages -->
        @foreach ($tournee->expenses as $expense)
            <div class="document-page" style="page-break-before: always; width: 210mm;">
                <h4 class="text-center font-bold mb-1">Document: {{ $expense->description }}</h4>
                <div class="flex justify-center">
                    @if (pathinfo($expense->expense_document, PATHINFO_EXTENSION) === 'pdf')
                        <div id="pdf-viewer-{{ $expense->id }}" class="pdf-container"
                            style="width: 100%; height: 240mm;"></div>
                    @else
                        <img src="{{ asset('storage/' . $expense->expense_document) }}"
                            style="max-width: 100%; max-height: 240mm; object-fit: contain;" alt="Expense Document">
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    <!-- Action Buttons -->
    <div class="flex justify-center space-x-4 mb-8 no-print">
        <button onclick="window.print()"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-200 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                </path>
            </svg>
            {{ __('Print Report') }}
        </button>
        <button id="download-pdf"
            class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-200 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
            </svg>
            {{ __('Save as PDF file') }}
        </button>
        @if((auth()->user()->employee->hasRole('sg') && $tournee->memor_status === 'sg_approve') || (auth()->user()->employee->hasRole('controller') && $tournee->memor_status === 'controller_approve'))
            <button class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center hover:text-gray-900"
                type="button" data-modal-toggle="approveModal-{{ $tournee->id }}">
                {{ __('Approve or Reject') }}
            </button>
        @endif
        @include('partials.modals._tournee-approve-memoier')
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
                        "{{ asset('storage/' . $expense->expense_document) }}",
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
                    filename: `Mémoire-{{ $tournee->order_number }}-{{ $tournee->employee->first_name }}_{{ $tournee->employee->last_name }}.pdf`,
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2,
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

                // Create a promise that resolves when the PDF is generated
                await new Promise((resolve, reject) => {
                    html2pdf()
                        .set(options)
                        .from(element)
                        .save()
                        .then(() => {
                            updateProgress(90, "Finalizing PDF...");
                            setTimeout(() => {
                                updateProgress(100, "Done!");
                                resolve();
                            }, 500);
                        })
                        .catch(reject);
                });
            } catch (error) {
                console.error("PDF generation failed:", error);
                updateProgress(0, "Failed to generate PDF");
                throw error; // Re-throw if you want calling code to handle it
            }
        }
    </script>
@endsection

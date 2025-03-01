@extends('layouts.app')
@section('title', $tournee->order_number . '-' . $tournee->employee->first_name . ' ' . $tournee->employee->last_name)
@section('content')
    <div id="report-content">
        <div class="bg-white w-4/5 mx-auto py-1 sm:px-1 lg:px-2 printable">
            <div class="bg-white p-1">
                <div class="flex flex-wrap mb-1">
                    <x-application-logo class="w-2/5"></x-application-logo>
                    <div class="w-3/5 px-10 mt-10 mb-6 md:mb-0 text-end">
                        <p>Beyrouth, {{ $tournee->memor_date->format('d/m/Y') }}</p>
                    </div>
                    <div class="w-full px-3 mt-4 mb-2 md:mb-0 text-center">
                        <h3 class="text-lg font-semibold">MÉMOIRE DE FRAIS</h3>
                    </div>
                </div>
            </div>
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
                        <td class="w-1/4">{{ $tournee->arrive_location }}</td>
                        <td class="w-1/4">Pays :</td>
                        <td class="w-1/4">{{ $tournee->bareme->pays }}</td>
                    </tr>
                    <tr>
                        <td class="w-1/4">Date d'arrivée :</td>
                        <td class="w-1/4">{{ $tournee->start_date->format('d/m/Y') }}</td>
                        <td class="w-1/4">Heure d'arrivée :</td>
                        <td class="w-1/4">{{ $tournee->start_time }}</td>
                    </tr>
                    <tr>
                        <td class="w-1/4">Date de départ :</td>
                        <td class="w-1/4">{{ $tournee->end_date->format('d/m/Y') }}</td>
                        <td class="w-1/4">Heure de départ :</td>
                        <td class="w-1/4">{{ $tournee->end_time }}</td>
                    </tr>
                    <tr class="bg-gray-200 h-4">
                        <td colspan="4"></td>
                    </tr>
                    <tr>
                        <td class="w-1/4">Nuitées à déduire des IJM :</td>
                        <td class="w-1/4">{{ $tournee->no_ded_accomodation }}</td>
                        <td class="w-1/4">Repas à déduire :</td>
                        <td class="w-1/4">{{ $tournee->no_ded_meals }}</td>
                    </tr>
                    <tr>
                        <td class="w-1/4">Avance sur IJM (USD ou EURO) :</td>
                        <td class="w-1/4">{{ $tournee->advance }}</td>
                        <td class="w-1/4">Restau adm. :</td>
                        <td class="w-1/4">0</td>
                    </tr>
                </tbody>
            </table>
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
                Nature de
                la dépense</th>
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
                <td
                    class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-sm font-medium text-gray-800">
                    {{ $expense->description }}</td>
                <td
                    class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-sm text-gray-800">
                    {{ $expense->expense_dateformat('d/m/Y H:i') }}</td>
                <td
                    class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-sm text-gray-800">
                    {{ $expense->amount }}</td>
                <td
                    class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-sm text-gray-800">
                    {{ $expense->currency }}</td>
            </tr>
        @empty
            <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                <td colspan="4"
                    class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-sm font-medium text-gray-800">
                    {{ __('No Expenses Found') }}</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        @forelse ($tournee->getExpensesByCurrency() as $currency=>$currencyAmount)
            <tr>
                <th scope="col"></th>
                <th scope="col"
                    class="px-1 py-[2px] text-center text-xs font-bold text-blue-600 uppercase border border-gray-500">
                    SOMME
                </th>
                <th scope="col"
                    class="px-1 py-[2px] text-center text-xs font-bold text-blue-600 uppercase border border-gray-500">
                    {{ $currencyAmount }}
                </th>
                <th scope="col"
                    class="px-1 py-[2px] text-center text-xs font-bold text-blue-600 uppercase border border-gray-500">
                    {{ $currency }}
                </th>
                <th scope="col"></th>
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
            <table class="table-auto w-full text-left">
                <thead>
                    <tr class="bg-blue-200">
                        <th colspan="2" class="px-4">Montant total du remboursement (IJM + FRAIS DIVERS - AVANCE)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="w-full py-1" colspan="2">
                            <table class="border border-gray-500 table-auto text-center">
    <tr>
        <td rowspan="2" class="w-1/3 border border-gray-500">Totaux</td>
        <td class="border border-gray-500 w-2/12">IJM</td>
        <td class="border border-gray-500 w-2/12">Frais divers</td>
        <td class="border border-gray-500 w-2/12">Avance</td>
        <td class="border border-gray-500 w-2/12">Net à payer</td>
    </tr>
    <tr>
        <td class="border border-gray-500 w-2/12">{{ $tournee->total_amount }}</td>
        <td class="border border-gray-500 w-2/12">
            <ul>
                @forelse ($tournee->getExpensesByCurrency() as $currency=>$currencyAmount)
                    <li>{{ $currencyAmount }} {{ $currency }}</li>
                @empty
                    <li>0.00</li>
                @endforelse
            </ul>
        </td>
        <td class="border border-gray-500 w-2/12">{{ $tournee->advance }}</td>
        <td class="border border-gray-500 w-2/12">
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
            <table class="table-auto w-full text-left">
                @if (count($tournee->getMemoireTotals()) === 1)
                    <tr>
                        <td class="w-1/3 py-[1px]">ARRETE ET LIQUIDE LA SOMME DE :</td>
                        @foreach ($tournee->getMemoireTotals() as $currency => $currencyAmount)
                            <td class="w-2/3 py-[1px]">{{ $currencyAmount }} {{ $currency }} <span
                                    class="font-normal px-5"> arrondi à </span>{{ round($currencyAmount) }}
                                {{ $currency }}</td>
                        @endforeach
                    </tr>
                @else
                    @foreach ($tournee->getMemoireTotals() as $currency => $currencyAmount)
                        @if ($loop->first)
                            <tr>
                                <td class="w-1/3 py-[1px]" rowspan="{{ count($tournee->getMemoireTotals()) }}">
                                    ARRETE ET LIQUIDE LA SOMME DE :</td>
                                <td class="w-2/3 py-[1px]">{{ $currencyAmount }} {{ $currency }} <span
                                        class="font-normal px-5">
                                        arrondi à </span>{{ round($currencyAmount) }}
                                    {{ $currency }}</td>
                            </tr>
                        @else
                            <tr>
                                <td class="w-2/3 py-[1px]">{{ $currencyAmount }} {{ $currency }} <span
                                        class="font-normal px-5">
                                        arrondi à </span>{{ round($currencyAmount) }}
                                    {{ $currency }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            </table>
            {{-- <div class="w-10/12 justify-between">
                <div class="w-1/3 font-semibold inline">ARRETE ET LIQUIDE LA SOMME DE :</div>
                <div class="w-full pl-40">
                    <table>
                        @forelse ($tournee->getMemoireTotals() as $currency=>$currencyAmount)
                            <tr>
                                <td class="w-3/12 text-center font-bold">{{ $currencyAmount }} {{ $currency }}</td>
                                <td class="w-3/12 text-center font-bold"><span class="font-normal px-5"> arrondi à </span>
                                </td>
                                <td class="w-3/12 text-center font-bold">{{ round($currencyAmount) }} {{ $currency }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">0.00</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div> --}}
            <table class="table-auto w-full text-left">
                <thead>
                    <tr class="bg-blue-200">
                        <th class="px-4 w-5/12">Signature du bénéficiaire</th>
                        <th class="px-24 w-7/12">Signature de l'autorité compétente</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="w-5/12">
                            <span>Atteste l'effectitivité des dépenses exposées</span>
                        </td>
                        <td class="w-7/12 text-center">
                            <span>Certifié exact,</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-5/12 pb-10">
                            <span>ci-dessus, en demande le remboursement,</span>
                        </td>
                        <td class="w-7/12 text-center">
                            <span></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-5/12">
                            <span></span>
                        </td>
                        <td class="w-7/12 text-center">
                            <span class="font-bold text-lg w-24 text-center">SCIORTINO Sabine</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-5/12">
                            <span class="font-light text-md  w-16 text-center">
                                {{ $tournee->employee->first_name }} {{ $tournee->employee->last_name }},
                                {{ $tournee->employee->position }}
                            </span>
                        </td>
                        <td class="w-7/12 text-center">
                            <span class="font-light text-md  w-16 text-center">COCAC - Directrice de l'IFL</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Add a print button -->
    <div class="mt-6 no-print text-center">
        <button onclick="window.print()" class="bg-blue-500 px-4 py-3 hover:bg-blue-700 text-white font-bold rounded">
            {{ __('Print Report') }}
        </button>
        <button id="download-pdf" class="bg-blue-500 px-4 py-3  hover:bg-blue-700 text-white font-bold rounded">
            {{ __('Save as PDF file') }}
        </button>
    </div>
    <script>
        document.getElementById("download-pdf").addEventListener("click", function() {
            var element = document.getElementById('report-content'); // The element you want to print

            var opt = {
                margin: 0,
                filename: `Mémoire-{{$tournee->order_number}}-{{$tournee->employee->first_name}}_{{$tournee->employee->last_name}}.pdf`,


                image: {
                    type: 'png',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2,
                    useCORS: true,
                }, // For better quality
                jsPDF: {
                    unit: 'in',
                    format: 'a4',
                    orientation: 'portrait'
                }
            };

            // Generate and download the PDF
            html2pdf().from(element).set(opt).save();
        });
    </script>
@endsection

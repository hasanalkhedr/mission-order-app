<!-- resources/views/reports/tournee_order_report.blade.php -->

@extends('layouts.app')
@section('title', $tournee->order_number . '-' . $tournee->employee->first_name . ' ' . $tournee->employee->last_name)
@section('content')
    <div class="bg-white max-w-4xl mx-auto py-10 sm:px-6 lg:px-8 printable">
        <div id="report-content">
            <div class="bg-white p-6">
                <div class="flex flex-wrap mb-2">
                    <x-application-logo class="w-2/5"></x-application-logo>
                    <div class="w-3/5 px-10 mt-10 mb-6 md:mb-0 text-end">
                        <p>Beyrouth, {{ $tournee->order_date->format('d/m/Y') }}</p>
                    </div>
                    <div class="w-full px-3 mt-4 mb-2 md:mb-0 text-center">
                        <h3 class="text-lg font-semibold">ORDRE DE TOURNEE {{ $tournee->order_number }}</h3>
                    </div>
                </div>
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
                            <td class="w-1/3">Résidence administrative :</td>
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
                            <td class="w-1/3">Objet :</td>
                            <td class="w-2/3">{{ $tournee->purpose }}</td>
                        </tr>
                        <tr><td colspan="2" class="w-full">
                            <h2 class="text-md text-center justify-center text-blue-500">Destinations du Tournee</h2>
                            <table class="w-full text-xs text-center text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <th class="cursor-pointer py-[2px] px-[2px] blue-color">#</th>
                                    <th class="cursor-pointer py-[2px] px-[2px] blue-color">Lieu de départ</th>
                                    <th class="cursor-pointer py-[2px] px-[2px] blue-color">Date et Heure d'arrivée lieu de mission
                                    </th>
                                    <th class="cursor-pointer py-[2px] px-[2px] blue-color">Lieu de mission</th>
                                    <th class="cursor-pointer py-[2px] px-[2px] blue-color">Date et Heure de départ lieu de mission
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
                        </td></tr>
                    </tbody>
                </table>
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
                                (Montant:{{ $tournee->bareme->pays_per_day . ' ' . $tournee->bareme->currency }}
                                / Repas:{{ $tournee->bareme->meal_cost }} /
                                Hebergement:{{ $tournee->bareme->accomodation_cost }})
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
                            <td class="w-2/3">Prise en charge des frais de transport :</td>
                            <td class="w-1/3">{{ $tournee->charge == 1 ? 'OUI' : 'NON' }}</td>
                        </tr>
                        <tr>
                            <td class="w-2/3">Prise en charge des indemnités journalières de tournee :</td>
                            <td class="w-1/3">{{ $tournee->ijm == 1 ? 'OUI' : 'NON' }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="w-full">{{ $tournee->budget_text }}</td>
                        </tr>
                        @if($tournee->reception_fees)
                        <tr>
                            <td class="w-2/3">Frais de réception :</td>
                            <td class="w-1/3">{{ $tournee->reception_fees }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
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
                <table class="table-auto w-full text-left">
                    <thead>
                        <tr class="bg-blue-200">
                            <th colspan="2" class="px-4">Signature de l'autorité compétente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2" class="w-full px-28 pt-2 pb-2 justify-end items-end text-right">
                                <span class="font-bold text-lg w-24 text-center">SCIORTINO Sabine</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="w-full px-24 pt-0 pb-40 text-right">
                                <span class="font-light text-md  w-16 text-center">COCAC - Directrice de l'IF</span>
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
    </div>
    <script>
        document.getElementById("download-pdf").addEventListener("click", function() {
            var element = document.getElementById('report-content'); // The element you want to print

            var opt = {
                margin: [0.1, 0.3, 0.5, 0.3],
                filename: "{{ $tournee->order_number . '-' . $tournee->employee->first_name . ' ' . $tournee->employee->last_name . '.pdf' }}",
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

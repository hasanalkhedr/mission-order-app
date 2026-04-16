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
                @if($tournee->conge)
                <tr>
                    <td class="w-1/3">Conge pendant mission:</td>
                    <td colspan="3" class="w-2/3">{{ $tournee->conge }}</td>
                </tr>
                @endif
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
                <tr class="bg-gray-200 h-4">
                    <td colspan="4"></td>
                </tr>
                {{-- <tr>
                    <td class="w-1/4">Nuitées à déduire des IJM :</td>
                    <td class="w-1/4">{{ $tournee->no_ded_accomodation }}</td>
                    <input type="hidden" id="no_ded_accomodation" value="{{ $tournee->no_ded_accomodation }}">
                    <td class="w-1/4">Repas à déduire :</td>
                    <td class="w-1/4">{{ $tournee->no_ded_meals }}</td>
                    <input type="hidden" id="no_ded_meals" value="{{ $tournee->no_ded_meals }}">
                </tr> --}}
                {{-- <tr>
                    <td class="w-1/4">Avance (Roupie indienne) :</td>
                    <td class="w-1/4">{{ $tournee->advance }}</td>
                    <td class="w-1/4">Restau adm. :</td>
                    <td class="w-1/4">0</td>
                </tr> --}}
            </tbody>
        </table>
        @if($tournee->has_weekend)
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 py-1 px-2 mb-1">
                <p>Ce Calendrier inclu un ou plusieurs jours de Weekend.</p>
            </div>
        @endif
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
                Libelle des dépenses à prendre en charge</th>
           <th scope="col"
                class="px-1 py-[2px] text-center text-xs font-medium text-gray-500 uppercase">
                A rembourser à l'agent</th>
            <th scope="col"
                class="px-1 py-[2px] text-center text-xs font-medium text-gray-500 uppercase">
                Devise</th>
            <th scope="col"
                class="px-1 py-[2px] text-center text-xs font-medium text-gray-500 uppercase">
                Prise en charge directe</th>
            <th scope="col"
                class="px-1 py-[2px] text-center text-xs font-medium text-gray-500 uppercase">
                Devise</th>
            <th scope="col"
                class="px-1 py-[2px] text-center text-xs font-medium text-gray-500 uppercase">
                Total INR</th>
        </tr>
    </thead>
    <tbody>
        <!-- Repas Row -->
        <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
            <td class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                <span class="expense-badge bg-green-100 text-green-800">Repas</span>
            </td>
            <td class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                {{ $tournee->no_meals }} <span class="text-red-600"> - {{$tournee->no_ded_meals}} = {{$tournee->no_meals - $tournee->no_ded_meals}}</span>
            </td>
            <td class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                {{ ($tournee->no_meals - $tournee->no_ded_meals) * $tournee->bareme->meal_cost / ($current_rate ? $current_rate->eur_rate : 1) }}
            </td>
            <td class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                INR
            </td>
            <td class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                --
            </td>
            <td class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                --
            </td>
            <td class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                {{ ($tournee->no_meals - $tournee->no_ded_meals) * $tournee->bareme->meal_cost / ($current_rate ? $current_rate->eur_rate : 1) }}
            </td>
        </tr>

        <!-- Hébergement Row -->
        @if($tournee->acc_total_inr && $tournee->acc_total_inr>0)
        <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
            <td class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                <span class="expense-badge bg-blue-100 text-blue-800">Hébergement</span>
            </td>
            <td class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                {{ $tournee->no_accomodation }}<span class="text-red-600"> - {{$tournee->no_ded_accomodation}} = {{$tournee->no_accomodation - $tournee->no_ded_accomodation}}</span>
            </td>
            <td class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                {{$tournee->acc_reimbursement_amount}}
            </td>
            <td class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                {{$tournee->acc_reimbursement_currency}}
            </td>
            <td class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                {{$tournee->acc_direct_amount}}
            </td>
            <td class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                {{$tournee->acc_direct_currency}}
            </td>
            <td class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                {{ $tournee->acc_total_inr }}
            </td>
        </tr>
        @endif
        @foreach ($tournee->expenses as $expense)
            @if($expense->total_inr && $expense->total_inr>0)
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
                        {{ __('expense.transport_types.'. $expense->transport_type) }}<br/>
                       @if($expense->transport_type === 'car_rental_with_driver')
                            @php
                            $details = [];
                            if ($expense->passenger == 1) $details[] = __('passenger');
                            if ($expense->distance == 1) $details[] = __('distance');
                            if ($expense->material == 1) $details[] = __('material');
                            if ($expense->visits == 1) $details[] = __('visits');
                            @endphp
                            @if(!empty($details))
                                @foreach($details as $detail)
                                    &nbsp;&nbsp;{{ $detail }}@if(!$loop->last)<br>@endif
                                @endforeach
                            @endif
                        @endif
                    @else
                        {{ $expense->meal_location }}
                        {{ $expense->description }}
                    @endif
                </td>

                <td
                    class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                    {{ number_format($expense->reimbursement_amount,2) }}
                </td>

                <!-- Amount -->
                <td
                    class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                    {{ $expense->reimbursement_currency }}
                </td>

                <!-- Currency -->
                <td
                    class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                    {{ number_format($expense->direct_amount,2) }}
                </td>
                <td
                    class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                    {{ $expense->direct_currency }}
                </td>
                <td
                    class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs text-gray-800">
                    {{ $expense->total_inr }}
                </td>
            </tr>
            @endif
        {{-- @empty
            <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                <td colspan="6"
                    class="px-1 text-center border border-gray-200 py-[2px] whitespace-nowrap text-xs font-medium text-gray-800">
                    {{ __('No Expenses Found') }}
                </td>
            </tr> --}}
        @endforeach
    </tbody>
    <tfoot>
        <tr>
                <th colspan="2"
                    class="px-1 py-[2px] text-center text-xs font-bold text-blue-600 uppercase border border-gray-500">
                    SOMME
                </th>
                <th
                    class="px-1 py-[2px] text-center text-xs font-bold text-blue-600 uppercase border border-gray-500">
                    {{ number_format($tournee->expense_reimbursement_total, 2) }}
                </th>
                <th
                    class="px-1 py-[2px] text-center text-xs font-bold text-blue-600 uppercase border border-gray-500">
 INR
                </th>
                <th
                    class="px-1 py-[2px] text-center text-xs font-bold text-blue-600 uppercase border border-gray-500">
                    {{ number_format($tournee->expense_direct_total, 2) }}

                </th>
                <th
                    class="px-1 py-[2px] text-center text-xs font-bold text-blue-600 uppercase border border-gray-500">
 INR
                </th>
                <th
                    class="px-1 py-[2px] text-center text-xs font-bold text-blue-600 uppercase border border-gray-500">
                    {{ number_format($tournee->expense_grand_total, 2) }}
 INR
                </th>
            </tr>
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
                        <table class="border border-gray-500 table-auto text-center w-full">
                            <tr>
                                <td rowspan="2" class="w-1/12 border border-gray-500 font-bold">Totaux</td>
                                <td class="border border-gray-500 w-3/12">A rembourser à l'agent</td>
                                <td class="border border-gray-500 w-3/12">Prise en charge directe</td>
                                <td class="border border-gray-500 w-2/12">Avance</td>
                                {{-- <td class="border border-gray-500 w-3/12">Net à payer <span class="text-xxs">(A rembourser à l'agent - Avance)</span></td> --}}
                                <td class="border border-gray-500 w-3/12">Mission totale</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-500 w-3/12">{{ $tournee->expense_reimbursement_total }}</td>
                                <td class="border border-gray-500 w-3/12">{{ $tournee->expense_direct_total }}
                                </td>
                                <td class="border border-gray-500 w-2/12">{{ $tournee->advance }}</td>
                                <td class="border border-gray-500 w-3/12">{{ $tournee->expense_reimbursement_total + $tournee->expense_direct_total }}
                                </td>
                            </tr>
                        </table>
                    </td>
            </tbody>
        </table>

        <!-- Net Total Table -->
        {{-- <table class="table-auto w-full text-left">
            <tr>
                <td class="w-1/2 py-[1px]">ARRETE ET LIQUIDE LA SOMME DE :</td>
                    <td class="w-2/2 py-[1px] font-bold text-red-600">{{ $tournee->expense_reimbursement_total - $tournee->advance }} INR
                        <span class="font-normal px-5"> arrondi à </span>{{ round($tournee->expense_reimbursement_total - $tournee->advance) }}
                        INR
                    </td>
            </tr>
        </table> --}}

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
                {{-- <tr>
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
                </tr> --}}
            </tbody>
        </table>
        <table class="table-auto w-full text-left">
            <tbody>
                <tr>
                    <td class="px-2 py-[1px] w-1/3">
                        <span class="font-bold text-lg text-center">Agent: </span>
                    </td>
                    <td class="px-2 py-[1px] w-1/3">
                        <span class="font-bold text-lg text-center">Gestionnaire: </span>
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
                        <span class="font-light text-md text-center">{{$tournee->memor_date->format('d/m/Y')}}</span>
                    </td>
                    <td class="w-1/3 px-2 py-[1px]">
                        <span class="font-light text-md text-center">
                            {{$tournee->getTourneeMemoirApproves()->where('approval_role', 'Gestionnaire')->last() ?
                                $tournee->getTourneeMemoirApproves()->where('approval_role', 'Gestionnaire')->last()->created_at->format('d/m/Y') : ''}}
                        </span>
                    </td>
                    <td class="w-1/3 px-2 py-[1px]">
                        <span class="font-light text-md text-center">{{$tournee->getTourneeMemoirApproves()->where('approval_role', 'Ordonnateur')->last() ?
                            $tournee->getTourneeMemoirApproves()->where('approval_role', 'Ordonnateur')->last()->created_at->format('d/m/Y') : ''}}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
        @if($tournee->memor_status === 'approved' && $tournee->accountant_id)
             <table class="table-auto w-full text-left mt-20 pt-20">
                <tbody>
                    <tr>
                        <td class="px-2 py-[1px] w-1/2">
                            <span class="font-bold text-lg text-center">Preparation de paiment:</span>
                            <span class="font-bold text-lg text-center">{{\App\Models\Employee::find($tournee->accountant_id)->first_name}} {{\App\Models\Employee::find($tournee->accountant_id)->last_name}}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
            @endif
    </div>

    <!-- OM REPORT -->
    <div class="report-page" style="width: 210mm; height: 297mm; margin: 0 auto; padding: 8mm; box-sizing: border-box;">
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
                    <td class="w-1/5">Objet/Motfits:</td>
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
                    <td class="w-5/6">Demande d'avance:</td>
                    <td class="w-1/6">{{ $tournee->advance >0 ? $tournee->advance : 'NON' }}</td>
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
                Nature de la dépense</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($tournee->expenses as $expense)
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

    <!-- Documents - each will be on separate pages -->
    @if($tournee->acc_expense_document)
        <div class="document-page" style="page-break-before: always; width: 210mm;">
            <h4 class="text-center font-bold mb-1">Document: Hébergement </h4>
            <div class="flex justify-center">
                @if (pathinfo($tournee->acc_expense_document, PATHINFO_EXTENSION) === 'pdf')
                    <div id="pdf-viewer-{{ $tournee->id }}" class="pdf-container"
                        style="width: 100%; height: 240mm;"></div>
                @else
                    <img src="{{ asset('storage/public/' . $tournee->acc_expense_document) }}"
                        style="max-width: 100%; max-height: 240mm; object-fit: contain;" alt="Expense Document">
                @endif
            </div>
        </div>
    @endif

    @foreach ($tournee->expenses as $expense)
        <div class="document-page" style="page-break-before: always; width: 210mm;">
            <h4 class="text-center font-bold mb-1">Document: {{ $expense->type }} {{__('expense.transport_types.'. $expense->transport_type)}} {{$expense->meal_location ?? ''}} {{$expense->description ?? ''}}</h4>
            {{-- <h4 class="text-center font-bold mb-1">Document: {{ $expense->description }}</h4> --}}
            <div class="flex justify-center">
                @if (pathinfo($expense->expense_document, PATHINFO_EXTENSION) === 'pdf')
                    <div id="pdf-viewer-{{ $expense->id }}" class="pdf-container"
                        style="width: 100%; height: 240mm;"></div>
                @else
                    <img src="{{ asset('storage/public/' . $expense->expense_document) }}"
                        style="max-width: 100%; max-height: 240mm; object-fit: contain;" alt="Expense Document">
                @endif
            </div>
        </div>
    @endforeach
</div>
<!-- Action Buttons -->
    <div class="flex justify-center space-x-4 mb-8 no-print">
        <button id="download-pdf"
            class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-200 flex items-center justify-center w-48">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                </path>
            </svg>
            {{ __('Save as PDF file') }}
        </button>
        @if (auth()->user()->employee->hasRole('controller')
            && in_array($tournee->employee->department_id, \App\Models\Department::where('controller_id', Auth::user()->employee->id)->pluck('id')->toArray())
            && $tournee->memor_status === 'controller_approve'
            && Auth::user()->employee->id != $tournee->employee_id)
            <form method="POST" action="{{ route('tournee_approves.m_approve', $tournee->id) }}">
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
        @endif
        @if (auth()->user()->employee->hasRole('sg') && $tournee->memor_status === 'sg_approve' && Auth::user()->employee->id != $tournee->employee_id)
            <!-- Valider Button with Modal Trigger -->
            <button type="button"
                    data-modal-target="validerModal-{{ $tournee->id }}"
                    data-modal-toggle="validerModal-{{ $tournee->id }}"
                    class="bg-white text-center hover:bg-white text-blue-800 border border-blue-300 font-bold py-3 px-6 rounded-lg shadow-md transition duration-200 flex items-center justify-center w-48">
                <svg class="h-6 w-6 text-blue-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ __('Valider') }}
            </button>

            <!-- Valider Modal -->
            <div id="validerModal-{{ $tournee->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Preparation de paiment
                            </h3>
                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="validerModal-{{ $tournee->id }}">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 md:p-5">
                            <form method="POST" action="{{ route('tournee_approves.m_approve', $tournee->id) }}">
                                @csrf
                                <div class="mb-4">
                                    <label for="approval_type-{{ $tournee->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sélectionnez l'employé responsable de la préparation du paiement.</label>
                                    <select id="approval_type-{{ $tournee->id }}" name="accountant" required
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="">Sélectionnez une employé</option>
                                        @foreach (\App\Models\Employee::accountant()->get() as $employee)
                                            <option value="{{$employee->id}}">{{$employee->first_name}} {{$employee->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex justify-end space-x-3">
                                    <button type="submit"
                                            name="action"
                                            value="approve"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        Confirmer
                                    </button>
                                    <button type="button"
                                            data-modal-toggle="validerModal-{{ $tournee->id }}"
                                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                        Annuler
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

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
        @if (pathinfo($tournee->acc_expense_document, PATHINFO_EXTENSION) === 'pdf')
                renderPDF(
                    "{{ asset('storage/public/' . $tournee->acc_expense_document) }}",
                    "pdf-viewer-{{ $tournee->id }}"
                );
            @endif
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
                filename: `Mémoire-{{ $tournee->order_number }}-{{ $tournee->employee->first_name }}_{{ $tournee->employee->last_name }}.pdf`,
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 1.7,
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

            // Set the filename for download by adding a suggested filename to the blob URL
            // Note: This approach may not work in all browsers
            if (newTab) {
                // Alternative approach: Use download attribute in an iframe
                setTimeout(() => {
                    const iframe = document.createElement('iframe');
                    iframe.style.display = 'none';
                    iframe.src = blobUrl;
                    iframe.setAttribute('download', filename);
                    document.body.appendChild(iframe);
                    setTimeout(() => {
                        document.body.removeChild(iframe);
                    }, 100);
                }, 1000);
            }

            // Clean up the blob URL after some time
            setTimeout(() => {
                URL.revokeObjectURL(blobUrl);
            }, 5000);

            updateProgress(100, "Done!");
        } catch (error) {
            console.error("PDF generation failed:", error);
            updateProgress(0, "Failed to generate PDF");
            throw error; // Re-throw if you want calling code to handle it
        }
    }
</script>
@endsection

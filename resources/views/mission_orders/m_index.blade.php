@php
    use App\Models\Department;
@endphp
@extends('layouts.app')

@section('content')
@section('title', __('Memoire'))
<nav class="flex justify-between items-center p-2 text-black font-bold">
    <div class="text-lg blue-color">
        {{ __('MÉMOIRE DE FRAIS') }}
    </div>
</nav>

@include('partials.searches._search-missions_m')
<div class="overflow-x-auto relative shadow-md sm:rounded-lg">
    <table x-data="data()" class="w-full text-sm text-left text-gray-500" x-data="employeeData">
        @unless ($missionOrders->isEmpty())
            <thead class="text-s text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Mission #') }}
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Employée') }}
                    </th>
                    {{-- <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Objet') }}
                    </th> --}}
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Pays') }}
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Lieu de la mission') }}
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Début le') }}
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('S’achève le') }}
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Statut') }}
                    </th>
                    {{-- @if (auth()->user()->hasRole('human_resource')) --}}
                    <th colspan="2" scope="col" class="py-3 px-6 blue-color text-center">Actions
                    </th>
                </tr>
            </thead>
            <tbody x-ref="tbody">
                @foreach ($missionOrders as $missionOrder)
                    <tr class="bg-white hover:bg-gray-50">
                        <td class="border-b py-4 px-6 font-bold text-gray-900 whitespace-nowrap cursor-pointer"
                            onclick="window.location.href = '{{ url(route('mission_orders.m_show', $missionOrder->id)) }}'">
                            <div class="cursor-pointer">
                                {{ $missionOrder->order_number }}
                            </div>
                        </td>
                        <td class="py-4 px-6 border-b cursor-pointer">
                            <div class="cursor-pointer">
                                {{ $missionOrder->employee->first_name }} {{ $missionOrder->employee->last_name }}
                            </div>
                        </td>
                        <td class="py-4 px-6 border-b cursor-pointer">
                            <div class="cursor-pointer">
                                {{ $missionOrder->bareme->pays }}|{{ $missionOrder->bareme->currency }}
                            </div>
                        </td>
                        <td class="py-4 px-6 border-b cursor-pointer">
                            <div class="cursor-pointer">
                                {{ $missionOrder->arrive_location }}
                            </div>
                        </td>
                        <td class="py-4 px-6 border-b cursor-pointer">
                            <div class="cursor-pointer">
                                {{ $missionOrder->start_date->format('d/m/Y') }} at {{ $missionOrder->start_time }}
                            </div>
                        </td>
                        <td class="py-4 px-6 border-b cursor-pointer">
                            <div class="cursor-pointer">
                                {{ $missionOrder->end_date->format('d/m/Y') }} at {{ $missionOrder->end_time }}
                            </div>
                        </td>
                        <td class="py-4 px-6 border-b cursor-pointer">
                            <div class="cursor-pointer">
                                {{ __($missionOrder->memor_status) }}
                            </div>
                        </td>
                        {{-- @hasanyrole('human_resource|sg|head') --}}
                        @switch($missionOrder->memor_status)
                            @case(null)
                                @if (auth()->user()->employee->id === $missionOrder->employee_id)
                                    <td class="text-center px-0 py-1 border-b">
                                        <a href="{{ route('mission_orders.m_create', $missionOrder->id) }}"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-1 py-1 text-center hover:text-gray-900">
                                            {{ __('Add Memoire') }}
                                        </a>
                                    </td>
                                @endif
                            @break

                            @case('draft')
                                @if (auth()->user()->employee->id === $missionOrder->employee_id)
                                    <td class="text-center px-0 py-1 border-b">
                                        <a href="{{ route('mission_orders.m_create', $missionOrder->id) }}"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-1 py-1 text-center hover:text-gray-900">
                                            {{ __('Edit') }}
                                        </a>
                                    </td>
                                    <td class="text-center px-0 py-1 border-b">
                                        <button
                                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-1 py-1 text-center hover:text-gray-900"
                                            type="button" data-modal-toggle="deleteModal-{{ $missionOrder->id }}">
                                            {{ __('Delete') }}
                                        </button>
                                    </td>
                                @endif
                            @break

                            @case('controller_approve')
                                @if (auth()->user()->employee->hasRole('controller'))
                                    <td class="text-center px-0 py-1 border-b">
                                        <button
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-1 py-1 text-center hover:text-gray-900"
                                            type="button" data-modal-toggle="approveModal-{{ $missionOrder->id }}">
                                            {{ __('Approve') }}
                                        </button>
                                    </td>
                                @endif
                                @if (auth()->user()->employee->hasRole('director') || auth()->user()->employee->hasRole('controller') ||
                                        auth()->user()->employee->hasRole('sg') ||
                                        (auth()->user()->employee->hasRole('supervisor') &&
                                            auth()->user()->employee->department_id === $missionOrder->employee->department_id))
                                    <td class="text-center px-0 py-1 border-b">
                                        <a href="{{ route('mission_orders.m_report', $missionOrder->id) }}"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-1 py-1 text-center hover:text-gray-900">{{ __('Print') }}</a>
                                    </td>
                                @endif
                            @break

                            @case('sg_approve')
                                @if (auth()->user()->employee->hasRole('sg'))
                                    <td class="text-center px-0 py-1 border-b">
                                        <button
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-1 py-1 text-center hover:text-gray-900"
                                            type="button" data-modal-toggle="approveModal-{{ $missionOrder->id }}">
                                            {{ __('Approve') }}
                                        </button>
                                    </td>
                                @endif
                                @if (auth()->user()->employee->hasRole('director') || auth()->user()->employee->hasRole('controller') ||
                                auth()->user()->employee->hasRole('sg') ||
                                (auth()->user()->employee->hasRole('supervisor') &&
                                    auth()->user()->employee->department_id === $missionOrder->employee->department_id))
                                    <td class="text-center px-0 py-1 border-b">
                                        <a href="{{ route('mission_orders.m_report', $missionOrder->id) }}"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-1 py-1 text-center hover:text-gray-900">{{ __('Print') }}</a>
                                    </td>
                                @endif
                            @break

                            @case('approved')
                                @if ($missionOrder->employee->id == auth()->user()->employee->id)
                                    <td class="text-center px-0 py-1 border-b">
                                        <a href="#"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-1 py-1 text-center hover:text-gray-900">{{ __('Request to Pay') }}</a>
                                    </td>
                                    <td class="text-center px-0 py-1 border-b">
                                        <a href="{{ route('mission_orders.m_report', $missionOrder->id) }}"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-1 py-1 text-center hover:text-gray-900">{{ __('Print') }}</a>
                                    </td>
                                @elseif (auth()->user()->employee->hasRole('supervisor') &&
                                        auth()->user()->employee->department_id == $missionOrder->employee->department_id)
                                    <td class="text-center px-0 py-1 border-b">
                                        <a href="{{ route('mission_orders.m_report', $missionOrder->id) }}"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-1 py-1 text-center hover:text-gray-900">{{ __('Print') }}</a>
                                    </td>
                                @elseif(auth()->user()->employee->hasRole('director') || auth()->user()->employee->hasRole('controller') || auth()->user()->employee->hasRole('sg'))
                                    <td class="text-center px-0 py-1 border-b">
                                        <a href="{{ route('mission_orders.m_report', $missionOrder->id) }}"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-1 py-1 text-center hover:text-gray-900">{{ __('Print') }}</a>
                                    </td>
                                @endif
                            @break

                            @case('paid')
                            @break
                        @endswitch
                        @include('partials.modals._delete-memoier')
                        @include('partials.modals._approve-memoier')
                    </tr>
                @endforeach
            @else
                <tr class="border-gray-300">
                    <td colspan="4" class="px-4 py-8 border-t border-gray-300 text-lg">
                        <p class="text-center">{{ __('No MÉMOIRE DE FRAIS Found') }}</p>
                    </td>
                </tr>
            @endunless
        </tbody>
    </table>
</div>
<div class="mt-6 p-4">
    {{ $missionOrders->links() }}
</div>
<script type="text/javascript">
    function data() {
        return {
            sortBy: "",
            sortAsc: false,
            sortByColumn($event) {
                if (this.sortBy === $event.target.innerText) {
                    if (this.sortAsc) {
                        this.sortBy = "";
                        this.sortAsc = false;
                    } else {
                        this.sortAsc = !this.sortAsc;
                    }
                } else {
                    this.sortBy = $event.target.innerText;
                }

                let rows = this.getTableRows()
                    .sort(
                        this.sortCallback(
                            Array.from($event.target.parentNode.children).indexOf(
                                $event.target
                            )
                        )
                    )
                    .forEach((tr) => {
                        this.$refs.tbody.appendChild(tr);
                    });
            },
            getTableRows() {
                return Array.from(this.$refs.tbody.querySelectorAll("tr"));
            },
            getCellValue(row, index) {
                return row.children[index].innerText;
            },
            sortCallback(index) {
                return (a, b) =>
                    ((row1, row2) => {
                        return row1 !== "" &&
                            row2 !== "" &&
                            !isNaN(row1) &&
                            !isNaN(row2) ?
                            row1 - row2 :
                            row1.toString().localeCompare(row2);
                    })(
                        this.getCellValue(this.sortAsc ? a : b, index),
                        this.getCellValue(this.sortAsc ? b : a, index)
                    );
            }
        };
    }
</script>
@endsection

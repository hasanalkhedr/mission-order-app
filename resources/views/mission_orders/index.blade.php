@php
    use App\Models\Department;
@endphp
@extends('layouts.app')

@section('content')
@section('title', __('Missions'))
<nav class="flex justify-between items-center p-2 text-black font-bold">
    <div class="text-lg blue-color">
        {{ __('Missions') }}
    </div>
    @if (auth()->user()->employee->allow_order)
        <div>
            <a href="{{ route('mission_orders.create') }}"
                class="hover:bg-blue-700 text-white py-2 px-4 rounded-full blue-bg">
                {{ __('Ordre de Mission') }}
            </a>
        </div>
    @endif
</nav>
@include('partials.searches._search-missions')
<div class="overflow-x-auto relative shadow-md sm:rounded-lg">
    <table x-data="data()" class="w-full text-sm text-left text-gray-500">
        @unless ($missionOrders->isEmpty())
            <thead class="text-s text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="py-3 px-6 blue-color">
                        <div class="flex flex-col">
                            <span @click="sortByColumn" class="cursor-pointer font-semibold">{{ __('Mission #') }}</span>
                            <input type="text" x-model="filters.order_number" placeholder="Filter..."
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                        </div>
                    </th>
                    <th scope="col" class="py-3 px-6 blue-color">
                        <div class="flex flex-col">
                            <span @click="sortByColumn" class="cursor-pointer font-semibold">{{ __('Employée') }}</span>
                            <select x-model="filters.employee_id"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">All Employees</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->first_name }}
                                        {{ $employee->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </th>
                    <th scope="col" class="py-3 px-6 blue-color">
                        <div class="flex flex-col">
                            <span @click="sortByColumn" class="cursor-pointer font-semibold">{{ __('Lieu de la mission') }}</span>
                            <select x-model="filters.country"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">All Countries</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country }}">{{ $country }}</option>
                                @endforeach
                            </select>
                        </div>
                    </th>
                    <th scope="col" class="py-3 px-6 blue-color">
                        {{-- <div class="flex flex-col">
                            <span @click="sortByColumn" class="cursor-pointer font-semibold">{{ __('Début le') }}</span>
                            <div class="flex space-x-1 mt-1">
                                <input type="date" x-model="filters.start_date_from" placeholder="From"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <input type="date" x-model="filters.start_date_to" placeholder="To"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                            </div>
                        </div> --}}
                        <div class="flex flex-col">
                            <span @click="sortByColumn" class="cursor-pointer font-semibold">{{ __('Début le') }}</span>
                            <div class="mt-1 space-y-1">
                                <input type="date" x-model="filters.start_date_from" placeholder="From"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <input type="date" x-model="filters.start_date_to" placeholder="To"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                            </div>
                        </div>
                    </th>
                    <th scope="col" class="py-3 px-6 blue-color">
                        <div class="flex flex-col">
                            <span @click="sortByColumn"
                                class="cursor-pointer font-semibold">{{ __('S\'achève le') }}</span>
                            <div class="mt-1 space-y-1">
                                <input type="date" x-model="filters.end_date_from" placeholder="From"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <input type="date" x-model="filters.end_date_to" placeholder="To"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                            </div>
                        </div>
                    </th>
                    <th scope="col" class="py-3 px-6 blue-color">
                        <div class="flex flex-col">
                            <span @click="sortByColumn" class="cursor-pointer font-semibold">{{ __('Statut') }}</span>
                            <select x-model="filters.status"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">All Statuses</option>
                                <option value="draft">Brouillon</option>
                                <option value="sup_approve">Validation chef de secteur</option>
                                <option value="sg_approve">Validation ordonnateur</option>
                                <option value="controller_approve">validation gestionnaire</option>
                                <option value="approved">Valide</option>
                                <option value="rejected">Rejetée</option>
                            </select>
                        </div>
                    </th>
                    <th colspan="2" scope="col" class="py-3 px-6 blue-color text-center">
                        <span class="font-semibold">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody x-ref="tbody">
                @foreach ($missionOrders as $missionOrder)
                    <tr class="bg-white hover:bg-gray-50"
                        x-show="filterRow({
                            order_number: '{{ $missionOrder->order_number }}',
                            employee: { id: '{{$missionOrder->employee->id}}', first_name: '{{ $missionOrder->employee->first_name }}', last_name: '{{ $missionOrder->employee->last_name }}' },
                            bareme: { pays: '{{ $missionOrder->bareme->pays }}' },
                            start_date: '{{ $missionOrder->start_date->format('Y-m-d') }}',
                            end_date: '{{ $missionOrder->end_date->format('Y-m-d') }}',
                            status: '{{ $missionOrder->status }}'
                        })">
                        <td class="border-b py-4 px-6 font-bold text-gray-900 whitespace-nowrap cursor-pointer"
                            onclick="window.location.href = '{{ url(route('mission_orders.show', $missionOrder->id)) }}'">
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
                                {{-- {{ $missionOrder->bareme->pays }}|{{ $missionOrder->bareme->currency }} --}}
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
                                {{ __($missionOrder->status) }}
                            </div>
                        </td>
                        @switch($missionOrder->status)
                            @case('draft')
                                @if (auth()->user()->employee->id === $missionOrder->employee_id)
                                    <td class="text-center px-0 py-1 border-b">
                                        <a href="{{ route('mission_orders.edit', $missionOrder->id) }}"
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

                            {{-- @case('sup_approve')
                                @if (auth()->user()->employee->hasRole('supervisor') &&
                                        in_array(
                                            $missionOrder->employee->department_id,
                                            Department::where('manager_id', Auth::user()->employee->id)->pluck('id')->toArray()))
                                    <td class="text-center px-0 py-1 border-b">
                                        <button
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-1 py-1 text-center hover:text-gray-900"
                                            type="button" data-modal-toggle="approveModal-{{ $missionOrder->id }}">
                                            {{ __('AVIS DU SUPÉRIEUR HIÉRARCHIQUE') }}
                                        </button>
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
                            @break --}}

                            @case('approved')
                                @if ($missionOrder->employee->id == auth()->user()->employee->id)
                                    <td class="text-center px-0 py-1 border-b">
                                        <a href="{{ route('mission_orders.m_create', $missionOrder->id) }}"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-1 py-1 text-center hover:text-gray-900">{{  __('Add Memoire') }}</a>
                                    </td>
                                @endif
                            @break

                            @case('paid')
                            @break
                        @endswitch
                        @if (auth()->user()->employee->id === $missionOrder->employee_id
                        || (auth()->user()->employee->hasRole('supervisor') && in_array($missionOrder->employee->department_id, Department::where('manager_id', Auth::user()->employee->id)->pluck('id')->toArray()))
                        || auth()->user()->employee->hasRole('controller')
                        || auth()->user()->employee->hasRole('sg'))
                            <td class="text-center px-0 py-1 border-b">
                                <a href="{{ route('mission_orders.report', $missionOrder->id) }}"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-1 py-1 text-center hover:text-gray-900">{{ __('Print') }}</a>
                            </td>
                        @endif
                        @include('partials.modals._delete-mission')
                        {{-- @include('partials.modals._approve-mission') --}}
                    </tr>
                @endforeach
            @else
                <tr class="border-gray-300">
                    <td colspan="7" class="px-4 py-8 border-t border-gray-300 text-lg">
                        <p class="text-center">{{ __('No Missions Found') }}</p>
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
            filters: {
                order_number: '',
                employee_id: '',
                country: '',
                start_date_from: '',
                start_date_to: '',
                end_date_from: '',
                end_date_to: '',
                status: ''
            },
            filterRow(mission) {
                // Filter by order number
                if (this.filters.order_number &&
                    !mission.order_number.toLowerCase().includes(this.filters.order_number.toLowerCase())) {
                    return false;
                }

                // Filter by employee
                if (this.filters.employee_id && mission.employee.id != this.filters.employee_id) {
                    return false;
                }

                // Filter by country
                if (this.filters.country && mission.bareme.pays !== this.filters.country) {
                    return false;
                }

                // Filter by start date range
                const startDate = new Date(mission.start_date);
                if (this.filters.start_date_from) {
                    const fromDate = new Date(this.filters.start_date_from);
                    if (startDate < fromDate) return false;
                }
                if (this.filters.start_date_to) {
                    const toDate = new Date(this.filters.start_date_to);
                    if (startDate > toDate) return false;
                }

                // Filter by end date range
                const endDate = new Date(mission.end_date);
                if (this.filters.end_date_from) {
                    const fromDate = new Date(this.filters.end_date_from);
                    if (endDate < fromDate) return false;
                }
                if (this.filters.end_date_to) {
                    const toDate = new Date(this.filters.end_date_to);
                    if (endDate > toDate) return false;
                }

                // Filter by status
                if (this.filters.status && mission.status !== this.filters.status) {
                    return false;
                }

                return true;
            },
            sortByColumn($event) {
                // Find the span element that was clicked
                const spanElement = $event.target.closest('span');
                if (!spanElement) return;

                if (this.sortBy === spanElement.innerText) {
                    if (this.sortAsc) {
                        this.sortBy = "";
                        this.sortAsc = false;
                    } else {
                        this.sortAsc = !this.sortAsc;
                    }
                } else {
                    this.sortBy = spanElement.innerText;
                }

                let rows = this.getTableRows()
                    .sort(
                        this.sortCallback(
                            Array.from($event.target.closest('th').parentNode.children).indexOf(
                                $event.target.closest('th')
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

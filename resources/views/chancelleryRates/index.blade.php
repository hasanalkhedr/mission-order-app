@extends('layouts.app')
@section('content')
@section('title', __('Chancellery Rates'))
<nav class="flex justify-between items-center p-2 text-black font-bold">
    <div class="text-lg blue-color">
        {{ __('Chancellery Rates') }}
    </div>
    <div>
        @if ((auth()->user()->employee->hasRole('controller') || auth()->user()->employee->hasRole('sg')) && !App\Models\ChancelleryRate::hasCurrentRate())
            <button class="hover:bg-blue-700 text-white py-2 px-4 rounded-full blue-bg"
                data-modal-toggle="createRateModal">
                {{ __('Add Chancellery Rates') }}
            </button>
        @endif
    </div>
</nav>
<div class="overflow-x-auto relative shadow-md sm:rounded-lg">
    <table x-data="data()" class="w-full text-sm text-left text-gray-500" x-data="employeeData">
        @unless ($chancelleryRates->isEmpty())
            <thead class="text-s text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Month') }}
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('EUR to INR') }}
                    </th>
                    {{-- <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('USD to INR') }}
                    </th> --}}
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Status') }}
                    </th>
                    @if (auth()->user()->employee->hasRole('controller') || auth()->user()->employee->hasRole('sg'))
                        <th scope="col" class="py-3 px-6 blue-color">
                            <span class="sr-only">{{ __('Edit') }}</span>
                        </th>
                        <th scope="col" class="py-3 px-6">
                            <span class="sr-only">{{ __('Delete') }}</span>
                        </th>
                    @endif
                    @if (auth()->user()->employee->hasRole('sg'))
                        <th scope="col" class="py-3 px-6 blue-color">
                            <span class="sr-only">{{ __('Approve') }}</span>
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody x-ref="tbody">
                @foreach ($chancelleryRates as $chancelleryRate)
                    <tr class="bg-white hover:bg-gray-50">
                        <td class="border-b py-4 px-6 font-bold text-gray-900 whitespace-nowrap cursor-pointer">
                            <div class="font-bold">
                                {{ $chancelleryRate->month_year->format('F Y') }}
                            </div>
                        </td>

                        <td class="py-4 px-6 border-b">
                            <div class="font-bold text-red-400">
                                1 EUR = {{ $chancelleryRate->eur_rate }} {{ __('INR') }}
                            </div>
                        </td>

                        {{-- <td class="py-4 px-6 border-b">
                            <div class="font-bold text-blue-400">
                                1 USD = {{ $chancelleryRate->usd_rate }} {{ __('INR') }}
                            </div>
                        </td> --}}

                        <td class="py-4 px-6 border-b">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $chancelleryRate->status == 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($chancelleryRate->status) }}
                            </span>
                        </td>

                        @if ($chancelleryRate->status == 'draft' && (auth()->user()->employee->hasRole('controller') || auth()->user()->employee->hasRole('sg')))
                        <td class="py-4 px-6 text-right border-b">
                            <button class="font-medium hover:underline blue-color" type="button"
                                data-modal-toggle="editRateModal-{{ $chancelleryRate->id }}">
                                {{ __('Edit') }}
                            </button>
                        </td>
                        <td class="py-4 px-6 text-right border-b">
                            <button class="font-medium text-red-600 hover:underline" type="button"
                                data-modal-toggle="deleteRateModal-{{ $chancelleryRate->id }}">
                                {{ __('Delete') }}
                            </button>
                        </td>
                        @endif
                        @if($chancelleryRate->status == 'draft' && auth()->user()->employee->hasRole('sg'))
                            <td class="py-4 px-6 text-right border-b">
                                <button class="font-medium hover:underline blue-color" type="button"
                                    data-modal-toggle="approveRateModal-{{ $chancelleryRate->id }}">
                                    {{ __('Approve') }}
                                </button>
                            </td>
                        @endif

                        @include('partials.modals._delete-chancelleryRate')
                        @include('partials.modals._edit-chancelleryRate')
                        @include('partials.modals._approve-chancelleryRate')
                    </tr>
                @endforeach
            @else
                <tr class="border-gray-300">
                    <td colspan="7" class="px-4 py-8 border-t border-gray-300 text-lg">
                        <p class="text-center">{{ __('No Chancellery Rates Found') }}</p>
                    </td>
                </tr>
            @endunless
        </tbody>
    </table>
    @include('partials.modals._create-chancelleryRate')
</div>
<div class="mt-6 p-4">
    {{ $chancelleryRates->links() }}
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

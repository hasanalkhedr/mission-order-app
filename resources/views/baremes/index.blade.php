@extends('layouts.app')
@section('title', __('Baremes'))
@section('content')
    <nav class="flex justify-between items-center p-2 text-black font-bold">
        <div class="text-lg blue-color">
            {{ __('Baremes') }}
        </div>
        <div>
            @if (auth()->user()->employee->hasRole('director') || auth()->user()->employee->hasRole('controller') || auth()->user()->employee->hasRole('sg'))
                <button class="hover:bg-blue-700 text-white py-2 px-4 rounded-full blue-bg" data-modal-toggle="createModal">
                    {{ __('Create bareme') }}
                </button>
            @endif
        </div>
    </nav>
    @include('partials.searches._search-baremes')
    <div class="mx-2 overflow-x-auto relative shadow-md sm:rounded-lg">
        <table x-data="data()" class="w-full text-sm text-left text-gray-500" x-data="baremeData">
            @unless ($baremes->isEmpty())
                <thead class="text-s text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                            {{ __('Pays') }}
                        </th>
                        <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                            {{ __('Currency') }}
                        </th>
                        <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                            {{ __('pays_per_day') }}
                        </th>
                        <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                            {{ __('meal_cost') }}
                        </th>
                        <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                            {{ __('accomodation_cost') }}
                        </th>
                        @if (auth()->user()->employee->hasRole('director') || auth()->user()->employee->hasRole('controller') || auth()->user()->employee->hasRole('sg'))
                            <th scope="col" class="py-3 px-6">
                                <span class="sr-only">{{ __('Edit') }}</span>
                            </th>
                            <th scope="col" class="py-3 px-6">
                                <span class="sr-only">{{ __('Delete') }}</span>
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody x-ref="tbody">
                    @foreach ($baremes as $bareme)
                        <tr class="bg-white hover:bg-gray-50">
                            <td class="border-b py-4 px-6 font-bold text-gray-900 whitespace-nowrap cursor-pointer"
                                onclick="window.location.href = '{{ url(route('baremes.show', ['bareme' => $bareme->id])) }}'">
                                <div class="cursor-pointer">
                                    {{ $bareme->pays }}
                                </div>
                            </td>
                            <td class="py-4 px-6 border-b cursor-pointer">
                                <div class="cursor-pointer">
                                    {{ $bareme->currency }}
                                </div>
                            </td>
                            <td class="py-4 px-6 border-b cursor-pointer">
                                <div class="cursor-pointer">
                                    {{ $bareme->pays_per_day }}
                                </div>
                            </td>
                            <td class="py-4 px-6 border-b cursor-pointer">
                                <div class="cursor-pointer">
                                    {{ $bareme->meal_cost }}
                                </div>
                            </td>
                            <td class="py-4 px-6 border-b cursor-pointer">
                                <div class="cursor-pointer">
                                    {{ $bareme->accomodation_cost }}
                                </div>
                            </td>
                            @if (auth()->user()->employee->hasRole('director') || auth()->user()->employee->hasRole('controller') || auth()->user()->employee->hasRole('sg'))
                                <td class="py-4 px-6 text-right border-b">
                                    <button class="font-medium hover:underline blue-color" type="button"
                                        data-modal-toggle="editModal-{{ $bareme->id }}">
                                        {{ __('Edit') }}
                                    </button>
                                </td>
                                <td class="py-4 px-6 text-right border-b">
                                    <button class="font-medium text-red-600 hover:underline" type="button"
                                        data-modal-toggle="deleteModal-{{ $bareme->id }}">
                                        {{ __('Delete') }}
                                    </button>
                                </td>
                            @endif
                            @include('partials.modals._delete-bareme')
                            @include('partials.modals._edit-bareme')
                        </tr>
                    @endforeach
                @else
                    <tr class="border-gray-300">
                        <td colspan="4" class="px-4 py-8 border-t border-gray-300 text-lg">
                            <p class="text-center">{{ __('No baremes Found') }}</p>
                        </td>
                    </tr>
                @endunless
            </tbody>
        </table>

        @include('partials.modals._create-bareme')
    </div>

    <div class="mt-6 p-4">
        {{ $baremes->links() }}
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

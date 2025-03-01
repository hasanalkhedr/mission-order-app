@extends('layouts.app')
@section('content')
@section('title', __('Users'))
<nav class="flex justify-between items-center p-2 text-black font-bold">
    <div class="text-lg blue-color">
        {{ __('Users') }}
    </div>
    {{-- @hasanyrole('human_resource|sg|head') --}}
    @if (auth()->user()->employee->role == 'hr' || auth()->user()->employee->role == 'sg')
        <div>
            <button class="hover:bg-blue-700 text-white py-2 px-4 rounded-full blue-bg" data-modal-toggle="createModal">
                {{ __('Create User') }}
            </button>
        </div>
        {{-- @endhasanyrole --}}
    @endif
</nav>
@include('partials.searches._search-employees')
<div class="overflow-x-auto relative shadow-md sm:rounded-lg">
    <table x-data="data()" class="w-full text-sm text-left text-gray-500" x-data="employeeData">
        @unless ($employees->isEmpty())
            <thead class="text-s text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Name') }}
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Department') }}
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Role') }}
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Fonction Administrative') }}
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Résidence administrative') }}
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Service') }}
                    </th>
                    {{-- @if (auth()->user()->hasRole('human_resource')) --}}
                    @if (auth()->user()->employee->role == 'hr')
                        <th scope="col" class="py-3 px-6 blue-color">
                            <span class="sr-only">{{ __('Edit') }}</span>
                        </th>
                        <th scope="col" class="py-3 px-6">
                            <span class="sr-only">{{ __('Delete') }}</span>
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody x-ref="tbody">
                @foreach ($employees as $employee)
                    <tr class="bg-white hover:bg-gray-50">
                        <td class="border-b py-4 px-6 font-bold text-gray-900 whitespace-nowrap cursor-pointer"
                            onclick="window.location.href = '{{ url(route('employees.show', ['employee' => $employee->id])) }}'">
                            <div class="cursor-pointer">
                                {{ $employee->first_name }} {{ $employee->last_name }}
                            </div>
                        </td>
                        @if ($employee->department == null)
                            <td class="py-4 px-6 border-b">
                                <div class="font-bold">
                                    -
                                </div>
                            </td>
                        @else
                            <td class="py-4 px-6 border-b cursor-pointer"
                                onclick="window.location.href = '{{ url(route('departments.show', ['department' => $employee->department->id])) }}'">
                                <div class="cursor-pointer">
                                    {{ $employee->department->name }}
                                </div>
                            </td>
                        @endif
                        <td class="py-4 px-6 border-b">
                            {{-- {{(implode(' | ', $employee->getRoleNamesCustom())) }} --}}
                            {{ config('globals.roles.'.$employee->role) }}
                        </td>
                        @if ($employee->position == null)
                            <td class="py-4 px-6 border-b">
                                <div class="font-bold">
                                    -
                                </div>
                            </td>
                        @else
                            <td class="py-4 px-6 border-b cursor-pointer">
                                <div class="cursor-pointer">
                                    {{ $employee->position }}
                                </div>
                            </td>
                        @endif
                        @if ($employee->administrativ_residence == null)
                            <td class="py-4 px-6 border-b">
                                <div class="font-bold">
                                    -
                                </div>
                            </td>
                        @else
                            <td class="py-4 px-6 border-b cursor-pointer">
                                <div class="cursor-pointer">
                                    {{ $employee->administrativ_residence }}
                                </div>
                            </td>
                        @endif
                        @if ($employee->service == null)
                            <td class="py-4 px-6 border-b">
                                <div class="font-bold">
                                    -
                                </div>
                            </td>
                        @else
                            <td class="py-4 px-6 border-b cursor-pointer">
                                <div class="cursor-pointer">
                                    {{ $employee->service }}
                                </div>
                            </td>
                        @endif
                        {{-- @hasanyrole('human_resource|sg|head') --}}
                        @if (auth()->user()->employee->role == 'hr' || auth()->user()->employee->role == 'sg')
                            <td class="py-4 px-6 text-right border-b">
                                <button class="font-medium hover:underline blue-color" type="button"
                                    data-modal-toggle="editProfileModal-{{ $employee->id }}">
                                    {{ __('Edit') }}
                                </button>
                            </td>
                            <td class="py-4 px-6 text-right border-b">
                                <button class="font-medium text-red-600 hover:underline" type="button"
                                    data-modal-toggle="deleteModal-{{ $employee->id }}">
                                    {{ __('Delete') }}
                                </button>
                            </td>
                            {{-- @endhasanyrole --}}
                        @endif
                        @include('partials.modals._delete-employee')
                        @include('partials.modals._edit-employee')
                    </tr>
                @endforeach
            @else
                <tr class="border-gray-300">
                    <td colspan="4" class="px-4 py-8 border-t border-gray-300 text-lg">
                        <p class="text-center">{{ __('No Employees Found') }}</p>
                    </td>
                </tr>
            @endunless
        </tbody>
    </table>
    @include('partials.modals._create-employee')
</div>
<div class="mt-6 p-4">
    {{ $employees->links() }}
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

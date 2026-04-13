@extends('layouts.app')

@section('content')
@section('title', __('Secteurs'))
<nav class="flex justify-between items-center p-2 text-black font-bold">
    <div class="text-lg blue-color">
        {{ __('Secteurs') }}
    </div>
    <div>
        @if (auth()->user()->employee->hasRole('controller') || auth()->user()->employee->hasRole('sg'))
            <button class="hover:bg-blue-700 text-white py-2 px-4 rounded-full blue-bg" data-modal-toggle="createModal">
                {{ __('Add Department') }}
            </button>
        @endif
    </div>
</nav>
@include('partials.searches._search-departments')
<div class="mx-2 overflow-x-auto relative shadow-md sm:rounded-lg">
    <table x-data="data()" class="w-full text-sm text-left text-gray-500" x-data="departmentData">
        @unless ($departments->isEmpty())
            <thead class="text-s text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Name') }}
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Supervisor') }}
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Controller') }}
                    </th>
                    @if (auth()->user()->employee->hasRole('controller') || auth()->user()->employee->hasRole('sg'))
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
                @foreach ($departments as $department)
                    <tr class="bg-white hover:bg-gray-50">
                        <td class="border-b py-4 px-6 font-bold text-gray-900 whitespace-nowrap cursor-pointer"
                            onclick="window.location.href = '{{ url(route('departments.show', ['department' => $department->id])) }}'">
                            <div class="cursor-pointer">
                                {{ $department->name }}
                            </div>
                        </td>
                        @if ($department->manager == null)
                            <td class="py-4 px-6 border-b cursor-pointer">
                                <div class="font-bold">
                                    --
                                </div>
                            </td>
                        @else
                            <td class="py-4 px-6 border-b cursor-pointer"
                                onclick="window.location.href = '{{ url(route('employees.show', ['employee' => $department->manager_id])) }}'">
                                <div class="cursor-pointer">
                                    {{ $department->manager->first_name }} {{ $department->manager->last_name }}
                                </div>
                            </td>
                        @endif
                        @if ($department->controller == null)
                            <td class="py-4 px-6 border-b cursor-pointer">
                                <div class="font-bold">
                                    --
                                </div>
                            </td>
                        @else
                            <td class="py-4 px-6 border-b cursor-pointer"
                                onclick="window.location.href = '{{ url(route('employees.show', ['employee' => $department->controller_id])) }}'">
                                <div class="cursor-pointer">
                                    {{ $department->controller->first_name }} {{ $department->controller->last_name }}
                                </div>
                            </td>
                        @endif
                        @if (auth()->user()->employee->hasRole('controller') || auth()->user()->employee->hasRole('sg'))
                            <td class="py-4 px-6 text-right border-b">
                                <button class="font-medium hover:underline blue-color" type="button"
                                    data-modal-toggle="editModal-{{ $department->id }}">
                                    {{ __('Edit') }}
                                </button>
                            </td>
                            <td class="py-4 px-6 text-right border-b">
                                <button class="font-medium text-red-600 hover:underline" type="button"
                                    data-modal-toggle="deleteModal-{{ $department->id }}">
                                    {{ __('Delete') }}
                                </button>
                            </td>
                        @endif
                        @include('partials.modals._delete-department')
                        @include('partials.modals._edit-department')
                    </tr>
                @endforeach
            @else
                <tr class="border-gray-300">
                    <td colspan="5" class="px-4 py-8 border-t border-gray-300 text-lg">
                        <p class="text-center">{{ __('No Departments Found') }}</p>
                    </td>
                </tr>
            @endunless
        </tbody>
    </table>
@include('partials.modals._create-department')
</div>

<div class="mt-6 p-4">
    {{ $departments->links() }}
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

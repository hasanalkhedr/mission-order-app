@extends('layouts.app')
@section('content')
@section('title', __('Signatures'))
<nav class="flex justify-between items-center p-2 text-black font-bold">
    <div class="text-lg blue-color">
        {{ __('Signatures') }}
    </div>
    <div>
        @if(auth()->user()->employee->hasRole('controller') || !auth()->user()->employee->signature)
        <button class="hover:bg-blue-700 text-white py-2 px-4 rounded-full blue-bg" data-modal-toggle="createSignatureModal">
            {{ __('Add Signature') }}
        </button>
        @endif
    </div>
</nav>
@include('partials.searches._search-signatures')
<div class="overflow-x-auto relative shadow-md sm:rounded-lg">
    <table x-data="data()" class="w-full text-sm text-left text-gray-500" x-data="employeeData">
        @unless ($signatures->isEmpty())
            <thead class="text-s text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Name') }}
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Department') }}
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Roles') }}
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                        {{ __('Signature') }}
                    </th>
                    @if (auth()->user()->employee->hasRole('controller'))
                        <th scope="col" class="py-3 px-6 blue-color">
                            <span class="sr-only">{{ __('Approve') }}</span>
                        </th>
                    @endif
                    <th scope="col" class="py-3 px-6 blue-color">
                        <span class="sr-only">{{ __('Edit') }}</span>
                    </th>
                    <th scope="col" class="py-3 px-6">
                        <span class="sr-only">{{ __('Delete') }}</span>
                    </th>
                </tr>
            </thead>
            <tbody x-ref="tbody">
                @foreach ($signatures as $signature)
                    <tr class="bg-white hover:bg-gray-50">
                        <td class="border-b py-4 px-6 font-bold text-gray-900 whitespace-nowrap cursor-pointer"
                            onclick="window.location.href = '{{ url(route('signatures.show', ['signature' => $signature->id])) }}'">
                            <div class="cursor-pointer">
                                {{ $signature->employee->first_name }} {{ $signature->employee->last_name }}
                            </div>
                        </td>
                        @if ($signature->employee->department == null)
                            <td class="py-4 px-6 border-b">
                                <div class="font-bold">
                                    -
                                </div>
                            </td>
                        @else
                            <td class="py-4 px-6 border-b">
                                <div class="cursor-pointer">
                                    {{ $signature->employee->department->name }}
                                </div>
                            </td>
                        @endif
                        <td class="py-4 px-6 border-b">
                            {{ implode(' | ', $signature->employee->getRoles()) }}
                            {{-- {{ config('globals.roles.'.$signature->role) }} --}}
                        </td>
                        <td class="py-4 px-6 border-b">
                            <div class="max-w-28">
                                <img src="{{ asset('storage/' . $signature->signature_path) }}" alt=""
                                    class="w-24 h-auto">
                            </div>
                        </td>
                        {{-- @hasanyrole('human_resource|sg|head') --}}
                        @if ($signature->status == 'draft' && auth()->user()->employee->hasRole('controller'))
                            <td class="py-4 px-6 text-right border-b">
                                <button class="font-medium hover:underline blue-color" type="button" data-modal-toggle="approveSignatureModal-{{ $signature->id }}">
                                    {{ __('Approve') }}
                                </button>
                            </td>
                        @endif
                        <td class="py-4 px-6 text-right border-b">
                            <button class="font-medium hover:underline blue-color" type="button"
                                data-modal-toggle="editSignatureModal-{{ $signature->id }}">
                                {{ __('Edit') }}
                            </button>
                        </td>
                        <td class="py-4 px-6 text-right border-b">
                            <button class="font-medium text-red-600 hover:underline" type="button"
                                data-modal-toggle="deleteSignatureModal-{{ $signature->id }}">
                                {{ __('Delete') }}
                            </button>
                        </td>
                        {{-- @endhasanyrole --}}
                        @include('partials.modals._delete-signature')
                        @include('partials.modals._edit-signature')
                        @include('partials.modals._approve-signature')
                    </tr>
                @endforeach
            @else
                <tr class="border-gray-300">
                    <td colspan="4" class="px-4 py-8 border-t border-gray-300 text-lg">
                        <p class="text-center">{{ __('No signatures Found') }}</p>
                    </td>
                </tr>
            @endunless
        </tbody>
    </table>
    @include('partials.modals._create-signature')
</div>
<div class="mt-6 p-4">
    {{ $signatures->links() }}
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

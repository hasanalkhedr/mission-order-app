@extends('layouts.app')
@section('title', __('tableau de bord'))
@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Tableau de Bord des Ordres de Mission</h1>
            <p class="text-gray-600">Gérez vos ordres de mission en un seul endroit</p>
        </div>


        <!-- Main Action Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{route('mission_orders.index')}}"
               class="group relative bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-50 group-hover:bg-blue-100 transition-colors duration-200">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Ordre de mission</h3>
                        <p class="mt-1 text-sm text-gray-500">les ordres de mission nécessitent votre attention</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-lg font-extrabold bg-blue-100 text-blue-800">
                        {{ $missionCount }}
                    </span>
                </div>
            </a>

            <a href="{{route('mission_orders.m_index')}}"
               class="group relative bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-50 group-hover:bg-blue-100 transition-colors duration-200">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">MÉMOIRE DE FRAIS</h3>
                        <p class="mt-1 text-sm text-gray-500">les ordres de mission nécessitent votre attention</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-lg font-extrabold bg-blue-100 text-blue-800">
                        {{ $memoireCount }}
                    </span>
                </div>
            </a>

            <a href="{{route('tournees.index')}}"
               class="group relative bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-50 group-hover:bg-blue-100 transition-colors duration-200">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Tournees</h3>
                        <p class="mt-1 text-sm text-gray-500">les ordres de mission nécessitent votre attention</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-lg font-extrabold bg-blue-100 text-blue-800">
                        {{ $tourneeCount }}
                    </span>
                </div>
            </a>

            <a href="{{route('tournees.m_index')}}"
               class="group relative bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-50 group-hover:bg-blue-100 transition-colors duration-200">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">MÉMOIRE DE FRAIS / TOURNEES</h3>
                        <p class="mt-1 text-sm text-gray-500">les ordres de mission nécessitent votre attention</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-lg font-extrabold bg-blue-100 text-blue-800">
                        {{ $tourneeMemoireCount }}
                    </span>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@extends('layouts.error')

@section('content')
@section('title', __('ERREUR INTERNE DU SERVEUR'))
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="text-center">
        <h1 class="text-2xl font-bold text-gray-900">New missions cannot be created until admin updates the chancellery rate for this month.</p>
        <a href="{{ url('/') }}" class="mt-6 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg">
            Retourner à l'accueil
        </a>
    </div>
</div>

@endsection

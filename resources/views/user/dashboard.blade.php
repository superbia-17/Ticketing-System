@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Welcome, {{ Auth::user()->name }}!</h1>
                <p class="text-gray-600 mb-6">Role: {{ ucfirst(Auth::user()->role) }}</p>
                @if(Auth::user()->role === 'student')
                    <p class="text-gray-600 mb-6">NIM: {{ Auth::user()->nim }}</p>
                @endif
                <div class="space-y-4">
                    <a href="/tickets/create" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Submit New Ticket
                    </a>
                    <a href="/tickets/my" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        View My Tickets
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
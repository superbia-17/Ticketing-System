@extends('layouts.app')

@section('content')
<div class="text-center py-20">
    <h1 class="text-4xl font-bold mb-4">UPB Ticketing System</h1>
    <p class="text-xl text-gray-600 mb-8">Need help? Submit a ticket to our support team.</p>
    
    <div class="space-x-4">
        <a href="/login" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold">Submit a Ticket</a>
        
        <a href="/admin/login" class="text-blue-600 border border-blue-600 px-6 py-3 rounded-lg font-semibold">Staff Login</a>
    </div>
</div>
@endsection
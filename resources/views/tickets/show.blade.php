@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('tickets.my') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                ← Back to My Tickets
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="border-b border-gray-200 pb-4 mb-4">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $ticket->title }}</h1>
                    <p class="text-sm text-gray-500 mt-1">Ticket #{{ $ticket->ticket_number }} • {{ $ticket->created_at->format('M d, Y H:i') }}</p>
                    <div class="flex items-center space-x-4 mt-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($ticket->priority === 'low') bg-green-100 text-green-800
                            @elseif($ticket->priority === 'medium') bg-yellow-100 text-yellow-800
                            @elseif($ticket->priority === 'high') bg-orange-100 text-orange-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($ticket->priority) }} Priority
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($ticket->status === 'open') bg-blue-100 text-blue-800
                            @elseif($ticket->status === 'in_progress') bg-yellow-100 text-yellow-800
                            @elseif($ticket->status === 'resolved') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                        <span class="text-sm text-gray-600">Category: {{ $ticket->category->name ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                    <p class="text-gray-700 bg-gray-50 p-4 rounded">{{ $ticket->description }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Conversation</h3>
                    @if($ticket->responses->count() > 0)
                        <div class="space-y-4">
                            @foreach($ticket->responses as $response)
                                <div class="flex {{ $response->is_internal ? 'justify-start' : 'justify-end' }}">
                                    <div class="max-w-lg px-4 py-2 rounded-lg {{ $response->is_internal ? 'bg-blue-100 text-blue-900' : 'bg-green-100 text-green-900' }}">
                                        <p class="text-sm font-medium">{{ $response->author_name }} ({{ $response->is_internal ? 'Admin' : 'You' }})</p>
                                        <p class="text-sm">{{ $response->message }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $response->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No responses yet.</p>
                    @endif
                </div>

                @if($ticket->allow_user_reply)
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Add Response</h3>
                        <form method="POST" action="{{ route('tickets.reply', $ticket->id) }}">
                            @csrf
                            <div class="mb-4">
                                <textarea name="response" rows="4" required
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                          placeholder="Type your response here...">{{ old('response') }}</textarea>
                                @error('response')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Send Response
                            </button>
                        </form>
                    </div>
                @else
                    <div class="border-t border-gray-200 pt-6">
                        <p class="text-gray-500">You cannot reply to this ticket yet. Please wait for admin response.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('tickets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        Ticket::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'priority' => $request->priority,
            'status' => 'open',
            'reporter_name' => Auth::user()->name,
            'reporter_email' => Auth::user()->email,
            'reporter_nim' => Auth::user()->nim,
            'reporter_phone' => Auth::user()->phone ?? null,
        ]);

        return redirect()->route('tickets.my')->with('success', 'Ticket created successfully.');
    }

    public function my()
    {
        $tickets = Auth::user()->tickets()->with('category')->orderBy('created_at', 'desc')->get();
        return view('tickets.my', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = Ticket::with('responses.user', 'category')->findOrFail($id);

        // Ensure user can only view their own tickets
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        return view('tickets.show', compact('ticket'));
    }

    public function reply(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$ticket->allow_user_reply) {
            return back()->withErrors(['You are not allowed to reply to this ticket yet.']);
        }

        $request->validate([
            'response' => 'required|string',
        ]);

        TicketResponse::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->response,
            'is_internal' => false,
        ]);

        return back()->with('success', 'Response added successfully.');
    }
}
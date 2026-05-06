<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Dashboard Utama: Menampilkan statistik global dan 5 tiket terbaru.
     */
    public function index()
    {
        // Statistik untuk kotak informasi (Milik User sendiri)
        $myTickets = Auth::user()->tickets()->get();

        // Mengambil hanya 5 tiket terbaru secara global untuk Dashboard
        $allTickets = Ticket::with(['category', 'submitter'])
            ->latest()
            ->take(5) 
            ->get();

        return view('user.dashboard', [
            'tickets' => $myTickets,
            'allTickets' => $allTickets
        ]);
    }

    /**
     * Menampilkan form pembuatan tiket.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('tickets.create', compact('categories'));
    }

    /**
     * Menyimpan tiket baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:low,medium,high', 
        ]);

        // --- LOGIKA GENERATOR NOMOR TIKET ---
        $latestTicket = Ticket::latest()->first();
        $nextId = $latestTicket ? $latestTicket->id + 1 : 1;
        $ticketNumber = 'TKT-' . date('Y') . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        // ------------------------------------

        Ticket::create([
            'ticket_number' => $ticketNumber, 
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
            'allow_user_reply' => true,
        ]);

        return redirect()->route('dashboard')->with('success', 'Ticket created successfully.');
    }

    /**
     * Menampilkan daftar tiket milik user yang sedang login.
     */
    public function my()
    {
        $tickets = Auth::user()->tickets()
            ->with('category')
            ->latest()
            ->get();

        return view('tickets.my', compact('tickets'));
    }

    /**
     * Menampilkan semua tiket (Riwayat Global) dengan Pagination.
     */
    public function all()
    {
        // Menggunakan paginate(10) untuk tampilan per halaman
        $tickets = Ticket::with(['submitter', 'category'])
            ->latest()
            ->paginate(10); 

        return view('tickets.all', compact('tickets'));
    }

    /**
     * Menampilkan detail tiket tertentu.
     */
    public function show($id)
    {
        $ticket = Ticket::with(['responses.user', 'category', 'submitter'])->findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Menambahkan balasan pada tiket.
     */
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
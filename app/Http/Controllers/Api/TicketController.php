<?php
namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Http\Request;
 
class TicketController extends Controller
{
    // Staff/admin: list all tickets with filters
    public function index(Request $request)
    {
        $tickets = Ticket::with(['category', 'assignee'])
            ->when($request->status,   fn($q) => $q->where('status',   $request->status))
            ->when($request->priority, fn($q) => $q->where('priority', $request->priority))
            ->when($request->category, fn($q) => $q->where('category_id', $request->category))
            ->when($request->assigned_to, fn($q) => $q->assignedTo($request->assigned_to))
            ->when($request->search, fn($q) => $q
                ->where('title', 'like', "%{$request->search}%")
                ->orWhere('ticket_number', 'like', "%{$request->search}%")
                ->orWhere('reporter_email', 'like', "%{$request->search}%")
            )
            ->latest()
            ->paginate(20);
 
        return response()->json($tickets);
    }
 
    // Public: submit a ticket (guest or student)
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'reporter_name'  => 'required|string|max:255',
            'reporter_email' => 'required|email',
            'reporter_phone' => 'nullable|string|max:20',
            'category_id'    => 'required|exists:categories,id',
            'priority'       => 'in:low,medium,high',
            'attachments'    => 'nullable|array|max:5',
            'attachments.*'  => 'file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);
 
        // Link to user account if logged in
        if (auth('sanctum')->check()) {
            $data['user_id'] = auth('sanctum')->id();
        }
 
        // Auto-assign based on category default handler
        $category = Category::find($data['category_id']);
        if ($category?->assigned_to) {
            $data['assigned_to'] = $category->assigned_to;
        }
 
        $ticket = Ticket::create($data);
 
        // Handle file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store("tickets/{$ticket->id}", 'public');
                $ticket->attachments()->create([
                    'file_path'     => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type'     => $file->getMimeType(),
                    'file_size'     => $file->getSize(),
                ]);
            }
        }
 
        return response()->json([
            'message'       => 'Ticket submitted successfully.',
            'ticket_number' => $ticket->ticket_number,
            'ticket'        => $ticket->load('category', 'attachments'),
        ], 201);
    }
 
    // Public: track ticket by ticket_number (no auth needed)
    public function track(string $ticketNumber)
    {
        $ticket = Ticket::where('ticket_number', $ticketNumber)
            ->with(['category', 'publicResponses', 'attachments', 'statusHistories'])
            ->firstOrFail();
 
        return response()->json($ticket);
    }
 
    // Staff/admin: show full ticket detail
    public function show(Ticket $ticket)
    {
        return response()->json(
            $ticket->load(['category', 'responses.author', 'attachments', 'statusHistories.changedBy', 'assignee', 'submitter'])
        );
    }
 
    // Student: list own tickets
    public function myTickets(Request $request)
    {
        $tickets = Ticket::where('user_id', $request->user()->id)
            ->with('category')
            ->latest()
            ->paginate(15);
 
        return response()->json($tickets);
    }
 
    // Staff/admin: update ticket (status, priority, assignment)
    public function update(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'status'      => 'sometimes|in:open,in_progress,resolved,closed',
            'priority'    => 'sometimes|in:low,medium,high',
            'assigned_to' => 'sometimes|nullable|exists:users,id',
            'category_id' => 'sometimes|exists:categories,id',
        ]);
 
        // resolved_at is auto-set when status becomes resolved
        if (isset($data['status']) && $data['status'] === 'resolved') {
            $data['resolved_at'] = now();
        }
 
        $ticket->update($data);
 
        return response()->json([
            'message' => 'Ticket updated.',
            'ticket'  => $ticket->fresh()->load('category', 'assignee'),
        ]);
    }
 
    // Staff/admin quick-resolve
    public function resolve(Ticket $ticket)
    {
        $ticket->resolve();
        return response()->json(['message' => 'Ticket resolved.', 'ticket' => $ticket]);
    }
 
    // Admin: delete ticket
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return response()->json(['message' => 'Ticket deleted.']);
    }
 
    // Admin: dashboard stats
    public function stats()
    {
        return response()->json([
            'total'       => Ticket::count(),
            'open'        => Ticket::where('status', 'open')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'resolved'    => Ticket::where('status', 'resolved')->count(),
            'closed'      => Ticket::where('status', 'closed')->count(),
            'high_priority' => Ticket::where('priority', 'high')
                ->whereNotIn('status', ['resolved', 'closed'])->count(),
            'this_month'  => Ticket::whereMonth('created_at', now()->month)->count(),
        ]);
    }
}

?>
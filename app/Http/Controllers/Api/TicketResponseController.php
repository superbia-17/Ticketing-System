<?php
namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
 
class TicketResponseController extends Controller
{
    // Staff/admin: view all responses including internal notes
    public function index(Ticket $ticket)
    {
        return response()->json(
            $ticket->responses()->with('author')->get()
        );
    }
 
    // Authenticated user: reply to own ticket
    public function store(Request $request, Ticket $ticket)
    {
        // Users can only respond to their own tickets
        if ($ticket->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }
 
        $data = $request->validate(['message' => 'required|string']);
 
        $response = $ticket->responses()->create([
            'user_id'     => $request->user()->id,
            'message'     => $data['message'],
            'is_internal' => false,
        ]);
 
        return response()->json($response->load('author'), 201);
    }
 
    // Staff/admin: post a reply or internal note
    public function storeStaff(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'message'     => 'required|string',
            'is_internal' => 'boolean',
        ]);
 
        $response = $ticket->responses()->create([
            'user_id'     => $request->user()->id,
            'message'     => $data['message'],
            'is_internal' => $data['is_internal'] ?? false,
        ]);
 
        return response()->json($response->load('author'), 201);
    }
}


?>
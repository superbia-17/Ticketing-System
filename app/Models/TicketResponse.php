<?php
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class TicketResponse extends Model
{
    protected $fillable = [
        'ticket_id', 'user_id', 'responder_name',
        'message', 'is_internal',
    ];
 
    protected $casts = [
        'is_internal' => 'boolean',
    ];
 
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
 
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
 
    // Returns display name regardless of guest or user
    public function getAuthorNameAttribute(): string
    {
        return $this->author?->name ?? $this->responder_name ?? 'Anonymous';
    }
}

?>
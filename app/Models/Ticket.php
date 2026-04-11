<?php
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
 
class Ticket extends Model
{
    protected $fillable = [
        'ticket_number', 'title', 'description',
        'reporter_name', 'reporter_email', 'reporter_phone',
        'user_id', 'category_id', 'status', 'priority',
        'assigned_to', 'resolved_at',
    ];
 
    protected $casts = [
        'resolved_at' => 'datetime',
    ];
 
    // Auto-generate ticket number before creating
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($ticket) {
            $ticket->ticket_number = self::generateTicketNumber();
        });
 
        // Auto-log status history on update
        static::updating(function ($ticket) {
            if ($ticket->isDirty('status')) {
                StatusHistory::create([
                    'ticket_id'  => $ticket->id,
                    'changed_by' => auth()->id(),
                    'old_status' => $ticket->getOriginal('status'),
                    'new_status' => $ticket->status,
                ]);
            }
        });
    }
 
    public static function generateTicketNumber(): string
    {
        $year  = now()->year;
        $count = self::whereYear('created_at', $year)->count() + 1;
        return sprintf('TKT-%d-%05d', $year, $count);
    }
 
    // Relationships
    public function submitter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
 
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
 
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
 
    public function responses()
    {
        return $this->hasMany(TicketResponse::class)->orderBy('created_at');
    }
 
    public function publicResponses()
    {
        return $this->hasMany(TicketResponse::class)
            ->where('is_internal', false)
            ->orderBy('created_at');
    }
 
    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }
 
    public function statusHistories()
    {
        return $this->hasMany(StatusHistory::class)->orderBy('created_at');
    }
 
    // Scopes
    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', 'open');
    }
 
    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', 'in_progress');
    }
 
    public function scopeByPriority(Builder $query, string $priority): Builder
    {
        return $query->where('priority', $priority);
    }
 
    public function scopeAssignedTo(Builder $query, int $userId): Builder
    {
        return $query->where('assigned_to', $userId);
    }
 
    // Helpers
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }
 
    public function isResolved(): bool
    {
        return in_array($this->status, ['resolved', 'closed']);
    }
 
    public function resolve(): void
    {
        $this->update([
            'status'      => 'resolved',
            'resolved_at' => now(),
        ]);
    }
}

?>
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ticket_number',
        'title',
        'description',
        'category_id',
        'priority',
        'status',
        'assigned_to',
        'reporter_name',
        'reporter_email',
        'reporter_nim',
        'reporter_phone',
        'allow_user_reply'
    ];



    protected static function booted(): void
    {
        static::creating(function ($ticket) {
            if ($ticket->category_id && is_null($ticket->assigned_to)) {
                $category = Category::find($ticket->category_id);
                if ($category) {
                    $ticket->assigned_to = $category->assigned_to;
                }
            }
        });
    }
    /**
     * Resolve the ticket by changing its status to 'resolved'.
     */
    public function resolve()
    {
        $oldStatus = $this->status;

        $this->update(['status' => 'resolved']);

        // Record status change in history
        $this->statusHistories()->create([
            'old_status' => $oldStatus,
            'new_status' => 'resolved',
        ]);
    }

    /**
     * Method untuk mengecek apakah tiket sudah selesai.
     * Digunakan oleh Panel Admin dan Dashboard.
     */
    public function isResolved()
    {
        return in_array($this->status, ['resolved', 'closed']);
    }

    /**
     * Method tambahan untuk mengecek apakah tiket sedang diproses.
     */
    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    /**
     * Relasi ke sejarah perubahan status (StatusHistory).
     */
    public function statusHistories()
    {
        return $this->hasMany(StatusHistory::class);
    }

    /**
     * Relasi ke kategori tiket.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke user yang membuat tiket (submitter).
     */
    public function submitter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke user (staf/admin) yang menangani tiket.
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Relasi ke semua balasan tiket.
     */
    public function responses()
    {
        return $this->hasMany(TicketResponse::class);
    }
}
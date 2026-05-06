<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
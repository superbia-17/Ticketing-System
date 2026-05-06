<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusHistory extends Model
{
    use HasFactory;

    protected $table = 'status_histories';

    protected $fillable = [
        'ticket_id',
        'status',
        'changed_by',
        'comment'
    ];

    /**
     * Relasi kembali ke Tiket.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Relasi ke User yang mengubah status.
     */
    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
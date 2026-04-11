<?php
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class TicketAttachment extends Model
{
    protected $fillable = [
        'ticket_id', 'file_path', 'original_name',
        'mime_type', 'file_size',
    ];
 
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
 
    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes < 1024) return "{$bytes} B";
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }
}

?>

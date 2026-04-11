<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
 
class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'assigned_to', 'is_active'];
 
    protected $casts = [
        'is_active' => 'boolean',
    ];
 
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            $category->slug = $category->slug ?? Str::slug($category->name);
        });
    }
 
    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
 
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}

?>
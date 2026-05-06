<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi (mass assignable).
     */
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role', 
        'nim',
        'phone', // Menambahkan phone agar sinkron dengan TicketController
    ];

    /**
     * Atribut yang disembunyikan untuk serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atribut yang harus dicasting ke tipe data tertentu.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // --- RELATIONSHIPS ---

    /**
     * Tiket yang dibuat oleh user ini.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    /**
     * Tiket yang ditugaskan kepada user ini (khusus staff/admin).
     */
    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    /**
     * Balasan/respons yang diberikan oleh user ini pada tiket.
     */
    public function responses()
    {
        return $this->hasMany(TicketResponse::class);
    }

    // --- FILAMENT ACCESS ---

    /**
     * Menentukan apakah user bisa mengakses panel admin Filament.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isStaff();
    }

    // --- ROLE HELPERS ---

    /**
     * Mengecek apakah user adalah Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Mengecek apakah user memiliki akses Staff (Admin atau Staff).
     */
    public function isStaff(): bool
    {
        return in_array($this->role, ['admin', 'staff', 'super_admin']);
    }

    /**
     * Mengecek apakah user adalah Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Mengecek apakah user adalah Mahasiswa.
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Mengecek apakah user adalah Publik.
     */
    public function isPublic(): bool
    {
        return $this->role === 'public';
    }
}
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    public function blogs()
    {
       return $this->hasMany(Blog::class);
    }

    // public function getAvatarUrlAttribute()
    // {
    //     if ($this->avatar) {
    //         return asset('storage/' . $this->avatar);
    //     }

    //     return asset('default-avatar.png');
    // }
}

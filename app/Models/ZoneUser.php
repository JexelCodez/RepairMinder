<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneUser extends Model
{
    use HasFactory;

    protected $table = 'zone_users';

    protected $fillable = [
        'zone_name',
    ];

    /**
     * Relasi ke User (satu zone bisa memiliki banyak user).
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_zone');
    }

}

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

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}

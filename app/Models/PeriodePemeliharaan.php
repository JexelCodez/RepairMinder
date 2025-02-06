<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Sushi\Sushi;

class PeriodePemeliharaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_barang',
        'periode',
        'deskripsi',
        'created_at',
        'updated_at',
    ];
}

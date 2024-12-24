<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelImages extends Model
{
    const UPDATED_AT = null;
    use HasFactory;
    protected $table  = 'tb_hotel_photos';
    protected $primaryKey = 'id';
    protected $fillable =
    [
        'id_hotel',
        'photo',
        'is_main',
        'room_tp',
        'entry_by',
    ];
}

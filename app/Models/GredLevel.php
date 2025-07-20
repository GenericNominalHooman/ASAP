<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GredLevel extends Model
{
    protected $table = 'user_gred_levels';

    protected $fillable = [
        'g_level',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}

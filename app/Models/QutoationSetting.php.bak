<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QutoationSetting extends Model
{
    protected $table = 'quotation_settings';

    protected $fillable = [
        'g_level',
        'specialization',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}

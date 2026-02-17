<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    protected $table = 'user_specializations';

    protected $fillable = [
        'specialization',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}

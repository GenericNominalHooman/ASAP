<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $table = 'quotation';

    protected $fillable = [
        'quotation_no',
        'advert_file_name',
        'advert_file_path',
        'title',
        'specializations',
        'begin_register_date',
        'end_register_date',
        'closing_date',
        'site_visit_location',
        'site_visit_date',
        'organization',
        'status',
    ];

    protected $casts = [
        'begin_register_date' => 'datetime',
        'end_register_date' => 'datetime',
        'closing_date' => 'datetime',
        'site_visit_date' => 'datetime',
    ];
}

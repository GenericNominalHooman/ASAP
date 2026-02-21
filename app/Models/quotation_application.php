<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\QuotationApplicationFactory;

class quotation_application extends Model
{
    /** @use HasFactory<\Database\Factories\QuotationApplicationFactory> */
    use HasFactory;

    protected $table = 'quotation_applications';

    protected $fillable = [
        'file_name',
        'title',
        'specializations',
        'begin_register_date',
        'end_register_date',
        'closing_date',
        'slip_path',
        'site_visit_location',
        'site_visit_date',
        'advert_path',
        'serial_number',
        'owner',
        'organization',
        'status',
        'user_id',
    ];

    protected static function newFactory()
    {
        return QuotationApplicationFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserQuotationApplicationTimer extends Model
{
    use HasFactory;

    protected $table = 'user_quotation_application_timer';

    protected $fillable = [
        'user_id',
        'timing',
        'enabled',
    ];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
        ];
    }

    /**
     * Get the user that owns the quotation application timer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

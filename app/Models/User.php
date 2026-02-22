<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\GredLevel;
use App\Models\Specialization;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'ssm_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function quotationApplications()
    {
        return $this->hasMany(quotation_application::class);
    }

    // public function quotationSettings()
    // {
    //     return $this->hasMany(QutoationSetting::class);
    // }

    public function quotationApplicationTimers()
    {
        return $this->hasMany(UserQuotationApplicationTimer::class);
    }

    // User can have many CIDB gred level code
    public function gredLevels()
    {
        return $this->hasMany(GredLevel::class);
    }

    // User can have many CIDB specialization code
    public function specializations()
    {
        return $this->hasMany(Specialization::class);
    }
}

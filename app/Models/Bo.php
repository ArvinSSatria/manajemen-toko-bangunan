<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bo extends Model
{
    use HasFactory;

    protected $table = 'bo';

    protected $fillable = [
        'name',
        'phone',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(BoOrder::class, 'bo_id');
    }

    public function getTotalDebtAttribute()
    {
        return $this->orders()->where('status', 'UNPAID')->sum('remaining_balance');
    }
}

<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'store_id',
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

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Check if user is Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    /**
     * Check if user is Store Admin.
     */
    public function isStoreAdmin(): bool
    {
        return $this->hasRole('store_admin');
    }

    /**
     * Check if user is Cashier.
     */
    public function isCashier(): bool
    {
        return $this->hasRole('cashier');
    }

    /**
     * Check if user is Central Sales.
     */
    public function isCentralSales(): bool
    {
        return $this->hasRole('central_sales');
    }

    /**
     * Get the store ID the user should operate on.
     * Super Admin may switch stores via session.
     */
    public function getActiveStoreId(): ?int
    {
        if ($this->isSuperAdmin()) {
            return session('active_store_id', $this->store_id);
        }

        return $this->store_id;
    }
}

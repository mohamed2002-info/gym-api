<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'duration_days',
        'price',
    ];

    protected $casts = [
        'duration_days' => 'integer',
        'price'         => 'decimal:2',
    ];

    /**
     * Relation : un type d'abonnement possède plusieurs membres.
     * (Relation entre deux tables : subscription_types -> members)
     */
    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}

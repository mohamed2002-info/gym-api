<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'join_date',
        'expiry_date',
        'subscription_type_id',
    ];

    protected $casts = [
        'join_date'   => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Champ calculé : statut actif / expiré selon la date d'expiration.
     */
    protected $appends = ['status'];

    /**
     * Relation : un membre appartient à un type d'abonnement.
     * (Relation entre deux tables : members -> subscription_types)
     */
    public function subscriptionType(): BelongsTo
    {
        return $this->belongsTo(SubscriptionType::class);
    }

    /**
     * Accessor pour le statut : "active" si l'abonnement n'est pas expiré.
     */
    public function getStatusAttribute(): string
    {
        if (! $this->expiry_date) {
            return 'expired';
        }

        return $this->expiry_date->isFuture() || $this->expiry_date->isToday()
            ? 'active'
            : 'expired';
    }
}

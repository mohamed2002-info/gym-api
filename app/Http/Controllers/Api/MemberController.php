<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberRequest;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Liste paginée avec recherche et filtres (nom, statut, type d'abonnement).
     */
    public function index(Request $request)
    {
        $query = Member::with('subscriptionType');

        // Recherche par nom / prénom / email
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtre par type d'abonnement
        if ($typeId = $request->query('subscription_type_id')) {
            $query->where('subscription_type_id', $typeId);
        }

        // Filtre par statut (active / expired) — calculé sur expiry_date
        if ($status = $request->query('status')) {
            $today = now()->toDateString();
            if ($status === 'active') {
                $query->whereDate('expiry_date', '>=', $today);
            } elseif ($status === 'expired') {
                $query->whereDate('expiry_date', '<', $today);
            }
        }

        $perPage = (int) $request->query('per_page', 10);

        $members = $query->orderByDesc('created_at')->paginate($perPage);

        return MemberResource::collection($members);
    }

    /**
     * Création d'un membre.
     */
    public function store(MemberRequest $request)
    {
        $member = Member::create($request->validated());

        return new MemberResource($member->load('subscriptionType'));
    }

    /**
     * Affichage d'un membre.
     */
    public function show(Member $member)
    {
        return new MemberResource($member->load('subscriptionType'));
    }

    /**
     * Mise à jour d'un membre.
     */
    public function update(MemberRequest $request, Member $member)
    {
        $member->update($request->validated());

        return new MemberResource($member->load('subscriptionType'));
    }

    /**
     * Suppression d'un membre.
     */
    public function destroy(Member $member)
    {
        $member->delete();

        return response()->json(['message' => 'Membre supprimé avec succès.']);
    }

    /**
     * Renouvellement de l'abonnement d'un membre :
     * on ajoute la durée du type d'abonnement à la date d'expiration.
     */
    public function renew(Member $member)
    {
        $member->load('subscriptionType');

        $base = $member->expiry_date->isFuture()
            ? $member->expiry_date
            : now();

        $member->update([
            'expiry_date' => $base->copy()->addDays($member->subscriptionType->duration_days),
        ]);

        return new MemberResource($member->fresh('subscriptionType'));
    }
}

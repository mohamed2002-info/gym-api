<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionTypeRequest;
use App\Http\Resources\SubscriptionTypeResource;
use App\Models\SubscriptionType;

class SubscriptionTypeController extends Controller
{
    /**
     * Liste de tous les types d'abonnement.
     */
    public function index()
    {
        return SubscriptionTypeResource::collection(
            SubscriptionType::orderBy('duration_days')->get()
        );
    }

    /**
     * Création d'un type d'abonnement.
     */
    public function store(SubscriptionTypeRequest $request)
    {
        $type = SubscriptionType::create($request->validated());

        return new SubscriptionTypeResource($type);
    }

    /**
     * Affichage d'un type d'abonnement.
     */
    public function show(SubscriptionType $subscriptionType)
    {
        return new SubscriptionTypeResource($subscriptionType);
    }

    /**
     * Mise à jour d'un type d'abonnement.
     */
    public function update(SubscriptionTypeRequest $request, SubscriptionType $subscriptionType)
    {
        $subscriptionType->update($request->validated());

        return new SubscriptionTypeResource($subscriptionType);
    }

    /**
     * Suppression d'un type d'abonnement.
     */
    public function destroy(SubscriptionType $subscriptionType)
    {
        $subscriptionType->delete();

        return response()->json(['message' => 'Type d\'abonnement supprimé avec succès.']);
    }
}

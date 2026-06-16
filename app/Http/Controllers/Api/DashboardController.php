<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Statistiques globales pour le tableau de bord.
     */
    public function stats(): JsonResponse
    {
        $today = now()->toDateString();

        $activeMembers  = Member::whereDate('expiry_date', '>=', $today)->count();
        $expiredMembers = Member::whereDate('expiry_date', '<', $today)->count();

        // Revenus du mois : somme des prix des abonnements des membres
        // inscrits durant le mois courant.
        $monthlyRevenue = Member::whereMonth('join_date', now()->month)
            ->whereYear('join_date', now()->year)
            ->join('subscription_types', 'members.subscription_type_id', '=', 'subscription_types.id')
            ->sum('subscription_types.price');

        // Évolution des inscriptions sur les 6 derniers mois.
        $registrations = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Member::whereMonth('join_date', $month->month)
                ->whereYear('join_date', $month->year)
                ->count();

            $registrations[] = [
                'month' => $month->translatedFormat('M Y'),
                'count' => $count,
            ];
        }

        // 5 membres les plus récents
        $recentMembers = Member::with('subscriptionType')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return response()->json([
            'active_members'  => $activeMembers,
            'expired_members' => $expiredMembers,
            'total_members'   => $activeMembers + $expiredMembers,
            'monthly_revenue' => (float) $monthlyRevenue,
            'registrations'   => $registrations,
            'recent_members'  => MemberResource::collection($recentMembers),
        ]);
    }
}

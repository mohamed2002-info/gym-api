<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\SubscriptionType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Unique compte : l'administrateur (propriétaire de la salle).
        // Pas d'inscription publique : c'est le seul accès à l'application.
        User::firstOrCreate(
            ['email' => 'admin@gym.com'],
            [
                'name'     => 'Administrateur',
                'phone'    => '+216 20 111 111',
                'password' => 'password',
                'role'     => 'admin',
            ]
        );

        // Types d'abonnement
        $monthly = SubscriptionType::firstOrCreate(
            ['name' => 'Mensuel'],
            ['duration_days' => 30, 'price' => 50.00]
        );
        $quarterly = SubscriptionType::firstOrCreate(
            ['name' => 'Trimestriel'],
            ['duration_days' => 90, 'price' => 130.00]
        );
        $yearly = SubscriptionType::firstOrCreate(
            ['name' => 'Annuel'],
            ['duration_days' => 365, 'price' => 450.00]
        );

        $types = [$monthly, $quarterly, $yearly];

        // Quelques membres de démonstration (actifs et expirés)
        $samples = [
            ['Ahmed', 'Ben Salah', 'ahmed@example.com', 5],
            ['Sarra', 'Trabelsi', 'sarra@example.com', 40],
            ['Mehdi', 'Khelifi', 'mehdi@example.com', -10],   // expiré
            ['Ines', 'Gharbi', 'ines@example.com', 120],
            ['Youssef', 'Mansour', 'youssef@example.com', -3], // expiré
        ];

        foreach ($samples as $i => [$first, $last, $email, $daysLeft]) {
            $type = $types[$i % count($types)];

            Member::firstOrCreate(
                ['email' => $email],
                [
                    'first_name'           => $first,
                    'last_name'            => $last,
                    'phone'                => '+216 2' . rand(0, 9) . ' ' . rand(100, 999) . ' ' . rand(100, 999),
                    'join_date'            => Carbon::now()->subDays($type->duration_days - $daysLeft),
                    'expiry_date'          => Carbon::now()->addDays($daysLeft),
                    'subscription_type_id' => $type->id,
                ]
            );
        }
    }
}

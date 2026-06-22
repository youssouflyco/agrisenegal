<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\AdminActivityLog;
use App\Models\SalesRecord;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPlatformStats();
        $this->seedSalesRecords();
        $this->seedDemoUsers();
        $this->seedAuditLogs();
    }

    private function seedPlatformStats(): void
    {
        $stats = [
            ['key' => 'orders', 'value' => 1248, 'amount' => null],
            ['key' => 'sales', 'value' => 986, 'amount' => null],
            ['key' => 'claims', 'value' => 23, 'amount' => null],
            ['key' => 'withdrawals', 'value' => 156, 'amount' => null],
            ['key' => 'revenue', 'value' => 0, 'amount' => 45875000],
        ];

        foreach ($stats as $stat) {
            DB::table('platform_stats')->updateOrInsert(
                ['key' => $stat['key']],
                array_merge($stat, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }

    private function seedSalesRecords(): void
    {
        if (SalesRecord::exists()) {
            return;
        }

        for ($i = 365; $i >= 0; $i--) {
            $date = now()->subDays($i);
            SalesRecord::create([
                'sale_date' => $date->toDateString(),
                'amount' => rand(150000, 2500000),
                'orders_count' => rand(5, 45),
            ]);
        }
    }

    private function seedDemoUsers(): void
    {
        $roles = [
            [UserRole::Client, 45],
            [UserRole::Producer, 28],
            [UserRole::Distributor, 12],
        ];

        foreach ($roles as [$role, $count]) {
            $existing = User::where('role', $role)->count();
            $toCreate = max(0, $count - $existing);

            for ($i = 0; $i < $toCreate; $i++) {
                User::factory()->create([
                    'role' => $role,
                    'status' => UserStatus::Active,
                ]);
            }
        }
    }

    private function seedAuditLogs(): void
    {
        if (AdminActivityLog::exists()) {
            return;
        }

        $samples = [
            ['action' => 'producer_validated', 'target_label' => 'Producteur Amadou Sow', 'reason' => null],
            ['action' => 'withdrawal_validated', 'target_label' => 'Retrait #RT-2026-0142', 'reason' => null],
            ['action' => 'user_suspended', 'target_label' => 'Client Ibrahima Ndiaye', 'reason' => 'Comportement frauduleux signalé'],
            ['action' => 'claim_rejected', 'target_label' => 'Réclamation #RC-089', 'reason' => 'Preuves insuffisantes'],
            ['action' => 'producer_validated', 'target_label' => 'Producteur Fatou Mbaye', 'reason' => null],
        ];

        $superAdmin = User::where('role', UserRole::SuperAdmin)->first();

        foreach ($samples as $i => $sample) {
            AdminActivityLog::create([
                'admin_id' => $superAdmin?->id ?? 1,
                'action' => $sample['action'],
                'target_label' => $sample['target_label'],
                'reason' => $sample['reason'],
                'created_at' => now()->subDays(rand(1, 30))->subHours(rand(1, 12)),
            ]);
        }
    }
}

<?php

namespace App\Services;

use App\Models\AdminActivityLog;
use App\Models\User;

class AdminActivityLogger
{
    public static function log(
        User $admin,
        string $action,
        string $targetLabel,
        ?string $targetType = null,
        ?int $targetId = null,
        ?string $reason = null,
        ?array $metadata = null,
    ): AdminActivityLog {
        return AdminActivityLog::create([
            'admin_id' => $admin->id,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'target_label' => $targetLabel,
            'reason' => $reason,
            'metadata' => $metadata,
        ]);
    }
}

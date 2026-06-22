<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Models\User;
use App\Services\TableExportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = AdminActivityLog::with('admin')->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('target_label', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%")
                    ->orWhereHas('admin', function ($aq) use ($search) {
                        $aq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($adminId = $request->get('admin_id')) {
            $query->where('admin_id', $adminId);
        }

        if ($action = $request->get('action')) {
            $query->where('action', $action);
        }

        if ($from = $request->get('from')) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $request->get('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $logs = $query->paginate(20)->withQueryString();
        $admins = User::admins()->orderBy('first_name')->get();
        $actions = AdminActivityLog::distinct()->pluck('action');

        return view('super-admin.audit.index', compact('logs', 'admins', 'actions'));
    }

    public function export(Request $request, TableExportService $export, string $format)
    {
        $query = AdminActivityLog::with('admin')->latest();

        if ($request->get('search')) {
            $search = $request->get('search');
            $query->where('target_label', 'like', "%{$search}%");
        }

        $logs = $query->limit(1000)->get();
        $headers = ['Date', 'Heure', 'Administrateur', 'Action', 'Cible', 'Motif'];
        $rows = $logs->map(fn ($log) => [
            $log->created_at->format('d/m/Y'),
            $log->created_at->format('H:i'),
            $log->admin?->full_name ?? '—',
            $this->actionLabel($log->action),
            $log->target_label,
            $log->reason ?? '—',
        ]);

        if ($format === 'pdf') {
            return $export->toPdf('exports.table', [
                'title' => 'Journal d\'audit',
                'headers' => $headers,
                'rows' => $rows,
            ], 'audit');
        }

        return $export->toExcel($headers, $rows, 'audit');
    }

    private function actionLabel(string $action): string
    {
        return match ($action) {
            'admin_created' => 'Création administrateur',
            'admin_updated' => 'Modification administrateur',
            'admin_password_reset' => 'Réinitialisation mot de passe',
            'admin_suspended' => 'Suspension administrateur',
            'admin_activated' => 'Réactivation administrateur',
            'admin_archived' => 'Archivage administrateur',
            'producer_validated' => 'Validation producteur',
            'withdrawal_validated' => 'Validation retrait',
            'user_suspended' => 'Suspension utilisateur',
            'claim_rejected' => 'Rejet réclamation',
            default => str_replace('_', ' ', ucfirst($action)),
        };
    }
}

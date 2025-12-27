<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = AuditLog::with('user')
            ->orderByDesc('created_at');

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', 'like', '%' . $request->model_type . '%');
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search in description or model_label
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', '%' . $search . '%')
                    ->orWhere('model_label', 'like', '%' . $search . '%')
                    ->orWhere('user_name', 'like', '%' . $search . '%');
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        // Get unique actions for filter
        $actions = AuditLog::distinct()->pluck('action')->sort();

        // Get unique model types for filter
        $modelTypes = AuditLog::distinct()
            ->whereNotNull('model_type')
            ->pluck('model_type')
            ->map(fn($type) => class_basename($type))
            ->unique()
            ->sort();

        return view('audit-logs.index', compact('logs', 'actions', 'modelTypes'));
    }

    public function show(AuditLog $auditLog): View
    {
        return view('audit-logs.show', compact('auditLog'));
    }
}

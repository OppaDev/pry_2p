<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuditController extends Controller
{
    /**
     * Display a listing of all audits grouped by user.
     */
    public function auditsByUser(Request $request)
    {
        $request->validate([
            'per_page' => 'nullable|integer|in:5,10,15,25,50',
            'search' => 'nullable|string|max:255',
            'event' => 'nullable|string|in:created,updated,deleted,restored,force_deleted',
            'auditable_type' => 'nullable|string|in:App\Models\User,App\Models\Producto'
        ], [
            'per_page.integer' => 'El valor debe ser un número entero.',
            'per_page.in' => 'El valor debe ser uno de los siguientes: 5, 10, 15, 25, 50.',
            'search.string' => 'El término de búsqueda debe ser una cadena de texto.',
            'search.max' => 'El término de búsqueda no puede tener más de 255 caracteres.'
        ]);

        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        $eventFilter = $request->get('event');
        $auditableTypeFilter = $request->get('auditable_type');

        // Query principal para obtener auditorías con información de usuario y modelo auditado
        $query = Audit::with(['user'])
            ->leftJoin('users as auditable_users', function($join) {
                $join->on('audits.auditable_id', '=', 'auditable_users.id')
                     ->where('audits.auditable_type', '=', 'App\Models\User');
            })
            ->leftJoin('productos', function($join) {
                $join->on('audits.auditable_id', '=', 'productos.id')
                     ->where('audits.auditable_type', '=', 'App\Models\Producto');
            })
            ->select(
                'audits.*',
                'auditable_users.name as auditable_user_name',
                'auditable_users.email as auditable_user_email',
                'productos.nombre as auditable_producto_nombre',
                'productos.codigo as auditable_producto_codigo'
            );

        // Filtro por búsqueda
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'LIKE', '%' . $search . '%')
                             ->orWhere('email', 'LIKE', '%' . $search . '%');
                })
                ->orWhere('auditable_users.name', 'LIKE', '%' . $search . '%')
                ->orWhere('auditable_users.email', 'LIKE', '%' . $search . '%')
                ->orWhere('productos.nombre', 'LIKE', '%' . $search . '%')
                ->orWhere('productos.codigo', 'LIKE', '%' . $search . '%');
            });
        }

        // Filtro por evento
        if ($eventFilter) {
            $query->where('event', $eventFilter);
        }

        // Filtro por tipo de modelo
        if ($auditableTypeFilter) {
            $query->where('auditable_type', $auditableTypeFilter);
        }

        $query->orderBy('created_at', 'desc');

        $audits = $query->paginate($perPage)->withQueryString();

        // Estadísticas para el dashboard
        $stats = [
            'total_audits' => Audit::count(),
            'total_users_with_audits' => Audit::distinct('user_id')->count('user_id'),
            'events_count' => Audit::select('event', DB::raw('count(*) as total'))
                ->groupBy('event')
                ->pluck('total', 'event')
                ->toArray(),
            'recent_activity' => Audit::where('created_at', '>=', now()->subDays(7))->count()
        ];

        return view('audits.by-user', compact(
            'audits', 
            'perPage', 
            'search', 
            'eventFilter', 
            'auditableTypeFilter',
            'stats'
        ));
    }

    /**
     * Display audit details for a specific audit record.
     */
    public function show(Audit $audit)
    {
        $audit->load('user');
        
        // Cargar el modelo auditado si existe
        if ($audit->auditable_type && $audit->auditable_id) {
            try {
                $auditableModel = $audit->auditable_type::find($audit->auditable_id);
                if (!$auditableModel && $audit->auditable_type === 'App\Models\User') {
                    $auditableModel = User::withTrashed()->find($audit->auditable_id);
                } elseif (!$auditableModel && $audit->auditable_type === 'App\Models\Producto') {
                    $auditableModel = \App\Models\Producto::withTrashed()->find($audit->auditable_id);
                }
            } catch (\Exception $e) {
                $auditableModel = null;
            }
        } else {
            $auditableModel = null;
        }

        return view('audits.show', compact('audit', 'auditableModel'));
    }
}

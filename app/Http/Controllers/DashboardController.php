<?php

namespace App\Http\Controllers;

use App\Services\ReporteService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected ReporteService $reporteService;
    
    public function __construct(ReporteService $reporteService)
    {
        $this->reporteService = $reporteService;
    }
    
    public function index()
    {
        // Obtener datos del dashboard usando ReporteService
        $dashboard = $this->reporteService->datosDashboard();
        
        return view('dashboard', compact('dashboard'));
    }
}

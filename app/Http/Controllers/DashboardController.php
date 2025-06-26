<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalProductos = Producto::count();
        $totalStock = Producto::sum('cantidad');
        $valorInventario = Producto::sum(DB::raw('precio * cantidad'));

        return view('dashboard', compact('totalUsers', 'totalProductos', 'totalStock', 'valorInventario'));
    }
}

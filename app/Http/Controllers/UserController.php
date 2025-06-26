<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\ValidarEditUser;
use App\Http\Requests\ValidarStoreUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'per_page' => 'nullable|integer|in:5,10,15,25,50',
            'search' => 'nullable|string|max:255'
        ], [
            'per_page.integer' => 'El valor debe ser un número entero.',
            'per_page.in' => 'El valor debe ser uno de los siguientes: 5, 10, 15, 25, 50.',
            'search.string' => 'El término de búsqueda debe ser una cadena de texto.',
            'search.max' => 'El término de búsqueda no puede tener más de 255 caracteres.'
        ]);
        
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        
        $query = User::select(['id', 'name', 'email', 'email_verified_at', 'created_at', 'updated_at']);
        
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }

        $query->orderBy('created_at', 'desc');
        
        $data = array(
            'usuarios' => $query->paginate($perPage)->withQueryString(),
            'perPage' => $perPage,
            'search' => $search
        );
        return view('users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ValidarStoreUser $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ValidarEditUser $request, User $user)
    {
        // Preparar datos para actualizar
        $data = $request->only(['name', 'email']);
        
        // Si se proporciona una nueva contraseña, la hasheamos
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        // Actualizar el usuario con los datos validados
        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}

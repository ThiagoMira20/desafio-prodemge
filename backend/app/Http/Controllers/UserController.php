<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Rules\ValidationCPF;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('cpf', 'LIKE', "%{$search}%")
                ->orWhere('id', 'LIKE', "%{$search}%");
        }

        $query->with('street');

        return $query->get();
    }

    public function store(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'name' => 'required|string|max:255',
            'social_name' => 'nullable|string|max:255',
            'cpf' => ['required', 'string', new ValidationCPF, 'unique:users'],
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'email' => 'required|string|email|max:255|unique:users,email',
        ]);


        // Criação do usuário
        $user = User::create([
            'name' => $request->name,
            'social_name' => $request->social_name,
            'cpf' => $request->cpf,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        return response()->json(['message' => 'Cadastro criado com sucesso', 'data' => $user], 201);
    }
}

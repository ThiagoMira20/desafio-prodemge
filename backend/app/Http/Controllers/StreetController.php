<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Street;
class StreetController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required',
            'cep' => 'required',
            'logradouro' => 'required',
            'numero' => 'required',
            'bairro' => 'required',
            'estado' => 'required',
            'cidade' => 'required',
        ]);
    
        $street = Street::where('user_id', $request->user_id)->first();
    

        if ($street) {
            $street->update([
                'tipo' => $request->tipo,
                'cep' => $request->cep,
                'logradouro' => $request->logradouro,
                'numero' => $request->numero,
                'complemento' => $request->complemento,
                'bairro' => $request->bairro,
                'estado' => $request->estado,
                'cidade' => $request->cidade,
            ]);

    
            return response()->json(['message' => 'Endereço atualizado com sucesso', 'data' => $street], 200);
        } else {
            // Se não existe, crie um novo registro
            $street = Street::create([
                'user_id' => $request->user_id,
                'tipo' => $request->tipo,
                'cep' => $request->cep,
                'logradouro' => $request->logradouro,
                'numero' => $request->numero,
                'complemento' => $request->complemento,
                'bairro' => $request->bairro,
                'estado' => $request->estado,
                'cidade' => $request->cidade,
            ]);
    
            return response()->json(['message' => 'Endereço salvo com sucesso', 'data' => $street], 201);
        }
    }
    

    public function show($id)
    {
        $endereco = Street::findOrFail($id);
        return response()->json($endereco);
    }
}

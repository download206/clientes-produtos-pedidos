<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('clients.index', ['clients' => $clients]);
    }

    public function apiIndex()
    {
        $clients = Client::all();
        return response()->json($clients);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string',
            'telefone' => 'required|string',
            'email' => 'required|email|unique:clients,email',
        ]);

        $client = Client::create($validated);

        return response()->json(['message' => 'Client created successfully', 'client' => $client], 201);
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }


    public function show($id)
    {
        $client = Client::with('orders')->findOrFail($id);
    
        if (request()->wantsJson()) {
            return response()->json($client);
        } else {
            return view('clients.show', compact('client'));
        }
    }
    

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'email' => 'required|email|unique:clients,email,' . $client->id,
        ]);

        $client->update($validated);

        return response()->json(['message' => 'Cliente atualizado com sucesso', 'client' => $client]);

        $client->update($request->all());
        return response()->json($client);

    }


    public function destroy($id)
    {
        $product = Client::findOrFail($id);
        $product->delete();

        return redirect()->route('clients.index')->with('success', 'Cliente excluído com sucesso.');
    }
    
}

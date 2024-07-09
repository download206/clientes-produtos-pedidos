<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', ['products' => $products]);
    }

    public function apiIndex()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'unit_price' => 'required|numeric',
        ]);

        $product = Product::create($validated);

        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        if (request()->wantsJson()) {
            return response()->json($product);
        } else {
            return view('products.show', ['product' => $product]);
        }
    }

    public function update(Request $request, Product $product)
    {
        // Validação dos dados do formulário
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit_price' => 'required|numeric',
            // Outras regras de validação conforme necessário
        ]);

        // Atualiza os atributos do produto
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'unit_price' => $request->unit_price,
            // Outros campos do produto conforme necessário
        ]);

        // Sincroniza os pedidos associados ao produto
        if ($request->has('order_ids')) {
            $validOrderIds = Order::whereIn('id', $request->input('order_ids'))->pluck('id')->toArray();
            $product->orders()->sync($validOrderIds);
        } else {
            $product->orders()->sync([]); // Limpa a associação se nenhum pedido for selecionado
        }

        // Redireciona com mensagem de sucesso
        return redirect()->route('products.index', $product->id)->with('success', 'Produto atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produto excluído com sucesso.');
    }
}
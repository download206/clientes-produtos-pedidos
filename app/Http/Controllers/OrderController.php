<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('client')->get();
        $clients = Client::all();
        $products = Product::all();

        return view('orders.index', compact('orders', 'clients', 'products'));
    }

    public function apiIndex()
    {
        $orders = Order::all();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'status' => 'required|string',
            'description' => 'nullable|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        // Create the order
        $order = Order::create([
            'client_id' => $validated['client_id'],
            'status' => $validated['status'],
            'description' => $validated['description'],
        ]);

        // Attach products to the order
        foreach ($validated['products'] as $product) {
            $order->products()->attach($product['product_id'], ['quantity' => $product['quantity']]);
        }

        // Redirect back to the orders page with a success message
        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    public function show($id)
    {
        $order = Order::findOrFail($id); // Supondo que seu modelo de pedido seja 'Order'
        return view('orders.show', compact('order'));
    }
    

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $products = Product::all();

        return view('orders.edit', compact('order', 'products'));
    }
    public function update(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'status' => 'required|string',
            'description' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);
    
        try {
            DB::beginTransaction();
    
            $order->update([
                'status' => $validatedData['status'],
                'description' => $validatedData['description'] ?? $order->description,
            ]);
    
            if (isset($validatedData['products'])) {
                foreach ($validatedData['products'] as $product) {
                    $order->products()->syncWithoutDetaching([
                        $product['product_id'] => ['quantity' => $product['quantity']]
                    ]);
                }
            } else {
                $order->products()->detach();
            }
    
            DB::commit();
    
            return response()->json([
                'message' => 'Order updated successfully',
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
    
            Log::error('Failed to update order:', ['error' => $e->getMessage()]);
    
            return response()->json([
                'message' => 'Failed to update order',
                'error' => $e->getMessage(),
            ], 500);
        }

          return redirect()->route('orders.index');
    }
    

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
    
        return redirect()->route('orders.index')->with('success', 'Pedido excluído com sucesso.');
    }

    public function detachProduct($orderId, $productId)
    {
        $order = Order::findOrFail($orderId);
        $product = Product::findOrFail($productId);

        $order->products()->detach($productId);

        return response()->json(['message' => 'Produto removido do pedido com sucesso.']);
    }
}

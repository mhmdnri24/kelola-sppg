<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Katalog;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $payload = [
            'title' => 'Keranjang',
            'cart' => $cart,
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'Keranjang', 'url' => route('keranjang')],
            ]
        ];

        return view('admin.cart.index', $payload);
    }

    public function add(Request $request)
    {
        $product = Katalog::findOrFail($request->id);

        $cart = session()->get('cart', []);
        $qty = $request->input('qty', 1);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['qty'] += $qty;
        } else {
            $cart[$product->id] = [
                'nama' => $product->nama,
                'harga' => $product->harga,
                'qty' => $qty,
                'produk_id' => $product->id,
                'supplier_id' => $product->supplier_id,
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Produk ditambahkan ke cart');
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart');

        if (isset($cart[$request->id])) {
            $cart[$request->id]['qty'] = $request->qty;
            session()->put('cart', $cart);
        }

        return back();
    }

    public function delete(Request $request)
    {
        $cart = session()->get('cart');

        if (isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }

        return back();
    }

    public function getCartCount(Request $request)
    {
        $cart = session()->get('cart', []);
        return count($cart);
    }

    public function getCartItemCount($id)
    {
        $cart = session()->get('cart', []);
        return $cart[$id]['qty'] ?? 0;
    }

    public function getCartTotal()
    {
        $cart = session()->get('cart', []);
        return array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['qty'] * $item['harga']);
        }, 0);
    }

    public function getCart()
    {
        $cart = session()->get('cart', []);
        return response()->json($cart);
    }
}

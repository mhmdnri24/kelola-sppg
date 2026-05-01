<?php

namespace App\Http\Controllers;

use App\Models\Katalog;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    //
    public function index()
    {
        $cart = session()->get('cart', []);
          $payload = [
            'title' => 'Checkout',
            'cart'=>$cart,
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'Checkout', 'url' => route('checkout')],
            ]
        ];

        return view('admin.checkout.index', $payload);
    }

    public function data(){

        $cart = session()->get('cart', []);

        if(!$cart) {
            return response()->json([
                'data' => [],
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
            ]);
        }

        $result = [];

        foreach ($cart as $key => $value) {

            $katalog = Katalog::with('supplier')->where('id', $value['produk_id'])->first();

           $item = [
                'nama' => $value['nama'],
                'harga' => $value['harga'],
                'qty' => $value['qty'],
                'total' => $value['harga'] * $value['qty'],
                'katalog' => $katalog,
            ];

            $result[] = $item;
        }

        return response()->json([
            'data' => $result,
            'recordsTotal' => count($result),
            'recordsFiltered' => count($result),
        ]);

    }
}

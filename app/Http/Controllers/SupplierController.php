<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    //
    public function getSupplier(){

        return Supplier::with('bankAccount')->get();

    }
}

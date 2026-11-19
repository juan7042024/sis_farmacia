<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\CompraTmp;
use Illuminate\Http\Request;
// asi se creo ""php artisan make:model CompraTmp -mcrf"
class CompraTmpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // traer las compras ya paginadas
        $compras = Compra::paginate(10);
        //
        return view('admin.ordenes_compra.index', [
            'compras'=> $compras
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        return view('admin.ordenes_compra.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CompraTmp $compraTmp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompraTmp $compraTmp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompraTmp $compraTmp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompraTmp $compraTmp)
    {
        //
    }
}

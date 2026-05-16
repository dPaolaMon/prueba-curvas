<?php

namespace App\Http\Controllers;

use App\Models\Maquina;
use App\Http\Requests\StoreMaquinaRequest;

class MaquinaController extends Controller
{
    /**
     * Display a listing of the machines.
     */
    public function index()
    {
        $maquinas = Maquina::orderBy('nombre')->get();
        return view('maquinas.index', compact('maquinas'));
    }

    /**
     * Show the form for creating a new machine.
     */
    public function create()
    {
        $maquina = new Maquina();
        return view('maquinas.create', compact('maquina'));
    }

    /**
     * Store a newly created machine in storage.
     */
    public function store(StoreMaquinaRequest $request)
    {
        $validated = $request->validated();
        
        Maquina::create($validated);

        return redirect()->route('maquinas.index')
                        ->with('success', 'Máquina registrada correctamente');
    }

    /**
     * Show the form for editing the specified machine.
     */
    public function edit(Maquina $maquina)
    {
        return view('maquinas.edit', compact('maquina'));
    }

    /**
     * Update the specified machine in storage.
     */
    public function update(StoreMaquinaRequest $request, Maquina $maquina)
    {
        $validated = $request->validated();
        
        $maquina->update($validated);

        return redirect()->route('maquinas.index')
                        ->with('success', 'Máquina actualizada correctamente');
    }

    /**
     * Remove the specified machine from storage.
     */
    public function destroy(Maquina $maquina)
    {
        $maquina->delete();

        return redirect()->route('maquinas.index')
                        ->with('success', 'Máquina eliminada correctamente');
    }
}

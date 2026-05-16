<?php

namespace App\Http\Controllers;

use App\Models\Ejercicio;
use App\Http\Requests\StoreEjercicioRequest;

class EjercicioController extends Controller
{
    /**
     * Display a listing of the exercises.
     */
    public function index()
    {
        $ejercicios = Ejercicio::orderBy('nombre')->get();
        return view('ejercicios.index', compact('ejercicios'));
    }

    /**
     * Show the form for creating a new exercise.
     */
    public function create()
    {
        $ejercicio = new Ejercicio();
        return view('ejercicios.create', compact('ejercicio'));
    }

    /**
     * Store a newly created exercise in storage.
     */
    public function store(StoreEjercicioRequest $request)
    {
        $validated = $request->validated();
        
        Ejercicio::create($validated);

        return redirect()->route('ejercicios.index')
                        ->with('success', 'Ejercicio registrado correctamente');
    }

    /**
     * Show the form for editing the specified exercise.
     */
    public function edit(Ejercicio $ejercicio)
    {
        return view('ejercicios.edit', compact('ejercicio'));
    }

    /**
     * Update the specified exercise in storage.
     */
    public function update(StoreEjercicioRequest $request, Ejercicio $ejercicio)
    {
        $validated = $request->validated();
        
        $ejercicio->update($validated);

        return redirect()->route('ejercicios.index')
                        ->with('success', 'Ejercicio actualizado correctamente');
    }

    /**
     * Remove the specified exercise from storage.
     */
    public function destroy(Ejercicio $ejercicio)
    {
        $ejercicio->delete();

        return redirect()->route('ejercicios.index')
                        ->with('success', 'Ejercicio eliminado correctamente');
    }
}

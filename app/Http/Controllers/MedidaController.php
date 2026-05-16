<?php

namespace App\Http\Controllers;

use App\Models\Medida;
use App\Models\Socia;
use App\Http\Requests\StoreMedidaRequest;
use Illuminate\Http\Request;

class MedidaController extends Controller
{
    /**
     * Display a listing of the measures.
     */
    public function index(Request $request)
    {
        $sort = $request->query('sort', 'fecha_registro');
        $direction = strtolower((string) $request->query('direction', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSorts = ['fecha_registro'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'fecha_registro';
        }

        $medidas = Medida::with('socia')
            ->orderBy($sort, $direction)
            ->get();

        return view('medidas.index', compact('medidas', 'sort', 'direction'));
    }

    /**
     * Show the form for creating a new measure.
     */
    public function create(Request $request)
    {
        $medida = new Medida();
        $sociaId = $request->integer('socia_id');

        if ($sociaId > 0 && Socia::whereKey($sociaId)->exists()) {
            $medida->socia_id = $sociaId;
        }

        $socias = Socia::orderBy('nombre')->orderBy('apellidos')->get();

        return view('medidas.create', compact('medida', 'socias'));
    }

    /**
     * Store a newly created measure in storage.
     */
    public function store(StoreMedidaRequest $request)
    {
        $validated = $request->validated();

        Medida::create($validated);

        return redirect()->route('medidas.index')
                        ->with('success', 'Medida registrada correctamente');
    }

    /**
     * Show the form for editing the specified measure.
     */
    public function edit(Medida $medida)
    {
        $socias = Socia::orderBy('nombre')->orderBy('apellidos')->get();

        return view('medidas.edit', compact('medida', 'socias'));
    }

    /**
     * Update the specified measure in storage.
     */
    public function update(StoreMedidaRequest $request, Medida $medida)
    {
        $validated = $request->validated();

        $medida->update($validated);

        return redirect()->route('medidas.index')
                        ->with('success', 'Medida actualizada correctamente');
    }

    /**
     * Remove the specified measure from storage.
     */
    public function destroy(Medida $medida)
    {
        $medida->delete();

        return redirect()->route('medidas.index')
                        ->with('success', 'Medida eliminada correctamente');
    }
}

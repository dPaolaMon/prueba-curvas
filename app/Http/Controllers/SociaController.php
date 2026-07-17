<?php

namespace App\Http\Controllers;

use App\Models\Socia;
use App\Models\Estado;
use App\Models\Municipio;
use Illuminate\Http\Request;

use App\Http\Requests\StoreSociaRequest;
use Illuminate\Support\Facades\Storage;

class SociaController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $sort = $request->query('sort', 'estatus');
        $direction = strtolower((string) $request->query('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        $allowedSorts = ['num_socia', 'nombre', 'estatus', 'fecha_alta', 'fecha_reingreso'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'estatus';
        }

        $query = Socia::with(['estado', 'municipio', 'user'])
            ->when($sort === 'nombre', function ($q) use ($direction) {
                $q->orderBy('nombre', $direction)
                    ->orderBy('apellidos', $direction);
            }, function ($q) use ($sort, $direction) {
                if ($sort === 'estatus') {
                    $q->orderByRaw("CASE WHEN estatus = 'Activa' THEN 0 WHEN estatus = 'Baja' THEN 1 ELSE 2 END {$direction}")
                        ->orderBy('num_socia', 'asc');

                    return;
                }

                $q->orderBy($sort, $direction);
            });

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('num_socia', 'like', "%{$search}%")
                    ->orWhere('nombre', 'like', "%{$search}%")
                    ->orWhere('apellidos', 'like', "%{$search}%")
                    ->orWhereRaw("CONCAT(nombre, ' ', apellidos) like ?", ["%{$search}%"]);
            });
        }

        $socias = $query->paginate(10)->withQueryString();

        $socias->through(function (Socia $socia) {
            $socia->edad = $socia->fecha_nacimiento?->age;

            return $socia;
        });

        return view('socias.index', [
            'socias' => $socias,
            'search' => $search,
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    public function create()
    {
        return view('socias.create', [
            'socia' => new Socia(),
            'estados' => Estado::orderBy('nombre')->get(),
            'municipios' => Municipio::orderBy('nombre')->get(),
        ]);
    }

    public function show(Socia $socia)
    {
        return view('socias.show', [
            'socia' => $socia->load(['estado', 'municipio', 'user']),
        ]);
    }

    public function edit(Socia $socia)
    {
        return view('socias.edit', [
            'socia' => $socia,
            'estados' => Estado::orderBy('nombre')->get(),
            'municipios' => Municipio::orderBy('nombre')->get(),
        ]);
    }

    public function destroy(Socia $socia)
    {
        $socia->delete();

        return redirect()
            ->route('socias.index')
            ->with('success', 'Socia eliminada correctamente');
    }

    public function toggleEstatus(Socia $socia)
    {
        if ($socia->estatus === 'Activa') {
            $socia->update([
                'estatus' => 'Baja',
                'fecha_baja' => now(),
            ]);

            return redirect()
                ->route('socias.index')
                ->with('success', 'Socia dada de baja correctamente');
        }

        $socia->update([
            'estatus' => 'Activa',
            'fecha_baja' => null,
            'fecha_reingreso' => now(),
        ]);

        return redirect()
            ->route('socias.index')
            ->with('success', 'Socia reactivada correctamente');
    }

    public function store(StoreSociaRequest $request)
    {
        $data = $request->validated();

        // En altas se controlan internamente estas fechas.
        $data['fecha_alta'] = now()->toDateString();
        $data['fecha_reingreso'] = now()->toDateString();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')
                ->store('socias', 'public');
        }

        $data['user_id'] = auth()->id();

        $socia = Socia::create($data);

        $loginSocia = (string) ($socia->num_socia ?? $socia->fresh()->num_socia);

        return redirect()
            ->route('socias.index')
            ->with('success', 'Socia registrada correctamente')
            ->with('socia_credentials', [
                'socia_id' => $socia->id,
                'login' => $loginSocia,
                'password' => 'cambiar',
            ]);
    }

    public function update(StoreSociaRequest $request, Socia $socia)
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {

            // eliminar foto anterior
            if ($socia->foto) {
                Storage::disk('public')->delete($socia->foto);
            }

            $data['foto'] = $request->file('foto')
                ->store('socias', 'public');
        }

        $socia->update($data);

        return redirect()
            ->route('socias.index')
            ->with('success', 'Socia actualizada correctamente');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePerfilSociaRequest;
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\Socia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PerfilSociaController extends Controller
{
    public function show(Request $request): View
    {
        $socia = $this->sociaAutenticada((int) $request->user()->id);

        return view('perfil-socia.show', [
            'socia' => $socia,
        ]);
    }

    public function edit(Request $request): View
    {
        $socia = $this->sociaAutenticada((int) $request->user()->id);

        return view('perfil-socia.edit', [
            'socia' => $socia,
            'estados' => Estado::orderBy('nombre')->get(),
            'municipios' => Municipio::orderBy('nombre')->get(),
        ]);
    }

    public function update(UpdatePerfilSociaRequest $request): RedirectResponse
    {
        $socia = Socia::where('user_id', $request->user()->id)->firstOrFail();
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            if ($socia->foto) {
                Storage::disk('public')->delete($socia->foto);
            }

            $data['foto'] = $request->file('foto')->store('socias', 'public');
        }

        $socia->update($data);

        return redirect()
            ->route('perfil-socia.show')
            ->with('success', 'Perfil actualizado correctamente');
    }

    private function sociaAutenticada(int $userId): Socia
    {
        return Socia::with(['estado', 'municipio'])
            ->where('user_id', $userId)
            ->firstOrFail();
    }
}

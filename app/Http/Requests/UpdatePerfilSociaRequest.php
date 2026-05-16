<?php

namespace App\Http\Requests;

use App\Models\Socia;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePerfilSociaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return strtoupper((string) $this->user()?->role) === 'SOCIA';
    }

    public function rules(): array
    {
        $sociaId = Socia::where('user_id', $this->user()->id)->value('id');

        return [
            'nombre' => ['required', 'string', 'max:64'],
            'apellidos' => ['required', 'string', 'max:128'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'fecha_nacimiento' => ['required', 'date'],
            'ocupacion' => ['nullable', 'string', 'max:32'],
            'estado_civil' => ['nullable', 'string', 'max:32'],
            'celular' => ['required', 'string', 'max:20'],
            'email' => [
                'nullable',
                'email',
                'max:32',
                Rule::unique('socias', 'email')->ignore($sociaId),
            ],
            'direccion' => ['nullable', 'string', 'max:256'],
            'colonia' => ['nullable', 'string', 'max:32'],
            'codigo_postal' => ['nullable', 'string', 'max:10'],
            'municipio_id' => ['required', 'exists:municipios,id'],
            'estado_id' => ['required', 'exists:estados,id'],
            'contacto_emergencia' => ['nullable', 'string'],
            'padecimiento_cronico' => ['nullable', 'string'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSociaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // TODO: luego aquí puedes meter Policies
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $sociaId = $this->route('socia')?->id;

        return [
            'nombre' => ['required', 'string', 'max:64'],
            'apellidos' => ['required', 'string', 'max:128'],
            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048', // 2MB
            ],
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

            'fecha_alta' => ['required', 'date'],
            'fecha_reingreso' => ['required', 'date'],

            'contacto_emergencia' => ['nullable', 'string'],
            'padecimiento_cronico' => ['nullable', 'string'],

            
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaquinaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255|unique:maquinas,nombre,' . ($this->maquina?->id ?? 'NULL'),
            'descripcion' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la máquina es requerido.',
            'nombre.unique' => 'Ya existe una máquina con este nombre.',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
            'descripcion.string' => 'La descripción debe ser un texto válido.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEjercicioRequest extends FormRequest
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
            'nombre' => 'required|string|max:255|unique:ejercicios,nombre,' . ($this->ejercicio?->id ?? 'NULL'),
            'descripcion' => 'nullable|string',
            'color' => 'required|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del ejercicio es requerido.',
            'nombre.unique' => 'Ya existe un ejercicio con este nombre.',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
            'descripcion.string' => 'La descripción debe ser un texto válido.',
            'color.required' => 'El color es requerido.',
            'color.max' => 'El color no puede exceder 255 caracteres.',
        ];
    }
}

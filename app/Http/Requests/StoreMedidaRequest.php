<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedidaRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $peso = $this->input('peso');
        $altura = $this->input('altura');
        $imc = $this->input('imc');

        if ($this->filled('fecha_registro')) {
            $this->merge([
                'fecha_registro' => str_replace('T', ' ', (string) $this->input('fecha_registro')),
            ]);
        }

        if (($imc === null || $imc === '') && is_numeric($peso) && is_numeric($altura)) {
            $imcCalculado = $this->calcularImc((float) $peso, (float) $altura);

            if ($imcCalculado !== null) {
                $this->merge([
                    'imc' => $imcCalculado,
                ]);
            }
        }
    }

    private function calcularImc(float $peso, float $altura): ?float
    {
        if ($peso <= 0 || $altura <= 0) {
            return null;
        }

        $alturaMetros = $altura > 3 ? $altura / 100 : $altura;

        if ($alturaMetros <= 0) {
            return null;
        }

        return round($peso / ($alturaMetros * $alturaMetros), 2);
    }

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
            'socia_id' => 'required|exists:socias,id',
            'fecha_registro' => 'required|date',
            'busto' => 'required|numeric|min:0',
            'cintura' => 'required|numeric|min:0',
            'abdomen' => 'required|numeric|min:0',
            'caderas' => 'required|numeric|min:0',
            'muslo' => 'required|numeric|min:0',
            'brazo' => 'required|numeric|min:0',
            'peso' => 'required|numeric|min:0',
            'altura' => 'required|numeric|min:0',
            'imc' => 'required|numeric|min:0',
            'porcentaje_grasa' => 'required|numeric|min:0|max:100',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'socia_id.required' => 'Debe seleccionar una socia.',
            'socia_id.exists' => 'La socia seleccionada no es válida.',
            'fecha_registro.required' => 'La fecha de registro es requerida.',
            'fecha_registro.date' => 'La fecha de registro no es válida.',
            'busto.required' => 'El busto es requerido.',
            'cintura.required' => 'La cintura es requerida.',
            'abdomen.required' => 'El abdomen es requerido.',
            'caderas.required' => 'Las caderas son requeridas.',
            'muslo.required' => 'El muslo es requerido.',
            'brazo.required' => 'El brazo es requerido.',
            'peso.required' => 'El peso es requerido.',
            'altura.required' => 'La altura es requerida.',
            'imc.required' => 'El IMC es requerido.',
            'porcentaje_grasa.required' => 'El porcentaje de grasa es requerido.',
            '*.numeric' => 'Todos los campos de medida deben ser numéricos.',
            '*.min' => 'Los valores no pueden ser negativos.',
            'porcentaje_grasa.max' => 'El porcentaje de grasa no puede ser mayor a 100.',
        ];
    }
}

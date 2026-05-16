<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMensajeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $usuario   = $this->user();
        $esSocia   = strtoupper((string) $usuario->role) === 'SOCIA';
        $propioId  = $usuario->id;

        return [
            'asunto' => ['nullable', 'string', 'max:120'],

            'cuerpo' => ['required', 'string', 'max:5000'],

            'destinatarios' => ['required', 'array', 'min:1'],

            'destinatarios.*' => [
                'required',
                'integer',
                // No puede enviarse a sí mismo
                Rule::notIn([$propioId]),
                // El destinatario debe existir
                Rule::exists('users', 'id'),
                // Si es socia, los destinatarios no pueden tener rol SOCIA
                function (string $attribute, mixed $value, callable $fail) use ($esSocia) {
                    if (!$esSocia) {
                        return;
                    }

                    $destinatario = User::find($value);
                    if ($destinatario && strtoupper((string) $destinatario->role) === 'SOCIA') {
                        $fail('Las socias no pueden enviarse mensajes entre ellas.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'cuerpo.required'          => 'El mensaje no puede estar vacío.',
            'destinatarios.required'   => 'Debe seleccionar al menos un destinatario.',
            'destinatarios.*.not_in'   => 'No puedes enviarte un mensaje a ti mismo.',
            'destinatarios.*.exists'   => 'Uno de los destinatarios seleccionados no es válido.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
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
        $propioId  = $usuario->id;
        $destinatariosPermitidos = User::query()
            ->where('id', '!=', $propioId)
            ->where('suspendido', false)
            ->where(function ($query) {
                $query->whereIn(DB::raw('UPPER(role)'), ['GERENTE', 'ENTRENADORA', 'ADMINISTRADOR'])
                    ->orWhere(function ($subQuery) {
                        $subQuery->whereRaw('UPPER(role) = ?', ['SOCIA'])
                            ->whereExists(function ($existsQuery) {
                                $existsQuery->selectRaw('1')
                                    ->from('socias')
                                    ->whereColumn('socias.user_id', 'users.id')
                                    ->where('socias.estatus', 'Activa');
                            });
                    });
            })
            ->pluck('id')
            ->all();

        return [
            'asunto' => ['nullable', 'string', 'max:120'],

            'cuerpo' => ['required', 'string', 'max:5000'],

            'destinatarios' => ['required', 'array', 'min:1'],

            'destinatarios.*' => [
                'required',
                'integer',
                'distinct',
                // No puede enviarse a sí mismo
                Rule::notIn([$propioId]),
                // El destinatario debe existir
                Rule::exists('users', 'id'),
                Rule::in($destinatariosPermitidos),
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
            'destinatarios.*.in'       => 'Uno de los destinatarios no está habilitado para recibir mensajes.',
            'destinatarios.*.distinct' => 'No puedes repetir destinatarios en el mismo mensaje.',
        ];
    }
}

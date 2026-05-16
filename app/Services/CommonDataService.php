<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class CommonDataService
{
    /**
     * Obtiene los datos comunes desde el archivo JSON
     */
    public static function getData()
    {
        $path = resource_path('data/common_data.json');
        
        if (!File::exists($path)) {
            return [];
        }
        
        return json_decode(File::get($path), true);
    }

    /**
     * Obtiene solo los estados civiles
     */
    public static function getCivilStatuses()
    {
        return self::getData()['estado_civil'] ?? [];
    }

    /**
     * Obtiene solo los métodos de pago
     */
    public static function getPaymentMethods()
    {
        return self::getData()['metodo_pago'] ?? [];
    }

    /**
     * Obtiene solo los roles de usuario
     */
    public static function getUserRoles()
    {
        return self::getData()['roles'] ?? [];
    }

    /**
     * Obtiene solo los roles de usuario, menos socia, pues se agrega desde su catalogo
     */
    public static function getUserRolesAdmin()
    {
        $roles = self::getData()['roles'] ?? [];

        return array_values(
            array_filter($roles, fn ($rol) => strtolower($rol) !== 'socia')
        );
    }

    /**
     * Obtiene solo los temas disponibles
     */
    public static function getThemes()
    {
        return self::getData()['temas'] ?? [];
    }

    /**
     * Obtiene solo los valores de los temas para validación
     */
    public static function getThemeValues()
    {
        $themes = self::getData()['temas'] ?? [];
        return array_column($themes, 'value');
    }
}

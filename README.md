# StandardMinimalist

Base minimalista y sólida para proyectos Laravel con autenticación lista y configuración moderna.

## 🛠️ Stack de Tecnologías

### Backend
- **[Laravel 11](https://laravel.com)** - Framework PHP moderno y elegante
- **[Laravel Breeze](https://laravel.com/docs/starter-kits#laravel-breeze)** - Starter kit de autenticación
- **PHP 8.2+** - Lenguaje del lado del servidor

### Frontend
- **[Bootstrap 5](https://getbootstrap.com)** - Framework CSS responsivo
- **[SweetAlert2](https://sweetalert2.github.io)** - Alertas y toasts personalizables
- **[Vite](https://vitejs.dev)** - Build tool y dev server ultrarrápido

### Base de Datos
- Compatible con MySQL, PostgreSQL, SQLite
- Migraciones incluidas para autenticación con username

### Testing
- **[Pest PHP](https://pestphp.com)** - Framework de testing moderno

## ✨ Características

- ✅ Autenticación completa (login, registro, recuperación de contraseña)
- ✅ Login con **username** en lugar de email
- ✅ Localización en **español** (auth, validation, passwords)
- ✅ Diseño limpio con Bootstrap 5
- ✅ Toasts con SweetAlert2 configurados globalmente
- ✅ Blade components reutilizables
- ✅ Estructura minimalista y escalable

## 🚀 Instalación

```bash
# Clonar repositorio
git clone <repo-url>
cd StandardMinimalist

# Instalar dependencias PHP
composer install

# Instalar dependencias Node.js
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Ejecutar migraciones
php artisan migrate

# Compilar assets
npm run dev

# Insertar usuario 'admin' con password 'admin' en la BD
INSERT INTO users (name, username, email, email_verified_at, password, remember_token, created_at, updated_at) VALUES ('Administrador', 'admin',  'admin@local.test', NOW(), '$2y$12$jjI7WWD8s87Dqm74iMuvDe2/oClMmmDh0/6oJLUxbh/CTCM594Tja', NULL, NOW(), NOW());

```

## 📝 Configuración

### Idioma
El proyecto está configurado en español por defecto. Archivos de traducción en `resources/lang/es/`.

### Autenticación
- Login con **username** (no email)
- Migración personalizada: `2026_03_06_000003_add_username_to_users_table.php`

### Estilos
- Tamaño de fuente base ajustado a `0.9375rem` (15px)
- Configuración global en `resources/css/app.css`

## 📦 Comandos Útiles

```bash
# Levantar servidor de desarrollo
php artisan serve

# Compilar assets (modo desarrollo)
npm run dev

# Compilar assets (producción)
npm run build

# Ejecutar tests
php artisan test
```

## 📄 Licencia

Este proyecto está basado en Laravel, licenciado bajo [MIT license](https://opensource.org/licenses/MIT).

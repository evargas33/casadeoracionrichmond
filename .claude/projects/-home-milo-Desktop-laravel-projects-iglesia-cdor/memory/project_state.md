---
name: Estado actual del proyecto iglesia-cdor
description: Funcionalidades implementadas y pendientes en el CMS de Casa de Oración
type: project
---

Sistema de gestión de contenido para iglesia (Laravel 11 + Livewire 3 + Tailwind + Alpine.js).

## Implementado

### Autenticación y roles
- Middleware `CheckRole` en `app/Http/Middleware/CheckRole.php`
- Registrado como alias `role` en `bootstrap/app.php`
- Rutas `/admin/*` protegidas con `role:superadmin,admin,editor,member`
- Checks `abort_if` en todos los métodos de los 4 componentes Livewire:
  - Crear/Editar/Guardar → superadmin, admin, editor
  - Eliminar/Publicar/Destacar/Activar → superadmin, admin
- Página 403 personalizada en `resources/views/errors/403.blade.php`

### Alpine.js
- Importado en `resources/js/app.js` con `window.Alpine = Alpine; Alpine.start();`
- Requiere `npm run build` después de cambios en app.js

### Admin layout (layouts/admin.blade.php)
- Topbar con nombre del usuario y botón "Salir" siempre visible
- Sidebar colapsable con localStorage
- Link Dashboard apunta a `admin.dashboard`

### Dashboard con stats
- Componente: `app/Livewire/Admin/Dashboard.php`
- Vista: `resources/views/livewire/admin/dashboard.blade.php`
- Ruta: `GET /admin/` → `admin.dashboard`
- Stats: sermones (total/publicados), series (total/activas), eventos (total/próximos/destacados), páginas (total/publicadas/en menú)
- Lista de 5 sermones publicados más recientes

### Rutas admin
- `/dashboard` → redirect a `admin.dashboard`
- `/admin/` → Dashboard
- `/admin/sermons` → SermonsManager
- `/admin/series` → SeriesManager
- `/admin/events` → EventsManager
- `/admin/pages` → PagesManager

### Media Library
- Modelo: `app/Models/Media.php`
- Controller: `app/Http/Controllers/Admin/MediaController.php` (store/destroy)
- Componente Livewire: `app/Livewire/Admin/MediaLibrary.php` (solo listado/filtros)
- Upload via `<form>` POST estándar a `POST /admin/media`
- Servidor debe iniciarse con `composer run dev` o `php -d upload_max_filesize=50M -d post_max_size=55M artisan serve`
- `storage:link` requerido para acceso público

### Gestión de Usuarios
- Componente: `app/Livewire/Admin/UsersManager.php`
- Ruta: `GET /admin/users` → `admin.users`
- CRUD completo con asignación de roles (checkboxes)
- Eliminar solo superadmin · Activar/desactivar admin+superadmin · No puede actuar sobre sí mismo

## Pendiente
- Vista pública para sermones/eventos (solo existe el panel admin)
- Gestión de categorías desde el panel (link en sidebar apunta a `#`)
- Notificaciones por email

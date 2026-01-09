# Copilot Instructions - Águilas Saber PHP

## 🏗️ Architecture Overview

**Águilas Saber** is a Laravel 12 resource management system with:
- **Backend**: Laravel REST API with JWT authentication
- **Primary Models**: `Usuario`, `Recurso`, `Prestamo`, `Producto`, `Role`
- **Auth Strategy**: Dual-guard (web/session for admin panel, api/JWT for mobile)
- **Database**: PostgreSQL (primary) with fallback to SQLite for testing

### Key Data Flows
1. **Authentication**: POST `/api/login` returns JWT token + user data with roles
2. **Resource Access**: All API routes protected by `auth:api` middleware requiring valid JWT
3. **Roles & Permissions**: Uses Spatie Laravel Permission with pivot table `role_usuario`

## 🔐 Authentication Patterns

### API Authentication (JWT)
```php
// Login endpoint - No auth needed
POST /api/login
Body: { "email": "admin@aguillassaber.local", "password": "password123" }
Response: { "success": true, "token": "eyJ...", "usuario": {..., "roles": ["admin"]} }

// Protected endpoints
Authorization: Bearer <token>
GET /api/usuarios  // Returns all usuarios with roles loaded
```

### Key Auth Files
- `config/auth.php`: Guard definitions (web=session, api=jwt)
- `config/jwt.php`: JWT expiry, algorithms, blacklist settings
- `app/Http/Controllers/Api/LoginController.php`: Token generation
- `.env`: `JWT_SECRET` - never commit, regenerate per environment

## 📊 Data Model Relationships

```
Usuario (authenticatable)
├─ has many Roles (via role_usuario pivot)
├─ has many Prestamos
└─ has many Productos

Prestamo
├─ belongs to Usuario
├─ belongs to Recurso
└─ tracked by codigo, fecha_prestamo, estado

Recurso
├─ has many Prestamos
└─ has unique constraint on nombre

Role (extends Spatie Role)
├─ many to many Usuario
└─ includes descripcion (custom field)
```

**Critical**: When modifying Usuario, always load roles: `Usuario::with('roles')->find($id)`

## 🛣️ API Routing Pattern

All API routes follow REST conventions in `routes/api.php`:
- `Route::post('/login', [LoginController::class, 'login'])` - No auth
- `Route::apiResource('usuarios', UsuarioController::class)` - Full CRUD, all auth-protected
- Report endpoints are separate: `GET /api/reporte-productos`, `GET /reporte-usuarios`

**Naming Convention**: Endpoint names match table names (usuarios, recursos, prestamos, productos, roles)

## 🚀 Development Workflow

### Starting the Application
```bash
# Full stack with concurrency (PHP serve + queue + logs + Vite)
composer run dev

# Just Laravel API
php artisan serve --host=127.0.0.1 --port=8000

# Database operations
php artisan migrate              # Run pending migrations
php artisan db:seed              # Seed with roles + test usuario
php artisan config:clear         # Clear config cache before changes
```

### Testing
```bash
composer test                     # Run Pest tests
# Tests use RefreshDatabase trait + sqlite in-memory DB
```

### Database Seeding
- `RoleSeeder` creates default roles
- `UsuarioSeeder` creates test usuarios (admin@aguillassaber.local / password123)
- **Important**: Seeds are called in `DatabaseSeeder::run()` in specific order

## 💡 Critical Conventions & Patterns

### 1. **Usuario Model Usage**
- Always authenticate against `App\Models\Usuario` (not User)
- Fillable fields: nombre, apellido, email, telefono, password, activo
- Password must be hashed: `Hash::make($password)`
- Load relationships: `$usuario->load('roles')` after modifications

### 2. **API Responses**
Return JSON with consistent structure:
```php
// Success with data
return response()->json([
    'success' => true,
    'token' => $token,
    'usuario' => [
        'id' => $usuario->id,
        'nombre' => $usuario->nombre,
        'roles' => $usuario->roles->pluck('nombre')
    ]
], 200);

// Error
return response()->json(['success' => false, 'message' => 'Error text'], 401);
```

### 3. **Role-Based Access**
- Roles are loaded in API responses: `'roles' => $usuario->roles->pluck('nombre')`
- Use Spatie's policy/permission system for authorization (not yet implemented - prepare for it)
- Pivot table: `role_usuario` (usuario_id, role_id)

### 4. **Migration Naming**
- Migrations use timestamp prefixes: `2025_07_05_030352_create_usuarios_table.php`
- Custom constraint added later: `2025_07_15_030941_add_unique_constraint_to_recursos_nombre.php`

### 5. **Request Validation**
- Use `$request->validate()` for input validation
- Combine with null-coalescing for optional fields

## ⚙️ Configuration to Know

- **Session Driver**: database (stored in sessions table)
- **Cache Store**: database (queries cached in cache table)
- **Queue**: database (jobs stored for async processing)
- **JWT Expiry**: Check `config/jwt.php` 'ttl' setting (minutes)
- **Bcrypt Rounds**: 12 (for password hashing)

## 🔧 Common Tasks & Solutions

### Add a New API Endpoint
1. Create controller in `app/Http/Controllers/Api/`
2. Add `Route::apiResource('resource', Controller::class)` inside protected middleware group
3. Return JSON responses with consistent structure
4. Test with: `php artisan serve` + API client

### Modify Usuario Model
1. Create migration: `php artisan make:migration add_field_to_usuarios_table`
2. Update `$fillable` array in model
3. Update seeders if needed
4. Run: `php artisan migrate`

### Implement Role-Based Authorization
- Use Spatie traits: `use CanAccess, HasRoles;`
- Add `auth('api')->user()->hasRole('admin')` checks
- Consider dedicated Policies in `app/Policies/`

## 📱 Mobile/React Integration Notes

- JWT tokens are returned in API response (not in headers/cookies)
- Token should be stored client-side and sent as: `Authorization: Bearer <token>`
- Include token in all protected requests
- Handle token expiry gracefully (logout + redirect to login)

## 🚨 Common Pitfalls to Avoid

1. **Forgot to load roles**: `Usuario::find($id)` won't include roles - use `with('roles')`
2. **Plain text passwords**: Always `Hash::make()` before saving
3. **Wrong guard**: Using 'web' guard for API calls - must use 'api'
4. **Missing JWT secret**: Ensure `.env` has `JWT_SECRET` or run `php artisan jwt:secret`
5. **Seed order matters**: RoleSeeder must run before UsuarioSeeder (defined in DatabaseSeeder)

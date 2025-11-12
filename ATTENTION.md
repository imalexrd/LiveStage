# ATTENTION.md

## Foco Principal

El foco principal del equipo en este momento es completar el **Hito 3: Dashboard de Músicos y Perfiles Públicos**. El objetivo es hacer que los perfiles aprobados sean visibles para los clientes, mejorando el dashboard principal y creando páginas de perfil público para cada músico.

## Guía de Instalación y Replicación

### 1. Dependencias del Entorno

Antes de instalar el proyecto, es necesario asegurarse de que el entorno de desarrollo cumple con los siguientes requisitos. Los siguientes comandos son para un sistema basado en Ubuntu:

```bash
# Actualizar la lista de paquetes
sudo apt-get update

# Instalar PHP 8.3 y extensiones necesarias
sudo apt-get install -y php8.3 php8.3-pgsql php8.3-zip php8.3-xml php8.3-curl php8.3-mbstring

# Instalar Composer
sudo apt-get install -y composer

# Instalar PostgreSQL
sudo apt-get install -y postgresql postgresql-contrib
```

### 2. Instalación del Proyecto

Una vez que las dependencias del entorno estén instaladas, sigue estos pasos para poner en marcha el proyecto:

```bash
# 1. Clona el repositorio (si aún no lo has hecho)
git clone <URL_DEL_REPOSITORIO>
cd <NOMBRE_DEL_DIRECTORIO>

# 2. Instala las dependencias de Composer
composer install

# 3. Instala las dependencias de NPM
npm install

# 4. Compila los assets del frontend
npm run build

# 5. Crea tu archivo de entorno a partir del ejemplo
cp .env.example .env

# 6. Genera la clave de la aplicación
php artisan key:generate

# 7. Configura tu base de datos en el archivo .env
# Asegúrate de que las variables DB_DATABASE, DB_USERNAME y DB_PASSWORD
# coincidan con tu configuración de PostgreSQL.

# 8. Configura las credenciales de Google OAuth en .env
# Añade tu Client ID y Client Secret de Google.
# GOOGLE_CLIENT_ID=
# GOOGLE_CLIENT_SECRET=

# 9. Ejecuta las migraciones y los seeders para poblar la base de datos
php artisan migrate:fresh --seed
```

### 3. Base de Datos de Prueba

Después de ejecutar los seeders, la base de datos contendrá tres usuarios de prueba. Puedes iniciar sesión con las siguientes credenciales:
- **Admin:** `admin@example.com` / `password`
- **Manager:** `manager@example.com` / `password`
- **Client:** `client@example.com` / `password`

## Notas y Contexto para el Futuro

### Decisiones Técnicas del Hito 3

- **Dashboard de Músicos:** Se ha creado un componente de Livewire (`MusiciansList`) para mostrar una lista de los perfiles de músicos que han sido aprobados. Este componente se muestra en el dashboard principal para los usuarios con roles de `client` y `admin`.
- **Perfiles Públicos:** Se ha implementado una página de perfil público para cada músico, accesible a través de una URL única generada con un `uuid`. Se ha creado un nuevo controlador (`MusicianProfileController`) y una vista para mostrar los detalles del perfil.
- **Rutas y Navegación:** Se ha añadido una nueva ruta (`/profiles/{uuid}`) para los perfiles públicos y se ha actualizado la lista de músicos en el dashboard para enlazar a estas páginas. Se resolvió un conflicto de nombres de rutas para evitar errores de autorización.

### Decisiones Técnicas del Hito 2

- **Gestión de Perfiles de Músico:** Se ha implementado un flujo completo para que los usuarios con el rol de `manager` puedan crear y editar sus perfiles de músico. Esto incluye un nuevo modelo `MusicianProfile` con una relación uno a uno con el modelo `User`, y una página dedicada para la gestión del perfil.
- **Panel de Administración:** Se ha instalado y configurado **FilamentPHP** para crear un panel de administración interno. Este panel permite a los usuarios con el rol de `admin` revisar y aprobar los perfiles de los músicos.
- **Autorización:** Se ha implementado un sistema de autorización basado en roles. El acceso a la página de gestión de perfiles de músico está restringido a los `managers`, y el panel de administración de Filament está restringido a los `admins`.

### Decisiones Técnicas del Hito 1

- **Framework y Starter Kit:** Se ha utilizado **Laravel 11+** como framework principal. Para la autenticación estándar, se ha implementado **Laravel Breeze** con el stack **TALL (Livewire)**. Esta elección acelera el desarrollo del frontend al permitir construir interfaces reactivas directamente con PHP.
- **Autenticación OAuth:** Se ha integrado **Laravel Socialite** para manejar el inicio de sesión con proveedores externos. El primer proveedor implementado es Google, sentando las bases para añadir otros en el futuro.
- **Base de Datos:** Se ha seleccionado **PostgreSQL** como motor de base de datos, siguiendo las especificaciones del proyecto. La configuración del entorno local está documentada en esta guía.
- **Datos de Prueba:** Se ha creado un `UserSeeder` para facilitar las pruebas de desarrollo, evitando la necesidad de registrar usuarios manualmente cada vez que se refresca la base de datos.

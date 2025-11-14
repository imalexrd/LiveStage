# ATTENTION.md

## Foco Principal

El foco principal del equipo en este momento es completar el **Hito 4: Perfiles de Músico Enriquecidos**. El objetivo es enriquecer los perfiles de los músicos con contenido multimedia, como una foto de banner, galería de imágenes, videos y pistas de audio.

## Guía de Instalación y Replicación

Esta guía proporciona los pasos para configurar el entorno de desarrollo desde cero.

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

### 2. Configuración de la Base de Datos

Después de instalar PostgreSQL, es necesario configurar la base de datos y el usuario que la aplicación utilizará.

```bash
# Iniciar el servicio de PostgreSQL
sudo service postgresql start

# Crear la base de datos para la aplicación
sudo -u postgres psql -c "CREATE DATABASE laravel;"

# Crear un nuevo usuario (reemplaza 'password' con una contraseña segura)
sudo -u postgres psql -c "CREATE USER root WITH PASSWORD 'password';"

# Otorgar todos los privilegios de la base de datos al nuevo usuario
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE laravel TO root;"

# Otorgar privilegios sobre el esquema public (necesario para las migraciones)
sudo -u postgres psql -c "GRANT ALL ON SCHEMA public TO root;" -d laravel
```

### 3. Instalación del Proyecto

Una vez que las dependencias y la base de datos estén listas, sigue estos pasos:

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

# 5. Crea y configura tu archivo de entorno
# El archivo .env.example está incompleto. Copia el siguiente contenido a un nuevo
# archivo llamado .env y ajústalo según tu configuración local.
# --- INICIO DEL CONTENIDO PARA .env ---
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=password

BROADCAST_CONNECTION=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120
MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback
# --- FIN DEL CONTENIDO PARA .env ---

# 6. Genera la clave de la aplicación (esto llenará la variable APP_KEY en .env)
php artisan key:generate

# 7. Ejecuta las migraciones y los seeders para poblar la base de datos
php artisan migrate:fresh --seed

# 8. Crea el enlace simbólico para el almacenamiento público
php artisan storage:link
```

### 4. Base de Datos de Prueba

Después de ejecutar los seeders, la base de datos contendrá tres usuarios de prueba. Puedes iniciar sesión con las siguientes credenciales:
- **Admin:** `admin@example.com` / `password`
- **Manager:** `manager@example.com` / `password`
- **Client:** `client@example.com` / `password`

## Nota para Futuros Agentes de IA

Para acelerar el proceso de configuración en el futuro, se recomienda crear una instantánea (snapshot) del entorno una vez que la instalación se haya completado y verificado. Esto permitirá restaurar un estado de trabajo funcional sin tener que repetir todos los pasos de instalación.

## Notas y Contexto para el Futuro

### Sistema de Archivos Públicos y Multimedia

Para mostrar imágenes, videos y otros archivos multimedia en el frontend, el proyecto utiliza el sistema de almacenamiento público de Laravel. A continuación se detalla cómo funciona y cómo utilizarlo:

1.  **Almacenamiento:** Todos los archivos subidos por los usuarios se guardan en el directorio `storage/app/public`. Esta carpeta no es directamente accesible desde la web por razones de seguridad.

2.  **Enlace Simbólico (Symlink):** Para hacer que estos archivos sean accesibles públicamente, se utiliza el comando `php artisan storage:link`. Este comando crea un "acceso directo" o *symlink* desde `public/storage` hacia `storage/app/public`. El servidor web (Nginx/Apache) solo tiene acceso al directorio `public`, por lo que este enlace es crucial.

3.  **Cómo Referenciar Archivos en las Vistas (Blade):** Para generar la URL correcta de un archivo en una vista, se debe utilizar el helper `asset()`. Este helper genera una URL completa al archivo dentro del directorio `public`.

    **Ejemplo Práctico:**

    Supongamos que un músico sube una imagen de banner y la ruta se guarda en la base de datos en la columna `banner_image_path` con el valor: `banners/mi-imagen.jpg`.

    Para mostrar esta imagen, el código en la vista Blade sería el siguiente:

    ```blade
    <img src="{{ asset('storage/' . $musician->banner_image_path) }}" alt="Banner del Músico">
    ```

    -   `asset()`: Genera la URL base (ej. `http://localhost:8000`).
    -   `'storage/'`: Apunta al *symlink* creado en el directorio `public`.
    -   `$musician->banner_image_path`: Es la ruta relativa del archivo guardada en la base de datos.

    El resultado final será una URL como: `http://localhost:8000/storage/banners/mi-imagen.jpg`, que el navegador puede cargar correctamente.

### Decisiones Técnicas del Hito 4

- **Perfiles Públicos:** Se ha tomado la decisión de hacer que los perfiles de los músicos y todo su contenido multimedia (imágenes, videos, audio) sean de acceso público. Esto elimina la necesidad de que los usuarios inicien sesión para ver los perfiles, lo que simplifica el acceso y la promoción de los artistas. Como resultado, se ha eliminado la autenticación de las rutas de visualización de perfiles y de servicio de archivos.

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

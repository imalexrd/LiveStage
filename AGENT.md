# AGENT.MD: Guía Esencial para Agentes de IA

Este documento proporciona toda la información necesaria para configurar, desarrollar y probar este proyecto.

---

## 1. Arquitectura y Patrones de Diseño

El backend sigue un patrón de **Capa de Servicios (Service Layer)**.

-   **Lógica de Negocio:** Toda la lógica de negocio se encuentra en clases de servicio dedicadas dentro del directorio `app/Services`.
-   **Controladores y Componentes:** Los controladores de Laravel y los componentes de Livewire deben ser "delgados" (thin), su única responsabilidad es manejar la entrada/salida HTTP y llamar a los servicios.
-   **Flujo de Datos:** Para estandarizar el flujo de datos entre las capas, utilizamos **Data Transfer Objects (DTOs)** con el paquete `spatie/laravel-data`.

El objetivo a largo plazo es exponer toda la lógica de negocio a través de una **API RESTful**, adoptando un enfoque "API-First".

---

## 2. Guía de Instalación Rápida

Esta guía asume un entorno basado en Ubuntu.

### Paso 1: Dependencias del Sistema

```bash
sudo apt-get update
sudo apt-get install -y php8.3 php8.3-pgsql php8.3-zip php8.3-xml php8.3-curl php8.3-mbstring php8.3-dom composer postgresql postgresql-contrib
```

### Paso 2: Configuración de la Base de Datos

```bash
# Iniciar PostgreSQL
sudo service postgresql start

# Crear bases de datos y usuario
sudo -u postgres psql -c "CREATE DATABASE laravel;"
sudo -u postgres psql -c "CREATE DATABASE laravel_test;"
sudo -u postgres psql -c "CREATE USER root WITH PASSWORD 'password';"

# Otorgar privilegios a la base de datos principal
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE laravel TO root;"
sudo -u postgres psql -c "GRANT ALL ON SCHEMA public TO root;" -d laravel

# Otorgar privilegios a la base de datos de prueba
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE laravel_test TO root;"
sudo -u postgres psql -c "GRANT ALL ON SCHEMA public TO root;" -d laravel_test
```

### Paso 3: Instalación de la Aplicación

```bash
# Instalar dependencias de PHP
composer install

# Instalar dependencias de JS y compilar assets
npm install
npm run build

# Crear y configurar archivo .env para desarrollo
cp .env.example .env
php artisan key:generate

# Crear archivo .env.testing para pruebas
# La clave debe ser una cadena válida de 32 bytes codificada en base64.
echo "APP_ENV=testing" > .env.testing
echo "APP_KEY=base64:$(openssl rand -base64 32)" >> .env.testing
echo "DB_CONNECTION=pgsql" >> .env.testing
echo "DB_HOST=127.0.0.1" >> .env.testing
echo "DB_PORT=5432" >> .env.testing
echo "DB_DATABASE=laravel_test" >> .env.testing
echo "DB_USERNAME=root" >> .env.testing
echo "DB_PASSWORD=password" >> .env.testing
echo "CACHE_DRIVER=array" >> .env.testing
echo "QUEUE_CONNECTION=sync" >> .env.testing
echo "SESSION_DRIVER=array" >> .env.testing

# Ejecutar migraciones y seeders
php artisan migrate:fresh --seed
```

---

## 3. Entorno de Pruebas

-   **Ejecutar la suite de pruebas completa:**
    ```bash
    ./vendor/bin/phpunit
    ```
-   **Usuarios de Prueba:** La base de datos se puebla con los siguientes usuarios, todos con la contraseña `password`:
    -   **Admin:** `admin@example.com`
    -   **Manager:** `manager@example.com`
    -   **Client:** `client@example.com`

---

## 4. Contexto Técnico Adicional

-   **Pre-existing Bugs:**
    -   Existe un bug conocido en el `BookingController` y `BookingTest` donde se hace referencia a una columna `event_location` que no existe en la base de datos. Las columnas correctas son `location_address`, `location_latitude` y `location_longitude`.
    -   Los valores de estado en `BookingController` (`accepted`, `rejected`) no coinciden con los valores permitidos en la migración de la tabla `bookings` (`pending`, `confirmed`, `cancelled`, `completed`).
    -   Hay un test inestable, `AuthenticationTest`, que puede fallar al no encontrar texto que sí está presente en la respuesta. Este parece ser un problema del entorno de pruebas.

-   **Sistema de Archivos:** Las imágenes y otros medios se suben a `storage/app/public`. Para que sean accesibles desde la web, se debe crear un enlace simbólico con `php artisan storage:link`. En las vistas de Blade, siempre se debe usar el helper `asset('storage/...')` para generar las URLs correctas.

### **Hoja de Especificaciones Técnicas Plataforma de Booking de Músicos**

### **1. Visión General del Proyecto**

#### **1.1. Concepto**
Se desarrollará un marketplace digital de alto rendimiento que funcionará como el ecosistema principal para la contratación de talento musical. La plataforma conectará de manera fluida a dos grupos de usuarios: clientes que organizan eventos y artistas/músicos que ofrecen sus servicios. El sistema centralizará todas las etapas del proceso de contratación: búsqueda y descubrimiento, gestión de disponibilidad y calendarios, cotizaciones, comunicación directa, reservaciones seguras, procesamiento de pagos, opciones de financiamiento y un sistema de reseñas para construir reputación y confianza.

#### **1.2. Objetivo Estratégico**
El objetivo principal es construir un backend escalable, seguro y bien documentado utilizando el framework Laravel. Este backend servirá como el cerebro de la operación, exponiendo una API RESTful completa que dará servicio a la aplicación web inicial y estará preparada para soportar futuras aplicaciones móviles nativas (iOS y Android) sin necesidad de reingeniería.

#### **1.3. Roles de Usuario y Sus Funciones**

Se definen tres roles de usuario distintos, cada uno con un conjunto claro de permisos y responsabilidades dentro de la plataforma:

1.  **Cliente (Client):**
    *   **Definición:** Cualquier persona, empresa u organización que busca contratar talento musical. Esto puede ir desde un individuo planeando una boda hasta un organizador de eventos corporativos.
    *   **Funciones:** Pueden registrarse, buscar artistas usando un sistema de filtros avanzados, ver perfiles detallados, solicitar reservaciones, comunicarse directamente con el manager del artista, realizar pagos de forma segura a través de la plataforma y dejar una reseña pública después de que el evento se haya completado.

2.  **Manager (Manager):**
    *   **Definición:** Es la cuenta oficial que representa a **una única entidad artística**. Esta entidad puede ser una banda, un artista solista, un DJ, un trío, etc. La relación entre una cuenta de Manager y un Perfil de Artista es **estrictamente uno-a-uno**.
    *   **Funciones:** Es responsable de crear y mantener actualizado el perfil público del artista, incluyendo biografía, galería de fotos y videos, repertorio y precios. Gestiona el calendario de disponibilidad, responde a las solicitudes de reservación (aceptando o rechazando), se comunica con los clientes sobre los detalles del evento y gestiona las ganancias obtenidas a través de la plataforma.

3.  **Administrador (Admin):**
    *   **Definición:** Es un miembro del equipo interno de la plataforma con acceso privilegiado al panel de control.
    *   **Funciones:** Tiene una visión global de toda la plataforma. Sus responsabilidades incluyen la aprobación de nuevos perfiles de artistas para mantener un estándar de calidad, la moderación de contenido (reseñas, fotos), la gestión de todos los usuarios (editar, banear), la supervisión de todas las transacciones financieras, la mediación en caso de disputas entre clientes y managers, y la gestión de la configuración general de la plataforma (como categorías y géneros musicales).

---

### **2. Arquitectura y Stack Tecnológico**

La elección del stack tecnológico está orientada a la escalabilidad, el rendimiento y la velocidad de desarrollo.

*   **Framework Backend:** **Laravel 11+** (o la última versión estable disponible). Elegido por su ecosistema robusto, seguridad integrada y herramientas para un desarrollo rápido (Eloquent ORM, Sanctum, Socialite).
*   **Base de Datos:** **PostgreSQL 15+** con la extensión **PostGIS** activada. PostgreSQL se elige por su robustez y manejo avanzado de JSONB. PostGIS es un requisito no negociable para implementar funcionalidades de geolocalización avanzadas, como la búsqueda por radio ("músicos a 50 km de mi ubicación").
*   **Servidor Web:** **Nginx**. Reconocido por su alto rendimiento, estabilidad y bajo consumo de recursos, ideal para servir tanto la API como los activos estáticos del frontend.
*   **Intérprete de PHP:** **PHP 8.3+**. Para aprovechar las últimas mejoras de rendimiento, características del lenguaje y parches de seguridad.
*   **Gestión de Cache y Colas:** **Redis**. Se utilizará de dos maneras: como un sistema de caché para almacenar resultados de consultas frecuentes (ej. perfiles de músicos, lista de géneros) y como un robusto gestor de colas para procesar tareas en segundo plano (ej. envío de correos de notificación, generación de contratos en PDF, procesamiento de imágenes subidas).
*   **API:** **API RESTful**. El diseño seguirá los principios RESTful para asegurar endpoints predecibles y coherentes. La documentación será generada y mantenida utilizando el estándar **OpenAPI (Swagger)** para facilitar la integración con el frontend y futuras aplicaciones.
*   **Autenticación:** Se implementará un sistema dual:
    1.  **Laravel Sanctum:** Para la autenticación basada en tokens de API, ideal para Single Page Applications (SPAs) y aplicaciones móviles. Gestionará el login tradicional con email/contraseña.
    2.  **Laravel Socialite:** Para integrar proveedores de autenticación OAuth 2.0, comenzando con **"Iniciar sesión con Google"** para ofrecer una experiencia de registro y login fluida.
*   **Frontend Web (MVP):** **Laravel Blade con el stack TALL (Tailwind CSS, Alpine.js, Laravel, Livewire)**. Esta elección permite construir una interfaz de usuario moderna y reactiva sin la complejidad de un framework de JavaScript completo, acelerando significativamente el desarrollo inicial.
*   **Pasarela de Pagos:** **Stripe Connect**. Es la solución estándar de la industria para marketplaces. Maneja de forma segura y automatizada el flujo completo de pagos: onboarding de los managers (vendedores), verificación de identidad (KYC), procesamiento de pagos de clientes, división automática de fondos (comisión para la plataforma y pago al manager) y transferencias a las cuentas bancarias de los managers.
*   **Servicios de Financiamiento:** **APIs de Affirm o Klarna**. Se integrarán como opciones de pago alternativas en el checkout, permitiendo a los clientes pagar sus eventos a plazos, lo que puede aumentar la tasa de conversión para bookings de mayor valor.
*   **Almacenamiento de Archivos:** **Amazon S3** (o un servicio compatible como DigitalOcean Spaces). Todos los archivos subidos por los usuarios (fotos de perfil, galerías de artistas, contratos en PDF) se almacenarán en un bucket en la nube para garantizar la escalabilidad, seguridad y disponibilidad.
*   **Panel de Administración:** **Filament PHP**. Un moderno constructor de paneles de administración para Laravel que acelera drásticamente el desarrollo de la interfaz de gestión interna, ofreciendo una experiencia de usuario rica y personalizable para los administradores.

---

### **3. Estructura de la Base de Datos y Modelos Eloquent (Reestructurada)**

La estructura está optimizada para la relación uno-a-uno entre Manager y Perfil de Músico.

**Tabla: `users` | Modelo: `User`**
*   `id` (PK, BigInt, Unsigned)
*   `name` (String): Nombre completo del usuario.
*   `email` (String, Unique): Correo para login, único en todo el sistema.
*   `password` (String, Hashed, Nullable): Nulo para usuarios registrados vía OAuth.
*   `email_verified_at` (Timestamp, Nullable): Fecha de verificación del email.
*   `role` (Enum: `client`, `manager`, `admin`): Define el tipo de usuario.
*   `phone_number` (String, Nullable): Teléfono de contacto.
*   `profile_picture_url` (String, Nullable): URL a la foto de perfil.
*   `google_id` (String, Nullable, Unique): ID único del usuario de Google para login social.
*   `stripe_customer_id` (String, Nullable): ID de cliente en Stripe (para Clientes que pagan).
*   `banned_at` (Timestamp, Nullable): Marca si un usuario está baneado.
*   `remember_token`, `timestamps`
*   **Relaciones Eloquent:**
    *   `hasOne(MusicianProfile::class, 'manager_id')`: **(RELACIÓN CLAVE)** Un usuario con rol 'manager' tiene exactamente un perfil de músico asociado.
    *   `hasMany(Booking::class, 'client_id')`: Las reservaciones que ha hecho como cliente.
    *   `hasMany(Review::class, 'client_id')`: Las reseñas que ha escrito como cliente.

**Tabla: `musician_profiles` | Modelo: `MusicianProfile`**
*   `id` (PK)
*   `manager_id` (FK a `users.id`, **Unique**): **(RESTRICCIÓN CLAVE)** La restricción `UNIQUE` a nivel de base de datos garantiza físicamente la relación uno-a-uno. Un usuario solo puede aparecer una vez en esta columna.
*   `artist_name` (String): El nombre público del artista o banda.
*   `bio` (Text): Descripción detallada, historia, miembros, etc.
*   `location_city` (String)
*   `location_state` (String)
*   `location_coordinates` (**Point**, Nullable, SRID 4326): Tipo de dato geográfico de PostGIS para almacenar latitud y longitud.
*   `base_price_per_hour` (Decimal): Precio base que se usa para cálculos iniciales.
*   `is_approved` (Boolean, Default: `false`): Indica si un admin ha aprobado el perfil para que sea visible públicamente.
*   `stripe_connect_id` (String, Nullable): El ID de la cuenta conectada de Stripe del manager, necesaria para recibir pagos.
*   `is_premium` (Boolean, Default: `false`): Si tiene una suscripción premium activa.
*   `premium_expires_at` (Timestamp, Nullable): Fecha de expiración del plan premium.
*   **Relaciones Eloquent:**
    *   `belongsTo(User::class, 'manager_id')`
    *   `belongsToMany(Genre::class, 'musician_genre')`
    *   `hasMany(Media::class)`
    *   `hasMany(Booking::class, 'musician_profile_id')`
    *   `hasMany(Review::class, 'musician_profile_id')`
    *   `hasMany(Availability::class, 'musician_profile_id')`

**Tabla: `availabilities` | Modelo: `Availability`**
*   `id` (PK)
*   `musician_profile_id` (FK a `musician_profiles.id`)
*   `unavailable_date` (Date): El manager marca esta fecha como no disponible en su calendario.
*   `timestamps`
*   **Relaciones Eloquent:**
    *   `belongsTo(MusicianProfile::class)`

**Tabla: `bookings` | Modelo: `Booking`**
*   `id` (PK)
*   `client_id` (FK a `users.id`)
*   `musician_profile_id` (FK a `musician_profiles.id`)
*   `event_date` (Date)
*   `start_time` (Time)
*   `end_time` (Time)
*   `status` (Enum: `pending`, `confirmed`, `cancelled`, `completed`)
*   `total_price` (Decimal)
*   `location_address` (String, Nullable): Dirección del evento, capturada con Google Maps.
*   `location_latitude` (Decimal, Nullable): Latitud para funcionalidades de mapa.
*   `location_longitude` (Decimal, Nullable): Longitud para funcionalidades de mapa.
*   `timestamps`
*   **Relaciones Eloquent:**
    *   `belongsTo(User::class, 'client_id')`
    *   `belongsTo(MusicianProfile::class)`

**Tabla: `payments`, `reviews`, `media`, `categories`, `genres` y las tablas de mensajería permanecen estructuralmente iguales a la versión anterior, ya que sus relaciones con `musician_profiles` y `users` son correctas y se adaptan a este modelo.**

---

### **4. Flujo de Autenticación Detallado**

#### **4.1. Autenticación Tradicional (Email y Contraseña)**
1.  **Registro (`POST /api/register`):** El usuario provee `name`, `email`, `password`, y `role`. El backend valida los datos, hashea la contraseña usando Bcrypt, crea el usuario en la base de datos y opcionalmente envía un correo de verificación.
2.  **Login (`POST /api/login`):** El usuario provee `email` y `password`. El backend verifica las credenciales. Si son correctas, genera un token de texto plano de Laravel Sanctum y lo devuelve en la respuesta JSON.
3.  **Acceso Protegido:** El cliente frontend almacena el token de forma segura y lo incluye en la cabecera `Authorization: Bearer <token>` en todas las solicitudes a endpoints protegidos. El middleware `auth:sanctum` de Laravel valida el token en cada petición.
4.  **Logout (`POST /api/logout`):** El backend invalida el token de acceso actual del usuario, cerrando la sesión de forma segura en ese dispositivo.

#### **4.2. Autenticación con Google OAuth (Laravel Socialite)**
1.  **Paso 1: Redirección (`GET /api/auth/google/redirect`):**
    *   El frontend invoca este endpoint.
    *   El backend utiliza Socialite para generar la URL de autorización única de Google y la devuelve en una respuesta JSON (`{ "redirect_url": "..." }`).
    *   El frontend redirige al usuario a esta URL.
2.  **Paso 2: Callback (`POST /api/auth/google/callback`):**
    *   El usuario se autentica en Google y autoriza la aplicación.
    *   Google redirige al usuario de vuelta al frontend con un código de autorización.
    *   El frontend envía este código al endpoint de callback en el backend.
3.  **Paso 3: Gestión de Usuario y Emisión de Token:**
    *   El backend intercambia el código con Google para obtener los datos del usuario (nombre, email, google_id).
    *   Se busca un usuario en la tabla `users` con ese `google_id`.
        *   **Si existe:** Se le autentica.
        *   **Si no existe:** Se busca por `email`.
            *   Si el email existe pero sin `google_id`, se vincula la cuenta de Google al usuario existente.
            *   Si el email no existe, se crea un nuevo usuario con los datos de Google, `password` nulo y rol `client` por defecto.
    *   Finalmente, el backend genera un token de Sanctum para el usuario autenticado o recién creado y lo devuelve al frontend, completando el inicio de sesión.

---

### **5. Core Features y Endpoints de la API (Reestructurados)**

La API está diseñada para ser intuitiva y RESTful, aprovechando la relación 1-a-1 del manager.

#### **5.1. Autenticación (`AuthController`)**
*   `POST /api/register` (Pública)
*   `POST /api/login` (Pública)
*   `POST /api/logout` (Autenticado)
*   `GET /api/auth/google/redirect` (Pública)
*   `POST /api/auth/google/callback` (Pública)
*   `GET /api/user` (Autenticado): Obtiene los datos del usuario logueado.

#### **5.2. Perfiles Públicos y Búsqueda (`PublicProfileController`, `MusicianSearchController`)**
*   `GET /search`: Muestra la página de búsqueda de músicos.
*   `GET /api/profiles`: Lista pública de perfiles aprobados. Acepta filtros como `?genre=cumbia&city=denver&radius=50&date=2025-12-24`.
*   `GET /api/profiles/{profileId}`: Vista pública detallada de un perfil de músico.
*   `GET /api/genres`: Lista todas las categorías y géneros.

#### **5.3. Gestión de Perfil de Manager (`ManagerProfileController`) - Rutas protegidas para rol `manager`**
La API es más limpia ya que el manager opera sobre su propio perfil implícito.

*   `GET /api/manager/profile`: **(SIMPLIFICADO)** Obtiene el perfil del manager autenticado.
*   `POST /api/manager/profile`: **(SIMPLIFICADO)** Crea el perfil de músico. Falla si ya existe uno.
*   `PUT /api/manager/profile`: **(SIMPLIFICADO)** Actualiza el perfil existente.
*   `POST /api/manager/profile/media`: **(SIMPLIFICADO)** Sube una nueva foto/video a la galería del perfil.
*   `DELETE /api/manager/profile/media/{mediaId}`: **(SIMPLIFICADO)** Elimina un archivo de la galería.
*   `GET /api/manager/profile/availability`: **(SIMPLIFICADO)** Obtiene las fechas no disponibles del calendario.
*   `POST /api/manager/profile/availability`: **(SIMPLIFICADO)** Marca una fecha como no disponible.
*   `DELETE /api/manager/profile/availability/{availabilityId}`: **(SIMPLIFICADO)** Libera una fecha.

#### **5.4. Reservaciones, Pagos y Contratos (`BookingController`)**
*   `POST /api/bookings`: (Autenticado: Cliente) Envía una solicitud de reservación.
*   `GET /api/bookings`: (Autenticado) Lista las reservaciones del usuario (vista diferente para Cliente y Manager).
*   `GET /api/bookings/{id}`: (Autenticado) Ver el detalle de una reservación específica.
*   `POST /api/bookings/{id}/confirm`: (Autenticado: Manager) Confirma una solicitud pendiente.
*   `POST /api/bookings/{id}/cancel`: (Autenticado) Cancela una reservación.
*   `GET /api/bookings/{id}/contract`: (Autenticado) Descarga el PDF del contrato generado.
*   `POST /api/bookings/{id}/pay`: (Autenticado: Cliente) Inicia el proceso de pago.

#### **5.5. Mensajería (`MessagingController`) y Reseñas (`ReviewController`)**
*   `GET /api/bookings/{bookingId}/messages`: (Autenticado) Obtiene los mensajes de una conversación.
*   `POST /api/bookings/{bookingId}/messages`: (Autenticado) Envía un nuevo mensaje.
*   `POST /api/bookings/{bookingId}/review`: (Autenticado: Cliente) Deja una reseña para una reservación completada.

#### **5.6. Panel de Administración (`Admin/...`)**
*   Rutas prefijadas con `/api/admin/` y protegidas por middleware `is_admin`.
*   Endpoints para gestionar usuarios, aprobar perfiles, supervisar bookings, etc.

---

### **6. Plan de Desarrollo por Fases (Roadmap)**

**Fase 1: Mínimo Producto Viable (MVP) - Completado**
*   **Objetivo:** Lanzar la funcionalidad central para validar el modelo de negocio.
*   **Features:**
    1.  Sistema de autenticación completo: Email/contraseña y Google OAuth.
    2.  Flujo de creación y gestión del **perfil único** de músico por parte del Manager.
    3.  Proceso de **aprobación de perfiles** por parte del Admin.
    4.  Búsqueda pública de músicos con filtros básicos (género, ciudad).
    5.  Sistema de reservaciones (solicitar, confirmar, cancelar) **sin integración de pagos**. La coordinación del pago se hará fuera de la plataforma.
    6.  Panel de administración funcional (Filament) para gestionar usuarios, perfiles y géneros.

**Fase 2: Búsqueda y Descubrimiento - Completado**
*   **Objetivo:** Crear una funcionalidad de búsqueda y descubrimiento que permita a los clientes encontrar músicos basándose en criterios específicos como el género musical, la ubicación, el tipo de evento y el rango de precios.
*   **Features:**
    1.  Implementación de una página de búsqueda con filtros dinámicos.
    2.  Extensión de la base de datos para incluir géneros, tipos de evento y coordenadas de ubicación.
    3.  Actualización de la página de gestión de perfiles para permitir a los músicos añadir esta información.
    4.  Búsqueda por nombre, ciudad, género, tipo de evento y rango de precios.

**Fase 3: Monetización y Confianza**
*   **Objetivo:** Activar los flujos de ingresos y construir un ecosistema seguro y confiable.
*   **Features:**
    1.  Integración completa de **Stripe Connect**: Onboarding de Managers (KYC) para recibir pagos.
    2.  Flujo de pago para Clientes al confirmar una reservación, con cálculo y cobro automático de la comisión de la plataforma.
    3.  Implementación del **Sistema de Mensajería Interna** por booking.
    4.  Implementación del **Calendario de Disponibilidad Básico**.
    5.  Generación automática de **Contratos en PDF** al confirmar un booking.
    6.  Sistema completo de **Notificaciones por Email** para todos los eventos clave.

**Fase 4: Crecimiento y Experiencia de Usuario Avanzada**
*   **Objetivo:** Mejorar la plataforma con funcionalidades premium, herramientas avanzadas de búsqueda y preparación para la expansión móvil.
*   **Features:**
    1.  **Suscripciones Premium para Managers**: Planes de pago para destacar perfiles en los resultados de búsqueda.
    2.  **Integración de Financiamiento**: Añadir Klarna/Affirm como opción de pago.
    3.  **Búsqueda Geográfica Avanzada**: Implementar la búsqueda por radio ("a X km de mí") usando PostGIS.
    4.  **Mensajería en Tiempo Real (WebSockets)**: Usando Laravel Reverb o Pusher para una experiencia de chat instantánea.
    5.  Refinamiento y documentación exhaustiva de la API para el desarrollo de la **App Móvil**.
    6.  **Dashboard avanzado para Managers** con estadísticas de rendimiento de su perfil (visitas, solicitudes, tasa de conversión, ingresos).

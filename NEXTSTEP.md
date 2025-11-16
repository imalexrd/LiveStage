# Propuesta de Próximo Hito: Hito 9 - Ubicación de Eventos con Google Maps

## 1. Contexto General

Actualmente, el proceso de booking no permite a los clientes especificar la ubicación exacta del evento. Para mejorar la claridad y la comunicación entre clientes y músicos, se añadirá una funcionalidad que permite a los clientes, de forma opcional, añadir una dirección precisa al crear o gestionar una solicitud de booking. Esta funcionalidad se integrará con la API de Google Maps para ofrecer una experiencia de usuario fluida y precisa en la captura de direcciones.

## 2. Tareas a Desarrollar

### 2.1. Actualizaciones de la Base de Datos

-   **Tarea:** Modificar la tabla `bookings` para almacenar la información de la ubicación.
-   **Detalles:**
    -   Crear una nueva migración para añadir las siguientes columnas a la tabla `bookings`:
        -   `location_address` (String, Nullable): Para almacenar la dirección de texto.
        -   `location_latitude` (Decimal, Nullable): Para almacenar la latitud.
        -   `location_longitude` (Decimal, Nullable): Para almacenar la longitud.
    -   Actualizar el modelo `Booking` para que los nuevos campos sean `fillable`.

### 2.2. Mejoras en el Backend

-   **Tarea:** Actualizar la lógica del backend para manejar la nueva información de ubicación.
-   **Detalles:**
    -   Modificar el `BookingController` para validar y guardar los nuevos campos de ubicación al crear y actualizar una reserva. La validación debe ser opcional.
    -   Asegurarse de que el `BookingRequestForm` y cualquier otro componente de Livewire relevante manejen los nuevos campos.

### 2.3. Integración de Google Maps en el Frontend

-   **Tarea:** Implementar la API de Google Maps Places Autocomplete en el formulario de booking.
-   **Detalles:**
    -   **Configuración de API:** Añadir la clave de la API de Google Maps al archivo `.env` y cargar el script de Google Maps en las vistas relevantes.
    -   **Campo de Autocompletado:** En el formulario de creación de booking (`BookingRequestForm`), reemplazar el campo de texto de la ubicación por un campo de autocompletado de Google Places.
    -   **Población de Campos:** Al seleccionar una dirección, el formulario deberá capturar la dirección de texto, la latitud y la longitud, y guardarlos en campos ocultos que se enviarán al backend.
    -   **Componente Reutilizable:** Considerar la creación de un componente de Blade para el input de Google Maps, de modo que pueda ser reutilizado fácilmente.

### 2.4. Visualización y Edición de la Ubicación

-   **Tarea:** Permitir a los usuarios ver y editar la ubicación después de haber creado la reserva.
-   **Detalles:**
    -   **Vista de Detalles del Booking:** En la página de detalles del booking (`bookings.show`), mostrar la ubicación del evento en un mapa estático de Google Maps si ha sido proporcionada.
    -   **Funcionalidad de Edición:** Añadir un botón de "Añadir/Editar Ubicación" en la página de detalles del booking. Este botón deberá abrir un modal con el mismo componente de autocompletado de Google Maps para que el cliente pueda añadir o actualizar la dirección.

## 3. Punto de Verificación del Hito

El hito se considerará completado cuando se cumplan los siguientes puntos:
1.  La base de datos ha sido actualizada con los nuevos campos de ubicación.
2.  Los clientes pueden añadir opcionalmente una ubicación al crear una nueva solicitud de booking.
3.  El campo de ubicación utiliza la API de Google Maps Places Autocomplete.
4.  La ubicación del evento, si existe, se muestra en la página de detalles del booking.
5.  Los clientes pueden añadir o actualizar la ubicación desde la página de detalles del booking.

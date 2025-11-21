# Próximo Objetivo: Consolidación de API de Pagos y Gestión de Disponibilidad

## Prompt para el Agente de IA

"Hola. Hemos completado la integración básica de Stripe en el frontend. Ahora necesitamos exponer estas funcionalidades a través de la API RESTful para futuras aplicaciones móviles y completar el sistema de gestión de disponibilidad de los músicos.

**Fase 1: API de Pagos (Paridad con Web)**

1.  **Endpoint de Onboarding (Manager):**
    *   **Ruta:** `GET /api/v1/manager/stripe/connect`
    *   **Lógica:** Debe generar y devolver la URL de Stripe Connect (Account Link) para el manager autenticado, similar a lo que hace el `StripeConnectController`.
    *   **Respuesta:** JSON `{ "url": "https://connect.stripe.com/setup/..." }`

2.  **Endpoint de Pago (Cliente):**
    *   **Ruta:** `POST /api/v1/bookings/{booking}/pay`
    *   **Lógica:** Debe utilizar el `StripePaymentService` para crear una sesión de Checkout para el booking especificado.
    *   **Respuesta:** JSON `{ "checkout_url": "https://checkout.stripe.com/..." }`

**Fase 2: Sistema de Disponibilidad (Calendario)**

1.  **Modelo y Migración:**
    *   Crea el modelo `Availability` y su migración.
    *   **Tabla `availabilities`:**
        *   `id`
        *   `musician_profile_id` (FK)
        *   `unavailable_date` (Date)
        *   `reason` (String, Nullable, ej: "Vacaciones", "Evento Privado")
        *   `timestamps`

2.  **Lógica de Servicio:**
    *   Actualiza `BookingService::checkAvailability` para que, además de revisar bookings confirmados, consulte la tabla `availabilities`. Si la fecha coincide con un registro en `availabilities`, debe lanzar una excepción.

3.  **API de Gestión de Disponibilidad (Manager):**
    *   `GET /api/v1/manager/availability`: Listar fechas no disponibles futuras.
    *   `POST /api/v1/manager/availability`: Bloquear una fecha (`unavailable_date`, `reason`).
    *   `DELETE /api/v1/manager/availability/{id}`: Desbloquear una fecha.

**Fase 3: Documentación API**

1.  **Actualizar AGENT.md:** Documenta los nuevos endpoints con ejemplos de `curl` y respuestas JSON esperadas."

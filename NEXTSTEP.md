# Hito 4: Sistema de Reservaciones Básico

## 1. Contexto General

Con los perfiles de músicos visibles y accesibles, el siguiente paso es permitir que los clientes inicien el proceso de contratación. Este hito se centra en implementar un sistema de reservaciones básico que permita a los clientes solicitar los servicios de un músico y a los managers aceptar o rechazar esas solicitudes. En esta fase, la gestión de pagos se realizará fuera de la plataforma.

## 2. Tareas a Desarrollar

### Sistema de Reservaciones (Booking)
- **Tarea:** Crear el flujo completo para que un `client` pueda solicitar una reservación a un `manager`.
- **Detalles:**
    - Crear un nuevo modelo `Booking` para almacenar los detalles de la reservación, incluyendo `musician_profile_id`, `client_id`, `event_date`, `status` (pendiente, confirmado, cancelado), etc.
    - En la página de perfil público de cada músico, añadir un formulario de solicitud de reservación.
    - Implementar la lógica para que el `manager` pueda ver las solicitudes de reservación pendientes en su dashboard.
    - Añadir acciones para que el `manager` pueda confirmar o cancelar una solicitud de reservación.

### Notificaciones por Email
- **Tarea:** Implementar un sistema de notificaciones por email para mantener a los usuarios informados sobre el estado de sus reservaciones.
- **Detalles:**
    - Enviar una notificación por email al `manager` cuando un `client` solicite una reservación.
    - Enviar una notificación por email al `client` cuando un `manager` confirme o cancele una reservación.

## 3. Punto de Verificación del Hito

La finalización de este hito se marca cuando el siguiente flujo sea completamente funcional:
1. Un `client` visita el perfil público de un músico y envía una solicitud de reservación para una fecha específica.
2. El `manager` del músico recibe una notificación por email y ve la solicitud pendiente en su dashboard.
3. El `manager` confirma la reservación.
4. El `client` recibe una notificación por email de que su reservación ha sido confirmada.

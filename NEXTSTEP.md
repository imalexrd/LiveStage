# Hito 3: Monetización y Confianza

## 1. Contexto General

Con los perfiles de músicos y el panel de administración ya funcionales, el siguiente paso crítico es activar los flujos de ingresos y construir un ecosistema seguro y confiable. Este hito se centra en la integración de una pasarela de pagos, la implementación de un sistema de mensajería interna y la gestión de la disponibilidad de los músicos.

## 2. Tareas a Desarrollar

### Integración de Stripe Connect
- **Tarea:** Integrar **Stripe Connect** para permitir que los managers (vendedores) registren sus cuentas bancarias y reciban pagos.
- **Detalles:**
    - Implementar el flujo de onboarding de Stripe Connect para los managers.
    - Almacenar el `stripe_connect_id` en el modelo `MusicianProfile`.
    - Asegurar que el proceso de verificación de identidad (KYC) de Stripe se maneje correctamente.

### Flujo de Pago para Clientes
- **Tarea:** Implementar el flujo de pago para que los clientes puedan contratar a los músicos.
- **Detalles:**
    - Integrar Stripe para procesar los pagos de los clientes.
    - Calcular y cobrar automáticamente la comisión de la plataforma en cada transacción.
    - Transferir los fondos restantes a la cuenta de Stripe Connect del manager.

### Sistema de Mensajería Interna
- **Tarea:** Implementar un sistema de mensajería interna para que clientes y managers puedan comunicarse dentro de la plataforma.
- **Detalles:**
    - Crear los modelos y las migraciones necesarias para la mensajería (p. ej., `Conversation`, `Message`).
    - Desarrollar la interfaz de usuario para la mensajería, permitiendo a los usuarios ver sus conversaciones y enviar mensajes.
    - Asegurar que las conversaciones estén asociadas a una reserva (`Booking`) específica.

### Calendario de Disponibilidad Básico
- **Tarea:** Implementar un calendario de disponibilidad básico para que los managers puedan marcar las fechas en las que no están disponibles.
- **Detalles:**
    - Crear el modelo y la migración para la disponibilidad (`Availability`).
    - Desarrollar una interfaz de calendario simple en el perfil del manager donde puedan seleccionar y guardar las fechas no disponibles.
    - Asegurar que la disponibilidad se tenga en cuenta al realizar una reserva.

### Generación Automática de Contratos en PDF
- **Tarea:** Generar automáticamente un contrato en PDF cuando se confirma una reserva.
- **Detalles:**
    - Utilizar una librería como `dompdf` o `snappy` para generar los PDFs.
    - Crear una plantilla de contrato con los detalles de la reserva.
    - Permitir que tanto el cliente como el manager descarguen el contrato desde la página de detalles de la reserva.

### Sistema de Notificaciones por Email
- **Tarea:** Implementar un sistema de notificaciones por email para todos los eventos clave.
- **Detalles:**
    - Enviar notificaciones por email para eventos como:
        - Nueva solicitud de reserva.
        - Confirmación de reserva.
        - Cancelación de reserva.
        - Nuevo mensaje en una conversación.
        - Perfil de músico aprobado.
    - Utilizar las colas de Laravel para enviar los emails de forma asíncrona.

## 3. Punto de Verificación del Hito

La finalización de este hito se marca cuando el siguiente flujo sea completamente funcional:
1. Un `manager` completa el proceso de onboarding de Stripe Connect.
2. Un `client` encuentra un músico y le envía una solicitud de reserva.
3. El `client` y el `manager` se comunican a través del sistema de mensajería interna para acordar los detalles.
4. El `manager` confirma la reserva.
5. El `client` realiza el pago a través de Stripe, y la plataforma cobra su comisión.
6. Se genera un contrato en PDF para la reserva.
7. El `manager` recibe los fondos en su cuenta de Stripe Connect.

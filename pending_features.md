# Propuesta de Próximo Hito: Hito 6 - Booking and Payments

## 1. Contexto General

Con la implementación de la búsqueda y el descubrimiento de músicos, los clientes ahora pueden encontrar a los artistas que desean. El siguiente paso lógico es permitirles reservar a estos músicos y realizar pagos seguros a través de la plataforma. Este hito es crucial para la monetización del marketplace y para proporcionar una experiencia de reserva completa y segura tanto para los clientes como para los músicos.

Este hito se centrará en la implementación de un sistema de booking completo, desde la solicitud inicial hasta la confirmación y el pago, utilizando Stripe Connect para gestionar las transacciones.

## 2. Tareas a Desarrollar

### 2.1. Backend: Lógica de Booking y Pagos

-   **Tarea:** Implementar la lógica de negocio para gestionar el ciclo de vida de una reserva y procesar los pagos.
-   **Detalles:**
    -   **Modelos y Controladores:** Crear los modelos `Booking` y `Payment`, y sus correspondientes controladores (`BookingController`, `PaymentController`).
    -   **Flujo de Booking:** Implementar el siguiente flujo:
        1.  El cliente envía una solicitud de reserva a un músico, especificando la fecha, el lugar y los detalles del evento.
        2.  El manager del músico recibe una notificación y puede aceptar o rechazar la solicitud.
        3.  Si la solicitud es aceptada, el cliente es notificado y se le solicita que realice el pago.
        4.  Una vez que se completa el pago, la reserva se confirma.
    -   **Integración de Stripe Connect:** Utilizar Stripe Connect para gestionar los pagos, incluyendo:
        -   **Onboarding de Managers:** Crear un flujo para que los managers conecten sus cuentas de Stripe a la plataforma.
        -   **Procesamiento de Pagos:** Implementar la lógica para cobrar a los clientes y transferir los fondos a la cuenta del manager, descontando la comisión de la plataforma.

### 2.2. Frontend: Interfaz de Booking y Pagos

-   **Tarea:** Crear las vistas y componentes necesarios para que los usuarios interactúen con el sistema de booking.
-   **Detalles:**
    -   **Componente de Solicitud de Reserva:** Crear un componente de Livewire (`BookingRequestForm`) que permita a los clientes enviar solicitudes de reserva desde el perfil de un músico.
    -   **Página de Gestión de Reservas:** Crear una página donde los clientes y los managers puedan ver y gestionar sus reservas.
    -   **Flujo de Pago:** Implementar una página de pago donde los clientes puedan introducir sus datos de pago y completar la transacción a través de Stripe.

### 2.3. Base de Datos: Nuevos Campos y Tablas

-   **Tarea:** Extender el esquema de la base de datos para soportar el sistema de booking.
-   **Detalles:**
    -   **Migraciones:** Crear las migraciones necesarias para las tablas `bookings` y `payments`.
    -   **Actualizar Modelos:** Añadir las relaciones correspondientes en los modelos `User`, `MusicianProfile`, `Booking` y `Payment`.

## 3. Punto de Verificación del Hito

El hito se considerará completado cuando el siguiente flujo sea completamente funcional:
1.  Un cliente puede solicitar una reserva a un músico desde su perfil.
2.  Un manager puede aceptar o rechazar una solicitud de reserva.
3.  Un cliente puede pagar una reserva aceptada a través de Stripe.
4.  Tanto el cliente como el manager pueden ver el estado de sus reservas en una página de gestión.

Este hito sentará las bases para futuras funcionalidades como la gestión de calendarios, los contratos automáticos y los sistemas de reseñas.

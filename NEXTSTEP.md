# Propuesta de Próximo Hito: Hito 7 - Integración de Pagos con Stripe Connect

## 1. Contexto General

Con el sistema de solicitud y aprobación de reservas implementado, la plataforma ya permite la interacción inicial entre clientes y músicos. El siguiente paso fundamental es completar el ciclo de booking, permitiendo a los clientes pagar de forma segura por las reservas que han sido aceptadas por los managers.

Este hito se centrará en integrar **Stripe Connect** para gestionar todo el flujo de pagos. Esto no solo asegurará las transacciones, sino que también automatizará la distribución de fondos (el pago al músico y la comisión de la plataforma), lo cual es crucial para la monetización y la confianza en el marketplace.

## 2. Tareas a Desarrollar

### 2.1. Backend: Integración de Stripe Connect y Lógica de Pago

-   **Tarea:** Implementar la infraestructura de backend para conectar a los managers con Stripe y procesar los pagos de los clientes.
-   **Detalles:**
    -   **Instalación del SDK de Stripe:** Integrar la librería oficial de Stripe para PHP (`stripe/stripe-php`) usando Composer.
    -   **Onboarding de Managers con Stripe Connect:**
        -   Crear un flujo para que los managers puedan conectar su cuenta bancaria a la plataforma. Esto implicará:
            1.  Generar un enlace de onboarding de Stripe (`Account Link`).
            2.  Redirigir al manager a la página de registro/conexión de Stripe.
            3.  Crear una ruta de callback para recibir al manager de vuelta en la plataforma y guardar su `stripe_connect_id` en su `musician_profile`.
    -   **Lógica de Procesamiento de Pagos:**
        -   Crear un `PaymentController` para gestionar la creación de intentos de pago.
        -   Cuando un cliente decida pagar por una reserva (`status` = 'accepted'), el backend deberá crear un **PaymentIntent** de Stripe.
        -   Este PaymentIntent debe configurarse para transferir los fondos directamente a la cuenta del manager (`destination`), reteniendo una comisión (`application_fee_amount`) para la plataforma.
    -   **Confirmación de Pago:**
        -   Implementar un webhook para recibir notificaciones de Stripe (ej. `payment_intent.succeeded`). Al recibir esta notificación, el sistema deberá actualizar el estado de la reserva a **'confirmed'** y registrar el pago en la tabla `payments`.

### 2.2. Frontend: Flujo de Pago del Cliente

-   **Tarea:** Crear la interfaz de usuario para que el cliente pueda realizar el pago de una reserva aceptada.
-   **Detalles:**
    -   **Botón de Pago:** En la página de gestión de reservas, mostrar un botón de "Pagar Ahora" para las reservas que tengan el estado 'accepted'.
    -   **Página de Checkout:** Crear una página de pago dedicada (ej. `/bookings/{booking}/payment`) donde el cliente pueda finalizar la transacción.
    -   **Integración de Stripe Elements:** Utilizar Stripe.js y Stripe Elements en la página de checkout para crear un formulario de pago seguro. Esto asegura que los datos de la tarjeta de crédito del cliente nunca toquen los servidores de la aplicación, cumpliendo con los estándares de seguridad (PCI compliance).

### 2.3. Base de Datos

-   **Tarea:** Asegurar que el esquema de la base de datos soporte la información de los pagos.
-   **Detalles:**
    -   No se requieren nuevas migraciones, ya que las tablas `musician_profiles` (con `stripe_connect_id`) y `payments` ya están creadas. El trabajo se centrará en poblar estos campos correctamente durante el flujo de onboarding y pago.

## 3. Punto de Verificación del Hito

El hito se considerará completado cuando el siguiente flujo sea completamente funcional:
1.  Un manager puede navegar desde su dashboard a una sección para conectar su cuenta de Stripe y completar el proceso de onboarding.
2.  Un cliente ve un botón para pagar en una reserva que ha sido aceptada.
3.  Al hacer clic, el cliente es llevado a una página de pago donde puede introducir sus datos de tarjeta en un formulario seguro de Stripe Elements y completar el pago.
4.  Tras un pago exitoso, el estado de la reserva se actualiza automáticamente a **'confirmed'**.
5.  El pago se registra correctamente en la tabla `payments` de la base de datos.

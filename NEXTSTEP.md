# Próximo Objetivo: Integración de Pagos con Stripe Connect

## Prompt para el Agente de IA

"Hola. La siguiente fase crítica del proyecto es integrar un sistema de pagos robusto y escalable utilizando Stripe Connect. Esto permitirá a la plataforma manejar transacciones de manera segura, automatizar el flujo de pagos entre clientes y músicos, y cobrar una comisión por el servicio.

**Fase 1: Onboarding de Managers con Stripe Connect**

1.  **Instalación y Configuración:**
    *   Instala el SDK oficial de Stripe para PHP (`stripe/stripe-php`).
    *   Añade las claves de API de Stripe (pública, secreta) al archivo `.env` y crea una entrada de configuración en `config/services.php`.

2.  **Crear Cuentas Conectadas (Standard):**
    *   **Requerimiento:** Los managers necesitan conectar su cuenta de Stripe a la plataforma para poder recibir pagos. Utilizaremos el flujo de "Cuentas Conectadas Standard" (Standard Connected Accounts).
    *   **Implementación:**
        *   En el dashboard del manager, añade un botón "Conectar con Stripe".
        *   Este botón debe redirigir al manager al flujo de onboarding de Stripe, pasando la URL de retorno a nuestra plataforma.
        *   Crea una ruta y un método en un controlador (ej. `StripeConnectController`) para manejar el callback de Stripe.
        *   Cuando el manager complete el onboarding, Stripe lo redirigirá de vuelta. En el método de callback, guarda el `stripe_connect_id` (que empieza con `acct_...`) en la tabla `musician_profiles`.
        *   Muestra el estado de la conexión en el dashboard del manager (ej. "Conectado" o "No Conectado").

**Fase 2: Flujo de Pago del Cliente (Direct Charges)**

1.  **Iniciar el Proceso de Pago:**
    *   **Requerimiento:** Una vez que un manager confirma una solicitud de booking, el cliente debe poder pagar para finalizar la reserva.
    *   **Implementación:**
        *   En la página de detalles del booking (para el cliente), si el estado es `confirmed`, muestra un botón de "Pagar Ahora".

2.  **Integrar Stripe Checkout:**
    *   **Requerimiento:** Utilizaremos Stripe Checkout para ofrecer una experiencia de pago segura y preconstruida.
    *   **Implementación:**
        *   Al hacer clic en "Pagar Ahora", el backend debe crear una **Sesión de Checkout de Stripe**.
        *   **Configuración Clave de la Sesión:**
            *   `payment_method_types`: `['card']`
            *   `line_items`: Debe incluir el nombre del artista y el `total_price` del booking.
            *   `mode`: `'payment'`
            *   `success_url` y `cancel_url`: Rutas en nuestra aplicación para manejar el resultado.
            *   **`payment_intent_data`**: Aquí es donde se define el flujo de Connect.
                *   `application_fee_amount`: El valor de `app_fee` (en centavos) que la plataforma cobrará.
                *   `transfer_data[destination]`: El `stripe_connect_id` del músico que recibirá el pago.
        *   Redirige al cliente a la URL de la sesión de Checkout generada por Stripe.

**Fase 3: Manejo de Webhooks y Post-Pago**

1.  **Crear un Endpoint de Webhooks:**
    *   **Requerimiento:** La aplicación debe escuchar eventos de Stripe para actualizar el estado de los bookings y pagos de forma fiable.
    *   **Implementación:**
        *   Crea una nueva ruta (ej. `POST /stripe/webhooks`) y un método en el `StripeConnectController` para manejar los webhooks entrantes.
        *   Añade la ruta al array `$except` en el middleware `VerifyCsrfToken`.
        *   Implementa la verificación de la firma del webhook usando el "secreto del endpoint de webhook" de Stripe para garantizar que la solicitud es auténtica.

2.  **Manejar el Evento `checkout.session.completed`:**
    *   **Requerimiento:** Cuando un pago se completa con éxito, debemos actualizar el estado del booking y registrar el pago.
    *   **Implementación:**
        *   En el manejador de webhooks, escucha específicamente el evento `checkout.session.completed`.
        *   Recupera el objeto de la sesión del evento. Usa metadatos en la sesión de checkout para vincularla con el `booking_id` de nuestra aplicación.
        *   Actualiza el estado del `Booking` a `paid` o `confirmed` (decidir cuál es más apropiado).
        *   Crea un nuevo registro en la tabla `payments` con el ID de la transacción de Stripe, el monto y el estado.
"

# Próximo Objetivo: Integración de Pagos con Stripe Connect

## Prompt para el Agente de IA

"Hola. Tu próxima tarea es implementar el sistema de cobro del servicio, el corazón de la aplicación. Deberás integrar Stripe Connect para gestionar los flujos financieros en nuestro modelo de marketplace, donde el cliente paga directamente al afiliado y la plataforma retiene una comisión.

La arquitectura se basará en **Cuentas Conectadas Standard (Standard Connected Accounts)** y **Cargos Directos (Direct Charges)**. Esto transfiere la responsabilidad de disputas y fraudes al afiliado, mientras la plataforma monetiza a través de comisiones de aplicación (`application_fee`).

Sigue estas fases para construir la integración:

**Fase 1: Configuración Fundamental y Onboarding de Afiliados**

1.  **Instalación y Configuración:**
    *   Instala el SDK nativo de Stripe (`stripe/stripe-php`).
    *   Añade las claves de API de Stripe (pública, secreta y del webhook de Connect) al archivo de configuración `config/services.php` y al `.env`.
    *   Crea una migración para añadir la columna `stripe_account_id` (string, nullable, indexado) a la tabla `users` para almacenar el ID de la cuenta conectada del afiliado.

2.  **Implementar el Flujo de Onboarding:**
    *   Crea un `StripeConnectController` con las siguientes rutas y métodos:
        *   `POST /stripe/connect`: Para iniciar el proceso. Este método creará una Cuenta Conectada Standard en Stripe y guardará el `stripe_account_id` en la base de datos del usuario.
        *   `POST /stripe/onboarding-link`: Generará un `AccountLink` de Stripe para que el usuario complete su información.
        *   `GET /stripe/return`: La `return_url` a la que el usuario es redirigido. Este método debe verificar el estado de la cuenta (`charges_enabled`).
        *   `GET /stripe/refresh`: La `refresh_url` para manejar enlaces expirados, generando uno nuevo.

**Fase 2: Procesamiento de Pagos (Cargos Directos)**

1.  **Crear la Lógica de Checkout:**
    *   Crea un `CheckoutController` que orqueste la creación de una sesión de Stripe Checkout.
    *   La sesión debe configurarse con `mode: 'payment'`.
    *   Dentro de `payment_intent_data`, establece el `application_fee_amount` para cobrar la comisión de la plataforma.
    *   Al crear la sesión, es **crítico** pasar el `Stripe-Account` header con el `stripe_account_id` del afiliado para asegurar que es un Cargo Directo.

2.  **Validación de Seguridad:**
    *   Antes de crear la sesión de Checkout, implementa una validación para asegurar que la cuenta del afiliado (`stripe_account_id`) existe y tiene `charges_enabled` a `true`.

**Fase 3: Sincronización de Estado con Webhooks de Connect**

1.  **Crear el Controlador de Webhooks:**
    *   Crea un `StripeConnectWebhookController` para manejar los eventos provenientes de las cuentas conectadas.
    *   Configura una ruta `POST /stripe/connect-webhook` y asegúrate de añadirla a las excepciones de CSRF.
    *   Implementa la verificación de la firma del webhook de Connect usando el secreto correspondiente (`connect_webhook_secret`).

2.  **Manejar Eventos Críticos:**
    *   **`checkout.session.completed`**: Este evento es la fuente de verdad para confirmar un pago. Al recibirlo, actualiza el estado de la reserva o pedido a "pagado". Utiliza colas de Laravel para procesar esta lógica en segundo plano.
    *   **`account.updated`**: Escucha este evento para sincronizar el estado de la cuenta conectada (ej. si `charges_enabled` o `payouts_enabled` cambian) con tu base de datos local.

**Fase 4: Gestión Post-Pago (Reembolsos)**

1.  **Implementar la Lógica de Reembolsos:**
    *   Crea un endpoint de API (`POST /api/v1/bookings/{booking}/refund`) para que un usuario autorizado pueda solicitar un reembolso.
    *   Al procesar el reembolso a través de la API de Stripe, incluye el parámetro `refund_application_fee: true` para devolver la comisión de la plataforma al afiliado.
    *   Asegúrate de pasar el `Stripe-Account` header para que el reembolso se procese en la cuenta correcta.
"

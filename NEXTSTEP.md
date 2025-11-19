# Próximo Objetivo: Mejorar la Experiencia de Booking y Precios Dinámicos

## Prompt para el Agente de IA

"Hola. Tu próxima tarea es mejorar la robustez del formulario de booking y expandir el sistema de precios dinámicos antes de pasar a la integración de Stripe.

**Fase 1: Mejorar la Experiencia de Usuario (UX) del Formulario de Booking**

1.  **Corregir el Flujo de Actualización de Precios:**
    *   **Problema:** Actualmente, el precio no se recalcula si el usuario selecciona una ubicación *antes* de seleccionar una fecha.
    *   **Solución:** Modifica el componente Livewire `BookingRequestForm` para que el precio se actualice correctamente sin importar el orden en que se llenen los campos. Una posible solución es forzar al usuario a seleccionar una fecha primero, deshabilitando el selector de ubicación hasta que la fecha esté presente.
    *   **Prueba:** Añade un test de Livewire que simule el llenado del formulario en un orden "incorrecto" y verifique que el precio final es el correcto.

**Fase 2: Implementar Precios Dinámicos Avanzados**

1.  **Configuración de Antelación Mínima por Músico:**
    *   **Requerimiento:** Los managers deben poder configurar con cuántos días de antelación mínimo se puede reservar a un músico (ej. 1 día, 7 días).
    *   **Implementación:**
        *   Añade una columna `minimum_booking_notice_days` (integer, default 1) a la tabla `musician_profiles`.
        *   Actualiza el `MusicianProfileForm` para que los managers puedan editar este valor.
        *   Modifica la validación en el `BookingService` para que `createBooking` falle si la fecha del evento no cumple con la antelación mínima requerida.

2.  **Tarifa de Urgencia para Bookings de Último Minuto:**
    *   **Requerimiento:** La plataforma debe poder cobrar una tarifa extra para bookings realizados con poca antelación (ej. para el día siguiente).
    *   **Implementación:**
        *   Añade una configuración en `config/fees.php` para la "tarifa de urgencia" (ej. `urgency_fee_percentage` = 10, `urgency_threshold_days` = 1).
        *   En el `BookingService`, dentro de `calculateTotalPrice`, añade la tarifa de urgencia al `totalPrice` si la fecha del evento está dentro del umbral definido.
        *   Asegúrate de que la tarifa de urgencia se muestre en el desglose de precios.

3.  **Comisión de la Aplicación Variable (App Fee):**
    *   **Requerimiento:** Prepara el sistema para una futura integración con Stripe Connect, donde la comisión de la plataforma (`app_fee`) pueda ser variable.
    *   **Implementación:**
        *   En el `BookingService`, la lógica de `calculateTotalPrice` debe devolver también un valor `app_fee`.
        *   Por ahora, la `app_fee` puede ser un porcentaje fijo del `basePrice` y la `weekendSurcharge` (ej. 15%).
        *   A futuro, este cálculo podrá incluir factores como el nivel del músico, si es un booking de último minuto, etc.
        *   **Nota:** No es necesario mostrar la `app_fee` al cliente en el desglose de precios, pero sí debe ser parte del objeto de precio devuelto por el servicio.
"

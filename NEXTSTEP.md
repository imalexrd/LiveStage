# Próximo Objetivo: Calendario de Disponibilidad en Frontend (Manager Dashboard)

## Contexto
El backend para la gestión de disponibilidad y pagos ya está completo (Modelos, Migraciones, API y Servicios). Ahora debemos implementar la interfaz de usuario para que los Managers gestionen su calendario visualmente desde su panel de control.

## Prompt para el Agente de IA

"Hola. El backend ya soporta la gestión de disponibilidad (bloqueo de fechas) y bookings. Necesitamos integrar un calendario visual en el perfil del Manager.

**Tarea: Calendario de Disponibilidad (Frontend)**

1.  **Componente Visual:**
    *   Modifica la vista de edición de perfil (`resources/views/musician-profile.blade.php` y/o `app/Livewire/MusicianProfileForm.php`).
    *   Implementa un calendario interactivo (anual o mensual con navegación).
    *   **Tech Stack:** Preferiblemente usar componentes nativos de Tailwind/Alpine o una integración ligera compatible con Livewire. Evitar dependencias pesadas si es posible, pero FullCalendar es aceptable si se integra bien.

2.  **Funcionalidades del Calendario:**
    *   **Visualización de Estados:**
        *   **Bookings Confirmados:** Deben aparecer marcados visualmente (ej. Rojo u otro color distintivo) mostrando que la fecha está ocupada por un evento.
        *   **Fechas Bloqueadas:** Deben aparecer marcadas (ej. Gris) indicando que el manager marcó esa fecha como 'No Disponible'.
        *   **Disponibles:** Fechas sin marcar.
    *   **Gestión de Disponibilidad:**
        *   Permitir al usuario hacer clic en una fecha para cambiar su estado.
        *   **Acción de Bloquear:** Si la fecha está libre, al hacer clic debe marcarse como no disponible. Opcionalmente, mostrar un modal o prompt para ingresar la `reason` (ej. "Vacaciones").
        *   **Acción de Desbloquear:** Si la fecha está bloqueada manualmente, al hacer clic debe liberarse.
        *   **Restricción:** No se deben poder modificar las fechas que tienen Bookings Confirmados desde este calendario (solo lectura para bookings).

3.  **Integración:**
    *   Conectar el calendario con la lógica de backend existente (Modelo `Availability`, Relación `bookings` en `MusicianProfile`).
    *   Asegurar que la interfaz sea responsive y mantenga la estética moderna del sitio."

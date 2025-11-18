# Próximo Hito: Corregir y Finalizar la Selección de Ubicación en el Perfil del Músico

## 1. Contexto General

Aunque la funcionalidad de selección de ubicación con mapa se ha implementado en todo el sitio, existe un bug crítico en la página de **edición del perfil del músico** (`/musician-profile`). Actualmente, cuando un manager selecciona una ubicación en el mapa, la dirección y las coordenadas no se guardan correctamente en su perfil.

Además, el formulario actual pide `Ciudad` y `Estado` por separado, lo cual es redundante y propenso a errores, ya que esta información puede ser extraída directamente de la selección del mapa.

El objetivo de este hito es solucionar este bug, refactorizar el formulario para que la selección en el mapa sea la única fuente de verdad para la ubicación, y asegurar que los datos se guarden y se muestren correctamente.

## 2. Requisitos Previos: Configuración de Google Maps

Antes de comenzar, es **crucial** que el entorno de Google Maps esté configurado correctamente. La falta de configuración causará errores en el frontend.

**Acción:** Lee y sigue las instrucciones del nuevo archivo de documentación: `GOOGLE_MAPS_INTEGRATION.md`. Asegúrate de que tu archivo `.env` local contenga un `GOOGLE_MAPS_API_KEY` y un `GOOGLE_MAPS_MAP_ID` válidos.

## 3. Tareas a Desarrollar

### 3.1. Refactorizar el Componente `MusicianProfileForm`

-   **Tarea 1: Simplificar los Campos del Formulario.**
    -   **Detalles:** En el componente `MusicianProfileForm` (`app/Livewire/MusicianProfileForm.php`) y su vista (`resources/views/livewire/musician-profile-form.blade.php`), elimina los campos de texto para `location_city` y `location_state`. La selección en el mapa será ahora la única forma de establecer la ubicación.
    -   **Acción:** Modifica la vista para que, en lugar de los campos eliminados, se muestre la dirección completa obtenida del mapa (`location_address`).

-   **Tarea 2: Implementar la Lógica de Guardado Correcta.**
    -   **Detalles:** Actualmente, el método `locationSelected` en `MusicianProfileForm` actualiza las propiedades `latitude` y `longitude`, pero la dirección (`location_address`) y la ciudad/estado no se están guardando.
    -   **Acción 1:** Modifica el método `locationSelected` para que también acepte y guarde la dirección.
    -   **Acción 2:** En el método `save`, asegúrate de que los nuevos campos (`location_address`, `latitude`, `longitude`) se validen y se persistan correctamente en la base de datos. Extrae la ciudad y el estado de la dirección completa si es necesario para mantener la estructura de la base de datos.

### 3.2. Asegurar la Carga de Datos Existentes

-   **Tarea 1: Mostrar la Ubicación Guardada.**
    -   **Detalles:** Al cargar la página de edición del perfil, si el músico ya tiene una ubicación guardada, el botón "Set Base Location on Map" debería mostrar la dirección actual en lugar del texto predeterminado.
    -   **Acción:** Modifica el método `mount` en `MusicianProfileForm` para cargar `location_address` y asegúrate de que se muestra correctamente en el botón de la vista.

## 4. Punto de Verificación del Hito

1.  La página de edición del perfil del músico ya no tiene campos de texto para `Ciudad` y `Estado`.
2.  Al hacer clic en "Set Base Location on Map", el modal del mapa se abre correctamente.
3.  Después de seleccionar una ubicación en el mapa y hacer clic en "Select Location", la dirección completa se muestra en la página de perfil.
4.  Al guardar el perfil, la `location_address`, `latitude` y `longitude` se almacenan correctamente en la base de datos.
5.  Al recargar la página, la dirección previamente guardada se muestra en el botón, confirmando que los datos se están cargando correctamente.

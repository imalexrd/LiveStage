# Propuesta de Próximo Hito: Hito 10 - Búsqueda Avanzada por Ubicación y Distancia

## 1. Contexto General

Para mejorar la experiencia de búsqueda y facilitar a los clientes la contratación de músicos locales, se implementará una funcionalidad de búsqueda basada en la ubicación geográfica. Los usuarios podrán buscar músicos introduciendo una dirección o utilizando su ubicación actual, y los resultados mostrarán la distancia a cada músico. Además, se añadirán filtros para acotar los resultados por distancia.

## 2. Tareas a Desarrollar

### 2.1. Actualizaciones del Backend

-   **Tarea:** Modificar el componente `MusicianSearch` de Livewire para soportar búsquedas por coordenadas GPS.
-   **Detalles:**
    -   Añadir propiedades para `latitude`, `longitude` y `distance` (radio de búsqueda).
    -   Implementar la lógica para calcular la distancia entre la ubicación del usuario y la de cada músico. Se puede utilizar una consulta SQL nativa (Haversine) o una librería de geolocalización si el motor de base de datos lo soporta.
    -   Modificar la consulta principal para filtrar músicos dentro del radio de distancia especificado y ordenar los resultados por proximidad.
    -   La `lat` y `lng` del músico ya están en la tabla `musician_profiles`.

### 2.2. Mejoras en el Frontend

-   **Tarea:** Actualizar la vista `musician-search.blade.php` para integrar la búsqueda por ubicación.
-   **Detalles:**
    -   **Campo de Autocompletado de Google Places:** Reemplazar el campo de texto de ubicación actual por un campo de autocompletado de Google Places. Al seleccionar una dirección, se deberán enviar la latitud y longitud al backend.
    -   **Botón "Usar mi ubicación actual":** Añadir un botón que, al ser pulsado, utilice la API de geolocalización del navegador para obtener la ubicación actual del usuario y realizar la búsqueda.
    -   **Filtro de Distancia:** Añadir un `slider` o un campo de selección para que los usuarios puedan ajustar el radio de búsqueda (en millas o kilómetros).
    -   **Visualización de Distancia:** En la tarjeta de cada músico en los resultados, mostrar la distancia desde la ubicación de búsqueda (ej. "a 5 millas").

### 2.3. Punto de Verificación del Hito

El hito se considerará completado cuando se cumplan los siguientes puntos:
1.  Los usuarios pueden introducir una dirección en un campo de autocompletado para buscar músicos.
2.  Los usuarios pueden utilizar su ubicación actual para encontrar músicos cercanos.
3.  Los resultados de la búsqueda muestran la distancia a cada músico.
4.  Los usuarios pueden filtrar los resultados por un radio de distancia.
5.  El sistema calcula correctamente la distancia y ordena los músicos por proximidad.

## Prompt para la siguiente iteración:

"Ahora, modifica la búsqueda de músicos para que funcione con geolocalización.

1.  **Componente Livewire (`MusicianSearch.php`):**
    *   Añade propiedades públicas para `$latitude`, `$longitude` y `$distance`.
    *   Modifica la consulta para que, si se proporcionan `$latitude` y `$longitude`, calcule la distancia de cada músico usando la fórmula Haversine y filtre los resultados según el valor de `$distance`.
    *   Ordena los músicos por distancia.
    *   Añade la distancia a cada músico en la colección de resultados.

2.  **Vista (`musician-search.blade.php`):**
    *   Reemplaza el input de ubicación por un componente de autocompletado de Google Places que actualice las propiedades `$latitude` y `$longitude` del componente Livewire.
    *   Añade un `slider` para controlar la propiedad `$distance`.
    *   Muestra la distancia en la tarjeta de cada músico.
    *   Añade un botón para "Usar mi ubicación actual" que obtenga las coordenadas del navegador."
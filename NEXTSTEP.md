# Propuesta de Próximo Hito: Hito 5 - Búsqueda y Descubrimiento de Músicos

## 1. Contexto General

Con los perfiles de músicos públicos y enriquecidos con contenido multimedia, la plataforma ya ofrece una buena presentación de los artistas. El siguiente paso crucial es permitir que los clientes encuentren a estos músicos de manera eficiente. Actualmente, no existe una forma de buscar o filtrar artistas, lo que limita la utilidad de la plataforma a medida que crece el número de perfiles.

Este hito se centra en la creación de una funcionalidad de búsqueda y descubrimiento que permita a los clientes encontrar músicos basándose en criterios específicos como el género musical, la ubicación, el tipo de evento y el rango de precios.

## 2. Tareas a Desarrollar

### 2.1. Backend: Lógica de Búsqueda y Filtrado

-   **Tarea:** Implementar una API o un endpoint que acepte parámetros de búsqueda y devuelva una lista de perfiles de músicos que coincidan.
-   **Detalles:**
    -   **Modelo y Controlador:** Crear un nuevo controlador, por ejemplo `MusicianSearchController`, que se encargue de procesar las peticiones de búsqueda.
    -   **Lógica de Filtrado:** Utilizar Eloquent para construir consultas a la base de datos que filtren los `MusicianProfile` por:
        -   **Género Musical:** (requerirá añadir una columna o tabla de géneros).
        -   **Ubicación:** (ciudad/estado).
        -   **Tipo de Evento:** (requerirá añadir una columna o tabla para especialidades, ej. "bodas", "conciertos", "eventos corporativos").
        -   **Rango de Precios:** (filtrar por `base_price_per_hour`).
    -   **Optimización:** Asegurarse de que las consultas estén optimizadas con índices en la base de datos para garantizar un rendimiento rápido.

### 2.2. Frontend: Interfaz de Búsqueda

-   **Tarea:** Crear una nueva página de "Búsqueda" o "Descubrimiento" donde los clientes puedan interactuar con los filtros.
-   **Detalles:**
    -   **Componente de Livewire:** Desarrollar un componente de Livewire (`MusicianSearch`) que contenga:
        -   Un campo de búsqueda de texto libre (para buscar por nombre de artista o palabras clave en la bio).
        -   Filtros desplegables o checkboxes para género, ubicación y tipo de evento.
        -   Un slider o campos de entrada para el rango de precios.
    -   **Resultados Dinámicos:** La lista de resultados se actualizará dinámicamente a medida que el usuario aplique los filtros, utilizando las capacidades reactivas de Livewire.
    -   **Diseño de la Página:** Diseñar una vista clara y atractiva que muestre los resultados de la búsqueda, reutilizando posiblemente el diseño de las tarjetas de músico existentes.

### 2.3. Base de Datos: Nuevos Campos

-   **Tarea:** Extender el esquema de la base de datos para soportar los nuevos criterios de búsqueda.
-   **Detalles:**
    -   **Migraciones:** Crear las migraciones necesarias para añadir:
        -   Una tabla `genres` y una tabla pivote `genre_musician_profile` para permitir que cada músico tenga múltiples géneros.
        -   Una tabla `event_types` y una tabla pivote `event_type_musician_profile` para las especialidades de eventos.
    -   **Actualizar Modelos:** Añadir las relaciones correspondientes (`belongsToMany`) en los modelos `MusicianProfile`, `Genre` y `EventType`.

## 3. Punto de Verificación del Hito

El hito se considerará completado cuando el siguiente flujo sea completamente funcional:
1.  Un cliente (o cualquier visitante) navega a la nueva página de "Búsqueda".
2.  El usuario puede introducir un término de búsqueda y aplicar filtros por género, ubicación y precio.
3.  La lista de músicos se actualiza en tiempo real para mostrar solo los perfiles que coinciden con los criterios.
4.  Al hacer clic en un músico de los resultados, el usuario es redirigido a su perfil público.
5.  Los *managers* pueden seleccionar los géneros y tipos de evento de su perfil desde la página de gestión de su perfil.

Este hito sentará las bases para futuras funcionalidades como sistemas de recomendación y una página de inicio más dinámica.

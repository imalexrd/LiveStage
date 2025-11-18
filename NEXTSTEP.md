# Próximo Hito: Búsqueda por Mapa Interactivo y Precios Dinámicos por Distancia

## 1. Contexto General

Para mejorar radicalmente la experiencia de usuario y la precisión en la contratación, abandonaremos la búsqueda de ubicación por texto y la reemplazaremos por una selección interactiva en un mapa. Esto permitirá a los clientes elegir una ubicación de evento con exactitud.

Adicionalmente, se introducirá un sistema de precios dinámico basado en la distancia de viaje. Los managers podrán configurar un radio de viaje incluido, una distancia máxima de viaje y una tarifa por milla extra. Esto automatizará el cálculo de los viáticos, ofreciendo transparencia tanto al cliente como al músico.

## 2. Tareas a Desarrollar

### 2.1. Actualizaciones del Backend

-   **Tarea 1: Ampliar el Perfil del Músico.**
    -   **Detalles:** Crear una nueva migración para añadir las siguientes columnas a la tabla `musician_profiles`:
        -   `travel_radius_miles` (DECIMAL, default 0): El radio en millas que el músico viaja sin coste adicional.
        -   `max_travel_distance_miles` (DECIMAL, nullable): La distancia máxima que el músico está dispuesto a viajar.
        -   `price_per_extra_mile` (DECIMAL, default 0): La tarifa a cobrar por cada milla recorrida fuera del `travel_radius_miles`.
    -   **Acción:** Actualizar el modelo `MusicianProfile` para incluir estos campos en la propiedad `$fillable`.

-   **Tarea 2: Implementar la Lógica de Cálculo de Tarifa por Distancia.**
    -   **Detalles:** Modificar el componente o la lógica de negocio encargada de crear un booking. Al calcular el `total_price`, se debe:
        1.  Obtener las coordenadas del músico y las coordenadas del evento.
        2.  Calcular la distancia en millas (usando la fórmula Haversine).
        3.  Si la distancia es mayor que `travel_radius_miles`, calcular las millas extra.
        4.  Verificar que la distancia no exceda `max_travel_distance_miles`.
        5.  Calcular la tarifa de viaje (`millas_extra * price_per_extra_mile`).
        6.  Añadir esta tarifa al precio total del booking.

### 2.2. Mejoras en el Perfil del Manager (Frontend)

-   **Tarea 1: Añadir Controles de Configuración de Viaje.**
    -   **Detalles:** En la página de edición del perfil del músico, añadir los campos de formulario necesarios para que el manager pueda configurar:
        -   Radio de viaje incluido (en millas).
        -   Distancia máxima de viaje (en millas).
        -   Precio por milla extra.

### 2.3. Mejoras en la Interfaz de Cliente (Frontend)

-   **Tarea 1: Crear un Componente de Mapa Interactivo (`LocationPickerModal`).**
    -   **Detalles:** Desarrollar un componente modal reutilizable (Livewire/Alpine) que contenga un mapa de Google Maps.
        -   Al abrirse, debe solicitar permisos de geolocalización del navegador para centrar el mapa en la ubicación del usuario.
        -   Debe incluir un campo de búsqueda (Google Places Autocomplete) para encontrar y navegar a direcciones específicas en el mapa.
        -   El usuario debe poder seleccionar una ubicación (ej. soltando un pin).
        -   El componente debe emitir la ubicación seleccionada (dirección, latitud, longitud) al componente padre.

-   **Tarea 2: Integrar el Mapa en la Búsqueda de Músicos.**
    -   **Detalles:** Reemplazar el campo de texto de ubicación actual en la página de búsqueda por un botón "Seleccionar Ubicación en el Mapa".
    -   Este botón abrirá el modal `LocationPickerModal`.
    -   La ubicación seleccionada en el mapa se usará como filtro opcional para la búsqueda, actualizando los resultados en tiempo real.

-   **Tarea 3: Integrar el Mapa en el Formulario de Booking.**
    -   **Detalles:** En el proceso de booking, la selección de la ubicación del evento será **obligatoria**.
    -   Utilizar el `LocationPickerModal` para que el cliente establezca la ubicación del evento.
    -   Mostrar de forma clara y desglosada la tarifa de viaje calculada (si aplica) antes de que el cliente confirme la reserva.

## 3. Punto de Verificación del Hito

1.  Un manager puede configurar sus preferencias de viaje y tarifas por distancia en su perfil.
2.  Un cliente puede seleccionar la ubicación de un evento en un mapa interactivo tanto en la página de búsqueda como en el formulario de booking.
3.  La búsqueda de músicos se puede filtrar opcionalmente por la ubicación seleccionada en el mapa.
4.  El precio total de un booking incluye automáticamente una tarifa de viaje si la distancia del evento excede el radio configurado por el manager.
5.  El sistema impide o advierte si un booking excede la distancia máxima de viaje del músico.

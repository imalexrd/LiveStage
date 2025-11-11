# Hito 3: Dashboard de Músicos y Perfiles Públicos

## 1. Contexto General

Con la gestión de perfiles de músicos y la aprobación de administradores ya implementada, el siguiente paso es hacer que los perfiles aprobados sean visibles para los clientes. Este hito se centra en mejorar el dashboard principal para que muestre una lista de los músicos disponibles y en crear una página de perfil público para cada músico.

## 2. Tareas a Desarrollar

### Dashboard Principal
- **Tarea:** Modificar el dashboard principal para que muestre una lista de todos los perfiles de músicos que han sido aprobados.
- **Detalles:**
    - Crear un componente de Livewire para mostrar la lista de perfiles de músicos.
    - Cada elemento de la lista debe mostrar, como mínimo, el nombre del artista, la ciudad y el estado.
    - Asegurar que solo los perfiles con `is_approved = true` se muestren en el dashboard.

### Página de Perfil Público
- **Tarea:** Crear una página de perfil público para cada músico.
- **Detalles:**
    - Crear una nueva ruta y una vista para mostrar los detalles de un perfil de músico específico.
    - La página del perfil debe mostrar toda la información relevante del músico, incluyendo `artist_name`, `bio`, `location_city`, `location_state`, y `base_price_per_hour`.
    - Añadir un enlace en cada elemento de la lista del dashboard que lleve a la página de perfil público del músico correspondiente.

## 3. Punto de Verificación del Hito

La finalización de este hito se marca cuando el siguiente flujo sea completamente funcional:
1. Un `manager` crea su perfil de músico y un `admin` lo aprueba.
2. El perfil del músico aprobado aparece en la lista del dashboard principal.
3. Un `client` (o cualquier usuario) puede hacer clic en el perfil del músico en el dashboard y ver la página de perfil público con todos los detalles del músico.

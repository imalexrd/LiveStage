# Próximo Objetivo: Implementar Controladores de API

## Prompt para el Agente de IA

"Hola. Tu tarea es continuar la refactorización de esta aplicación Laravel para exponer la lógica de negocio a través de una API RESTful. La Fase 2 (Refactorización de Perfiles de Músico a la Capa de Servicios) ya ha sido completada. Ahora debes enfocarte en la Fase 3.

**Fase 3: Introducir Controladores de API**

1.  Crea un nuevo controlador de API: `app/Http/Controllers/Api/V1/MusicianProfileController.php`.
2.  En `routes/api.php`, define una ruta de tipo `GET` para `/api/v1/musicians` que apunte al método `index` del nuevo controlador.
3.  Implementa el método `index` en `MusicianProfileController`. Este método debe:
    *   Inyectar el `MusicianProfileService`.
    *   Usar el método `search()` del servicio (ya creado en la Fase 2) para obtener los perfiles. Puedes pasarle los parámetros de la petición (`$request->all()`) como filtros.
    *   Devolver los resultados como una respuesta JSON. Puedes usar `Resources` de Laravel para formatear la salida si lo consideras necesario.
4.  Verifica que puedes obtener una lista de músicos en formato JSON accediendo a la ruta `/api/v1/musicians` a través de un cliente API o un test.

**Fase 4 (Opcional Avanzado): Implementar DTOs para la API**

1.  Crea un DTO para los filtros de búsqueda de la API: `app/Data/MusicianSearchFilterData.php`. Este DTO debe definir las propiedades que se pueden usar para filtrar (ej: `search`, `latitude`, `longitude`, `minPrice`, etc.).
2.  Modifica el método `index` en `MusicianProfileController` para que acepte el DTO en lugar de un array: `index(MusicianSearchFilterData $filters)`.
3.  Actualiza el método `search` en `MusicianProfileService` para que también acepte el `MusicianSearchFilterData` DTO.
4.  Verifica que la búsqueda a través de la API sigue funcionando correctamente. Esto estandariza la validación y el flujo de datos desde la petición hasta el servicio."

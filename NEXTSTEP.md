# Próximo Objetivo: Paridad de Funcionalidades - API y Web

## Prompt para el Agente de IA

"Hola. Tu tarea es continuar la refactorización de esta aplicación Laravel para que la API RESTful alcance la paridad de funcionalidades con la interfaz web de Livewire. La Fase 3 (Endpoint de Búsqueda) ya ha sido completada. Ahora debes enfocarte en la Fase 4, que consiste en implementar la gestión completa de perfiles de músicos a través de la API.

**Fase 4: Implementar CRUD de Perfiles de Músico en la API**

El objetivo es replicar toda la funcionalidad que actualmente existe en el formulario de perfil de músico de Livewire, pero a través de endpoints de API.

1.  **Obtener un Perfil Específico (Read):**
    *   Crea un método `show` en `MusicianProfileController`.
    *   Define una ruta `GET /api/v1/musicians/{profile}` en `routes/api.php`.
    *   El método debe recibir un `MusicianProfile`, inyectado a través de *route model binding*.
    *   Devuelve los datos del perfil utilizando `MusicianProfileResource`.
    *   **Prueba:** Escribe un test que verifique que al hacer un GET a `/api/v1/musicians/{id}` se obtiene el perfil correcto con la estructura JSON esperada.

2.  **Crear un Nuevo Perfil (Create):**
    *   Crea un método `store` en `MusicianProfileController`.
    *   Define una ruta `POST /api/v1/musicians` protegida con `auth:sanctum`. Solo los usuarios con rol `manager` pueden crear perfiles.
    *   El método debe aceptar un `MusicianProfileData` DTO para la validación.
    *   Utiliza el `MusicianProfileService` para crear el perfil asociado al usuario autenticado.
    *   Devuelve el perfil recién creado con un código de estado `201` (Created).
    *   **Prueba:** Escribe un test que simule una petición POST por un usuario `manager` autenticado, enviando datos válidos, y verifique que el perfil se crea en la base de datos y se devuelve la respuesta correcta.

3.  **Actualizar un Perfil Existente (Update):**
    *   Crea un método `update` en `MusicianProfileController`.
    *   Define una ruta `PUT /api/v1/musicians/{profile}` protegida con `auth:sanctum`.
    *   Implementa una `Policy` para asegurar que solo el `manager` propietario del perfil pueda actualizarlo.
    *   El método debe aceptar un `MusicianProfileData` DTO.
    *   Utiliza el `MusicianProfileService` para actualizar el perfil.
    *   Devuelve el perfil actualizado.
    *   **Prueba:** Escribe un test que simule una petición PUT por el `manager` propietario, verifique que los datos se actualizan y que un usuario no autorizado recibe un error `403` (Forbidden).

4.  **Eliminar un Perfil (Delete):**
    *   Crea un método `destroy` en `MusicianProfileController`.
    *   Define una ruta `DELETE /api/v1/musicians/{profile}` protegida con `auth:sanctum`.
    *   Utiliza la misma `Policy` del paso anterior para la autorización.
    *   Elimina el perfil de la base de datos.
    *   Devuelve una respuesta vacía con un código de estado `204` (No Content).
    *   **Prueba:** Escribe un test que verifique que el propietario puede eliminar su perfil y que la entrada desaparece de la base de datos.

**Guía para Pruebas de API:**

-   **Autenticación:** Para probar los endpoints protegidos, primero debes autenticar a un usuario en tu test y luego usar el método `actingAs($user, 'sanctum')` para realizar las peticiones.
-   **Headers:** Asegúrate de incluir el header `Accept: application/json` en tus peticiones de prueba para recibir respuestas en formato JSON.
-   **Validación:** Escribe tests que verifiquen los fallos de validación. Por ejemplo, envía una petición `POST` sin un campo requerido y asegúrate de recibir un código de estado `422` (Unprocessable Entity) con los errores correspondientes.
"

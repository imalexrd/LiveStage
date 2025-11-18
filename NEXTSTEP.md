# Plan de Refactorización: Centralización de la Lógica de Negocio

## Objetivo Principal

El objetivo de esta refactorización es extraer la lógica de negocio actualmente dispersa en los componentes de Livewire y los controladores de Laravel, y centralizarla en una capa de servicios dedicada. Esto desacoplará la lógica de la implementación de la interfaz de usuario (UI), lo que permitirá:

1.  **Reutilización de Código:** La misma lógica de negocio podrá ser utilizada por controladores web, componentes de Livewire y nuevos controladores de API.
2.  **Desarrollo en Paralelo:** Facilitará la creación de una API para una futura aplicación móvil sin duplicar código.
3.  **Mantenibilidad:** Hará que el código sea más fácil de entender, probar y mantener a largo plazo.
4.  **Testing Simplificado:** Permitirá escribir pruebas unitarias para la lógica de negocio de forma aislada.

## Arquitectura Propuesta

Implementaremos un patrón de **Capa de Servicios** (Service Layer).

1.  **Capa de Servicios (`app/Services`):**
    *   Contendrá toda la lógica de negocio principal (crear reservas, buscar músicos, gestionar perfiles, etc.).
    *   Cada servicio será una clase PHP simple (POPO - Plain Old PHP Object) que puede ser inyectada donde se necesite.
    *   Ejemplos: `BookingService`, `MusicianProfileService`.

2.  **Controladores (`app/Http/Controllers`):**
    *   Se convertirán en "controladores delgados" (thin controllers).
    *   Su única responsabilidad será:
        1.  Recibir y validar las peticiones HTTP.
        2.  Llamar a los métodos correspondientes en la capa de servicios.
        3.  Devolver una respuesta (una vista, una redirección o JSON para la API).

3.  **Componentes de Livewire (`app/Livewire`):**
    *   También se volverán "delgados".
    *   Su responsabilidad se centrará en gestionar el estado de la UI y la interacción del usuario.
    *   Llamarán a la capa de servicios para ejecutar acciones de negocio, en lugar de contener la lógica directamente.

4.  **(Opcional pero recomendado) Data Transfer Objects (DTOs):**
    *   Para estandarizar los datos que fluyen entre las capas (peticiones -> servicios -> modelos), se recomienda usar DTOs.
    *   Estos son objetos simples que definen la estructura de los datos. El paquete `spatie/laravel-data` es una excelente opción para esto.

---

## Prompt para el Agente de IA: Instrucciones de Ejecución

"Hola. Tu tarea es continuar la refactorización de esta aplicación Laravel para centralizar la lógica de negocio en una capa de servicios. La Fase 1 ya ha sido completada. Ahora debes enfocarte en la Fase 2.

**Fase 1: Crear la Capa de Servicios y Refactorizar los Bookings (¡Completada!)**

-   [x] Se ha creado el directorio `app/Services`.
-   [x] Se ha creado `app/Services/BookingService.php`.
-   [x] La lógica de creación de reservas ha sido movida desde `BookingRequestForm` al `BookingService`.
-   [x] El componente `BookingRequestForm` ha sido refactorizado para usar el servicio.
-   [x] Se han añadido tests para verificar la nueva implementación y el correcto funcionamiento del formulario.

**Contexto Adicional de la Fase 1:** Para completar esta fase, fue necesario configurar completamente el entorno de pruebas desde cero. Esto incluyó instalar dependencias del sistema (como `composer` y extensiones de PHP), configurar una base de datos de prueba (`laravel_test`), crear un archivo `.env.testing`, y solucionar varios errores de base de datos relacionados con el esquema y el mass assignment. Se han añadido tests unitarios y de feature para asegurar la calidad. El archivo `INIT.md` contiene las instrucciones detalladas para replicar el entorno.

---

**Fase 2: Refactorizar los Perfiles de Músico (Próximo Objetivo)**

1.  Crea un nuevo servicio: `app/Services/MusicianProfileService.php`.
2.  Analiza los componentes `app/Livewire/MusicianProfileForm.php` y `app/Livewire/MusicianSearch.php`.
3.  Mueve la lógica para crear/actualizar un perfil de músico desde `MusicianProfileForm` al `MusicianProfileService`. Crea un método como `updateProfile(User $user, array $data): MusicianProfile`.
4.  Mueve la lógica de búsqueda y filtrado de músicos desde `MusicianSearch` al `MusicianProfileService`. Crea un método como `search(array $filters)`.
5.  Refactoriza ambos componentes de Livewire para que utilicen el `MusicianProfileService`.
6.  Asegúrate de que la edición de perfiles y la búsqueda de músicos sigan funcionando como antes. Añade tests si es necesario.

**Fase 3: Introducir Controladores de API**

1.  Crea un nuevo controlador de API: `app/Http/Controllers/Api/V1/MusicianProfileController.php`.
2.  En `routes/api.php`, define una ruta de tipo `GET` para `/api/v1/musicians` que apunte al método `index` del nuevo controlador.
3.  Implementa el método `index` en `MusicianProfileController`. Este método debe:
    *   Inyectar el `MusicianProfileService`.
    *   Usar el método `search()` del servicio (creado en la Fase 2) para obtener los perfiles.
    *   Devolver los resultados como una respuesta JSON. Puedes usar `Resources` de Laravel para formatear la salida.
4.  Verifica que puedes obtener una lista de músicos en formato JSON accediendo a `http://<tu-dominio>/api/v1/musicians`.

**Fase 4 (Opcional Avanzado): Implementar DTOs con `spatie/laravel-data`**

1.  Si el paquete no está instalado, ejecútalo: `composer require spatie/laravel-data`.
2.  Crea un DTO para los datos de actualización del perfil del músico: `app/Data/MusicianProfileData.php`. Este DTO debe definir las propiedades que se pueden actualizar (ej: `nickname`, `bio`, `city`, etc.).
3.  Modifica el método `updateProfile` en `MusicianProfileService` para que acepte el DTO en lugar de un array: `updateProfile(User $user, MusicianProfileData $data): MusicianProfile`.
4.  Actualiza el componente `MusicianProfileForm` para que cree una instancia de `MusicianProfileData` a partir de los datos del formulario y la pase al servicio.
5.  Verifica que la actualización del perfil sigue funcionando correctamente. Esto estandariza el flujo de datos y lo hace más robusto."

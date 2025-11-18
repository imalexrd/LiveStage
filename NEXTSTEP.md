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

"Hola. Tu tarea es refactorizar esta aplicación Laravel para centralizar la lógica de negocio en una capa de servicios. Sigue el plan detallado en este documento (`REFACTOR.md`) y ejecuta las fases en orden. No avances a la siguiente fase hasta completar la anterior y asegurarte de que la funcionalidad existente no se ha roto.

**Fase 1: Crear la Capa de Servicios y Refactorizar los Bookings**

1.  Crea un nuevo directorio: `app/Services`.
2.  Dentro de `app/Services`, crea un nuevo archivo llamado `BookingService.php`.
3.  Analiza el componente `app/Livewire/BookingRequestForm.php`. Identifica toda la lógica relacionada con la creación y validación de una reserva (`Booking`).
4.  Mueve esa lógica al `BookingService`. Crea un método público como `createBooking(User $client, MusicianProfile $profile, array $data): Booking`. Este método debe encargarse de crear y guardar la reserva en la base de datos.
5.  Refactoriza el componente `app/Livewire/BookingRequestForm.php` para que inyecte y utilice `BookingService` para crear la reserva. El componente solo debe encargarse de recoger los datos del formulario y llamar al servicio.
6.  Verifica que el formulario de solicitud de reserva sigue funcionando correctamente después de los cambios.

**Fase 2: Refactorizar los Perfiles de Músico**

1.  Crea un nuevo servicio: `app/Services/MusicianProfileService.php`.
2.  Analiza los componentes `app/Livewire/MusicianProfileForm.php` y `app/Livewire/MusicianSearch.php`.
3.  Mueve la lógica para crear/actualizar un perfil de músico desde `MusicianProfileForm` al `MusicianProfileService`. Crea un método como `updateProfile(User $user, array $data): MusicianProfile`.
4.  Mueve la lógica de búsqueda y filtrado de músicos desde `MusicianSearch` al `MusicianProfileService`. Crea un método como `search(array $filters)`.
5.  Refactoriza ambos componentes de Livewire para que utilicen el `MusicianProfileService`.
6.  Asegúrate de que la edición de perfiles y la búsqueda de músicos sigan funcionando como antes.

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
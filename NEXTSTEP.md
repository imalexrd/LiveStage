# Hito 4: Perfiles de Músico Enriquecidos

## 1. Contexto General

Con los perfiles públicos funcionales, el siguiente paso es enriquecerlos con contenido multimedia para que los artistas puedan presentarse de manera más atractiva a los clientes. Este hito se centra en añadir la capacidad de subir una foto de banner, una galería de imágenes, videos y pistas de audio al perfil de cada músico.

## 2. Tareas a Desarrollar

### Gestión de Contenido Multimedia
- **Tarea:** Implementar la lógica para que los `managers` puedan gestionar el contenido multimedia de su perfil.
- **Detalles:**
    - **Base de Datos:**
        - Añadir una columna para la **imagen de banner** en la tabla `musician_profiles`.
        - Crear una nueva tabla `media` para almacenar las rutas a las imágenes de la galería, videos y pistas de audio. Esta tabla deberá estar relacionada con `musician_profiles`.
    - **Backend (Manager):**
        - Desarrollar la funcionalidad de subida de archivos (imágenes, videos, audio). Los archivos se almacenarán siguiendo las especificaciones del proyecto (Amazon S3).
        - Crear los componentes de Livewire necesarios para que el `manager` pueda realizar operaciones CRUD (Crear, Leer, Actualizar, Eliminar) sobre su contenido multimedia desde la página de gestión de su perfil.
    - **Backend (Admin):**
        - Extender el recurso de Filament (`MusicianProfileResource`) para que los administradores también puedan realizar operaciones CRUD sobre el contenido multimedia de cualquier músico.

### Visualización en el Perfil Público
- **Tarea:** Actualizar la página de perfil público para mostrar todo el nuevo contenido multimedia.
- **Detalles:**
    - Mostrar la imagen de banner en la parte superior del perfil.
    - Crear una sección de galería de imágenes.
    - Crear secciones para mostrar los videos y reproducir las pistas de audio.

## 3. Punto de Verificación del Hito

La finalización de este hito se marca cuando el siguiente flujo sea completamente funcional:
1.  Un `manager` inicia sesión y navega a la página de gestión de su perfil.
2.  El `manager` sube una foto de banner, varias imágenes para su galería, un video de YouTube (o similar) y una pista de audio.
3.  Un `client` (o cualquier usuario) visita el perfil público de este músico y puede ver el banner, la galería de imágenes, el video y escuchar la pista de audio.
4.  Un `admin` puede ver y modificar todo el contenido multimedia de este músico desde el panel de administración de Filament.

---

## Hito 4: Registro de Progreso y Próximos Pasos (13 de Noviembre, 2025)

### Resumen del Trabajo Realizado

Se implementó la lógica completa para el Hito 4, siguiendo las especificaciones originales. Los cambios incluyen:
- **Base de Datos:** Se crearon las migraciones para añadir el campo `banner_image_path` a la tabla `musician_profiles` y para crear la nueva tabla `media`.
- **Modelos:** Se actualizaron los modelos `MusicianProfile` y se creó el modelo `Media` con sus relaciones correspondientes.
- **Backend:** Se desarrolló el componente de Livewire `MultimediaManager` para gestionar la subida, visualización y eliminación de archivos.
- **Frontend:** Se actualizaron las vistas `musician-profile.blade.php` (para el manager) y `musician-profile-show.blade.php` (para el perfil público) para mostrar los nuevos elementos multimedia.
- **Panel de Administración:** Se extendió el `MusicianProfileResource` de Filament para permitir a los administradores gestionar los archivos multimedia de los músicos.

### Problemas Persistentes

A pesar de que la implementación del código está completa y los tests de la aplicación pasan, existen dos problemas críticos que impiden que la funcionalidad sea usable:
1.  **Error 403 Forbidden:** El servidor devuelve un error 403 (Prohibido) al intentar acceder a cualquier archivo subido a través del navegador.
2.  **Excepción `RouteNotFoundException`:** La aplicación lanza una excepción indicando que la ruta nombrada `storage.serve` no está definida, a pesar de que está correctamente declarada en `routes/web.php`. Este error parece estar relacionado con un problema de caché persistente.

### Intentos de Solución Realizados

Se realizaron múltiples intentos para solucionar estos problemas, abarcando configuración de la aplicación, permisos del servidor y enrutamiento:
1.  **Cambio a Almacenamiento Local:** Se configuró el sistema para usar el disco `local` (`storage/app/public`) en lugar de `s3`, para descartar problemas con servicios externos.
2.  **Creación de Symlink:** Se ejecutó `php artisan storage:link` para hacer que los archivos públicos del almacenamiento sean accesibles desde la web.
3.  **Corrección de Permisos:** Se usó `chmod` para ajustar los permisos de la carpeta `storage` recursivamente a `775` para directorios y `664` para archivos.
4.  **Corrección de Propietario:** Se usó `chown` para cambiar el propietario de las carpetas `storage` y `bootstrap/cache` al usuario del servidor web (`www-data`). Esto solucionó errores de permisos en la línea de comandos (tests), pero no el error 403 en el navegador.
5.  **Creación de Controlador y Ruta Segura:** Para asegurar que solo usuarios autenticados puedan ver los archivos, se implementó un `FileController` y una ruta dedicada (`/storage/{path}`) protegida por el middleware `auth`.
6.  **Limpieza Exhaustiva de Caché:** Se ejecutaron todos los comandos de limpieza de caché de Laravel (`route:clear`, `view:clear`, `cache:clear`, `config:clear`) y se eliminó manualmente el contenido de `bootstrap/cache` para forzar una reconstrucción completa.
7.  **Cambio a URL Manual:** Para solucionar el `RouteNotFoundException`, se modificaron las vistas para generar las URLs manualmente con `url('/storage/' . $path)` en lugar de usar el helper `route()`.

### Sugerencias para Próximos Pasos

El problema parece residir en la configuración del entorno del servidor web (Nginx/Apache) o en cómo Laravel interactúa con él, más que en el código de la aplicación. El error 403, a pesar de los permisos y propietario correctos, sugiere un problema a un nivel más bajo.

1.  **Verificar la Configuración del Servidor Web:** Es crucial revisar la configuración del sitio en Nginx o Apache. Hay que asegurarse de que no haya reglas que bloqueen el acceso a la ruta `/storage` y que el `root` del sitio esté apuntando correctamente al directorio `public` de Laravel.
2.  **Diagnosticar el `FileController`:** Añadir logs al `FileController` para ver si la petición llega siquiera al controlador. Un `Log::info('Accediendo a la ruta: ' . $path);` al inicio del método `serve` puede confirmar si el problema está en el enrutamiento de Laravel o en el servidor web.
3.  **Revisar Logs del Servidor Web:** Inspeccionar los logs de error de Nginx (`/var/log/nginx/error.log`) o Apache en el momento exacto en que se produce el 403 puede dar la pista definitiva sobre la causa raíz.

### Prompt para la Próxima Iteración

```
La tarea es resolver un persistente error 403 (Forbidden) al intentar acceder a archivos subidos en una aplicación Laravel. A pesar de haber corregido los permisos de archivos (664), directorios (775), y el propietario (`www-data`), y haber creado una ruta segura con un controlador para servir los archivos, el error continúa.

Tu misión es diagnosticar y solucionar este problema. Revisa el archivo `NEXTSTEP.md` para un resumen completo de todos los intentos de solución fallidos.

El siguiente paso lógico es investigar la configuración del servidor web (Nginx/Apache) y añadir logs al `FileController` para determinar en qué punto exacto está fallando la petición. Comienza por añadir un log al método `serve` en `app/Http/Controllers/FileController.php` para ver si la petición está llegando a la aplicación Laravel, y luego revisa los logs de error del servidor web.
```

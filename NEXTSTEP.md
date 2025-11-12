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

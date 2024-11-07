# Creación de una página web dinámica en PHP

## Objetivo

El objetivo de esta práctica es el desarrollo de una página web dinámica en PHP que incluya una serie de funcionalidades avanzadas para gestionar, visualizar y editar datos de una fuente externa. Los datos se obtendrán mediante técnicas de web scraping usando `Selenium` con `Python`. El proyecto también requerirá la implementación de autenticación segura mediante tokens `JWT`, una interfaz de administración protegida, y la internacionalización para soportar múltiples idiomas.

## Especificaciones Técnicas

La práctica deberá cumplir con las siguientes especificaciones:

### Front-end con Bootstrap

Implementa la interfaz de usuario utilizando el framework `Bootstrap` para asegurar un diseño responsive y moderno.
La página principal debe mostrar los datos scrapeados, con la posibilidad de que los usuarios puedan interactuar con ellos (ej., búsquedas, filtros, etc.).

### Routing en PHP

Implementa un sistema de routing en `PHP` que permita gestionar las diferentes rutas de la aplicación (ej., página principal, página de administración, autenticación, etc.).
Cada ruta debe estar claramente separada para facilitar la mantenibilidad del código.

### Panel de Administración

Crea un panel de administración accesible solo para usuarios autenticados, desde donde se puedan gestionar los datos obtenidos a través de scraping.
Los datos que se scrapeen deben ser almacenados en una base de datos y ser editables desde este panel.

### Autenticación mediante API y JWT

Implementa una API autenticada mediante `JWT` (`JSON Web Tokens`) para manejar la autenticación de usuarios.
La autenticación debe permitir el inicio de sesión y la permanencia de la sesión incluso si el usuario cierra el navegador, utilizando cookies y sesiones.
La API debe proteger el acceso a las rutas y funcionalidades del panel de administración para asegurar que solo usuarios autenticados tengan acceso.

### Internacionalización
La aplicación debe soportar internacionalización utilizando la biblioteca `gettext` en `PHP`.
Implementa al menos dos idiomas (ej., español e inglés), y asegúrate de que todas las interfaces de usuario y mensajes estén traducidos correctamente.

### Scraping de Datos con Selenium y Python
Realiza el scraping de datos en una web pública utilizando `Selenium` en `Python`.
Los datos obtenidos deben ser estructurados y almacenados en una base de datos en el servidor del proyecto.
El script de scraping debe ser capaz de ejecutarse de manera independiente y almacenar los datos en la base de datos para luego ser gestionados desde el panel de administración.

### Modelo de Datos en Base de Datos
Crea un modelo de datos adecuado para almacenar la información scrapeada en la base de datos.
El modelo de datos debe ser estructurado de manera que permita futuras ampliaciones o cambios sin grandes modificaciones en el sistema.

### Gestión de Sesiones y Cookies
Implementa la gestión de sesiones y cookies para mantener al usuario conectado en caso de que cierre el navegador, permitiendo el acceso continuo a la administración.

## Requerimientos de Entrega

* **Estructura del código**: El código debe estar estructurado y organizado en módulos claros y separados (routing, autenticación, scraping, etc.).
* **Comentarios y documentación**: Todo el código debe estar correctamente comentado y documentado para facilitar su comprensión y revisión.
* **Repositorio en GitHub**: El proyecto debe estar alojado en un repositorio privado de `GitHub` y compartido con el profesor para su evaluación.
* **README.md**: El proyecto debe incluir un archivo `README.md` que explique cómo instalar y ejecutar el proyecto, junto con una descripción general de la estructura del código y las tecnologías utilizadas.

## Evaluación y valoración

La evaluación de la práctica considerará los siguientes aspectos:

* **Funcionalidad completa**: Se valorará que la página cumpla con todas las funcionalidades requeridas (scraping, panel de administración, internacionalización, autenticación con JWT, etc.).
* **Diseño y usabilidad**: La interfaz de usuario debe ser intuitiva, clara y profesional, y ser completamente funcional en dispositivos móviles y de escritorio.
* **Estructura y calidad del código**: Se valorará la organización del código, la claridad en los comentarios y la estructura de los archivos.
* **Documentación completa**: La documentación debe estar bien escrita y ser suficiente para permitir a otro desarrollador comprender y ejecutar el proyecto fácilmente.

### Recomendaciones

* Asegúrate de probar el sistema de autenticación para verificar que protege adecuadamente las rutas sensibles.
* Realiza pruebas de la internacionalización para confirmar que las traducciones son correctas y que el cambio de idioma funciona en toda la aplicación.
* Realiza pruebas de carga en el scraping para optimizar el tiempo de ejecución y asegurar que se maneje cualquier posible cambio en la estructura de la página objetivo.
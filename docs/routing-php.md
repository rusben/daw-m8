## Routing system en PHP

Si recién estás comenzando tu viaje en el desarrollo de PHP, es probable que uses nombres de archivos completos en la URL para navegar por tu aplicación, como `server/contact.php`. No te preocupes, todos empezamos así y así es como aprendemos.

Hoy quiero ayudarte a mejorar la forma en que navegas por los archivos en tu aplicación. Hablaremos sobre el `routing`, ya que es crucial en cualquier aplicación moderna. Te ayudará a dar un paso adelante en tu desarrollo PHP profesional.

Un sistema de `routing` simplemente asigna una solicitud HTTP a un controlador de solicitudes (función o método). Es decir, define cómo navegamos o accedemos a diferentes partes de una aplicación sin necesidad de escribir el nombre del archivo. Puede hacer esto creando o configurando rutas (o caminos). Por ejemplo, la ruta `server/contact` nos permite acceder al archivo contact.php.

## Requisitos previos

Para aprovechar al máximo este tutorial, necesitará lo siguiente:

* Una comprensión básica de `PHP`.
* Una comprensión básica de `http` y redes.
* Un servidor `apache2` o `nginx` y conocimientos básicos de cómo configurarlos.

## ¿Cómo funciona el `routing`?

Primero, déjame recordarte qué es una ruta. El `routing` nos permite estructurar nuestra aplicación de una mejor manera y deshacernos de las URL desordenadas. Estas son dos características principales que ofrece cualquier buen sistema de `routing`:

* Define qué acción ejecutar para cada solicitud entrante.
* Genera URL compatibles con SEO (por ejemplo, `/views/users` en lugar de `views/user.php?all`).

Para hacer un sistema de `routing`, necesitamos un `router`, que no es más que el archivo de entrada a nuestra aplicación. De forma predeterminada, este archivo de entrada se denomina `index.php`. Dentro del archivo definimos el sistema de `routing` gracias a [switch](https://www.php.net/manual/en/control-structures.switch.php) o [match](https://www.php.net/manual/en/control-structures.match.php) declaraciones.

Por último y no menos importante, debemos redirigir todas las solicitudes al router. Esto se hace en el archivo de configuración del servidor PHP.

## Configuración del proyecto

Antes de seguir adelante, veamos cómo será el proyecto:

```bash
.
├── index.php
└── views
    ├── 404.php
    ├── contact.php
    ├── home.php
    └── users.php

```

## Utilice los siguientes comandos de shell para iniciar el proyecto:
    
* **.htaccess**: un archivo de configuración de `apache2` a nivel de directorio. No lo necesita si utiliza un servidor `nginx`.
* **index.php**: este es el `router` y el archivo de entrada del proyecto. Todas las solicitudes entrantes serán redirigidas aquí.
* **views**: esta carpeta contiene todas las interfaces de usuario del proyecto.

## Cómo redirigir todas las solicitudes HTTP al `router`

Dijimos anteriormente que la redirección se realiza en el archivo de configuración del servidor PHP. Por lo tanto, deberás realizar algunos ajustes dependiendo de si utilizas un servidor `apache2` o `nginx`.

### Redirigir usando Apache

Aquí podemos usar fácilmente el archivo .htaccess que ya hemos creado en la raíz del proyecto. Agregue las directivas a continuación:

```bash
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)＄ index.php
```

* Línea 1: Activamos el motor de reescritura en tiempo de ejecución del servidor `apache2`.
* Línea 2: Limitamos el acceso a archivos físicos.
* Línea 3: redirigimos todas las próximas solicitudes al `index.php`.

Nota: Si el sitio o la aplicación se encuentran en la raíz del servidor (o si no tenemos un host virtual), así es como debería verse el `.htaccess`:

```bash
RewriteEngine On
RewriteBase /folder/
RewriteRule ^index\\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /folder/index.php [L]
```

En el código anterior, reemplace `/folder/` con el nombre de la carpeta que contiene su sitio.

### Redirigir usando nginx

El archivo de configuración predeterminado se llama nginx.conf. Este archivo se puede encontrar en `etc/nginx`, `usr/local/nginx/conf`, o `/usr/local/etc/nginx`.

Para redirigir a `index.php` use el siguiente comando:

```bash
location / {
        try_files $uri $uri/ /index.php
}
```

La `location /` bloque especifica que se trata de una coincidencia para todas las ubicaciones a menos que se especifique explícitamente la  `location /<name>`.

La directiva `try_files` le dice al servidor que para cualquier solicitud al URI que coincida con el bloque en la ubicación, pruebe primero con `$uri` (o `$uri/`) y, si el archivo está presente, entregue el archivo. De lo contrario, se utiliza la opción alternativa (`index.php`). Y este último comportamiento es el que queremos.

Vuelva a cargar el servidor después de la modificación.

## ¿Cómo crear el sistema de `routing`?

Ahora sabemos cómo funciona el `routing` e incluso enviamos todas las solicitudes al `router`. Ahora es el momento de escribir el código del `router` en `index.php`.

Primero, cree una variable para contener la cadena de solicitud HTTP:

```bash
$request = $_SERVER['REQUEST_URI'];
```

Esta variable nos ayudará a comparar con muchas rutas (rutas) y llamar a la interfaz de vista adecuada.

```php
switch ($request) {
     case '/views/users':
        require __DIR__ . '/views/users.php';

     case '/views/department':
        require __DIR__ . '/views/dep.php';
}
```

¿Qué está pasando aquí? La declaración `switch` es similar a una serie de declaraciones `if` en la misma expresión (variable). Ejecuta un código solo cuando se encuentra una declaración de `case` cuya expresión se evalúa como un valor que coincide con el valor de la expresión `switch`. 

Consideremos que nuestra variable tiene el valor `/views/users/`. Cuando se ejecute la parte del código anterior, `PHP` verificará si el valor `/views/users` es igual al valor de la declaración case, que en nuestro caso es `/views/users/`. Entonces, esta condición se evaluará como verdadera, PHP llamará al archivo `/views/users.php`. Si la condición se evalúa como falsa, `PHP` buscará la siguiente declaración de caso hasta el final del bloque de `switch`.

Nota: Cada vez que la declaración de caso se evalúa como verdadera, PHP continuará ejecutando el código en las siguientes declaraciones de caso sin necesariamente evaluar esas declaraciones de caso. En nuestro caso, PHP también requiere views/dep.php. Para evitar este "mal comportamiento", debe agregar una declaración de interrupción después de cada declaración de caso.

Ahora juntemos todo en nuestro archivo `index.php`:

```php
<?php

$request = $_SERVER['REQUEST_URI'];
$viewDir = '/views/';

switch ($request) {
    case '':
    case '/':
        require __DIR__ . $viewDir . 'home.php';
        break;

    case '/views/users':
        require __DIR__ . $viewDir . 'users.php';
        break;

    case '/contact':
        require __DIR__ . $viewDir . 'contact.php';
        break;

    default:
        http_response_code(404);
        require __DIR__ . $viewDir . '404.php';
}
```

Como ya sabe, comenzamos almacenando una solicitud de usuario en la variable `$request`, luego la usamos en la declaración de cambio. En aras de un código limpio, he creado una variable para contener el nombre del directorio de vista.

También notarás otras dos cosas:

* Tanto ```''``` como ```'/'``` se utilizan para hacer coincidir `site.com` y `site.com/` cuando los usuarios están en la raíz de la aplicación o el sitio web.
* Hay una declaración de `case` especial, `default`, para hacer coincidir cualquier cosa que no coincida con los otros casos, es decir, cuando se desconoce la ruta.

Ahora agreguemos algunos datos ficticios en nuestras `views`.

## Agregar datos ficticios en el directorio views
Ya hemos creado todos los archivos en el directorio `views`. Vayamos a este directorio y agreguemos algo de contenido en cada archivo.

Simplemente coloque algo de contenido en cada archivo:

```html
<h1>Home</h1>
<p>Welcome in my app.</p>
```

```html
<h1>Users</h1>
<p>List of our users.</p>
```

```html
<h1>Conct us</h1>
<p>Getting in touch is easy. Just email us</p>
```

```html
<h1>404</h1>
<p>You've reached the end of Internet.</p>
```

Como puede ver, cada archivo solo contiene un título y un párrafo. Siéntete libre de agregar el contenido que quieras y probar el `router`.

## Pensamientos finales

En este tutorial, aprendió cómo crear un sistema de `routing` básico desde cero, que incluye:

* Cómo crear un archivo llamado `index.php` en la raíz del proyecto. Este es el `router` para su aplicación.
* Cómo redirigir todas las solicitudes entrantes al `router`. Esto lo haces en el archivo de configuración de tu servidor.
* Cómo crear el sistema de `routing` con una declaración de cambio en el `router`.


### Más información
https://www.freecodecamp.org/news/how-to-build-a-routing-system-in-php/
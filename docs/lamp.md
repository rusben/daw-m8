# LAMP Stack
Una LAMP Stack es un conjunto de tecnologías de código abierto que permite crear aplicaciones web dinámicas. El acrónimo LAMP representa las siguientes tecnologías:

* **Linux**: Es el sistema operativo base. Existen diversas distribuciones de Linux como Ubuntu, CentOS, Debian, etc., siendo Ubuntu 24.04 una de las versiones más recientes.
* **Apache**: Es el servidor web encargado de gestionar las solicitudes HTTP y servir páginas web. Es uno de los servidores más utilizados en el mundo debido a su flexibilidad y fiabilidad.
* **MySQL/MariaDB**: Es el sistema de gestión de bases de datos (RDBMS). Aunque históricamente se ha usado MySQL, en muchas distribuciones Linux modernas se opta por MariaDB, una bifurcación de MySQL, por su compatibilidad y licencias abiertas.
* **PHP**: Es el lenguaje de programación utilizado para desarrollar aplicaciones web dinámicas. PHP se integra con Apache y se comunica con MySQL/MariaDB para generar contenido dinámico.

En conjunto, estas tecnologías permiten construir y servir aplicaciones web dinámicas y robustas.

## Instalación de la LAMP Stack en Ubuntu 24.04

Para instalar la pila LAMP en Ubuntu 24.04, sigue los siguientes pasos:

1. Actualizar los repositorios del sistema

Antes de iniciar la instalación, asegúrate de que tu sistema esté actualizado. Abre una terminal y ejecuta:

```bash
sudo apt update
sudo apt upgrade
```

2. Instalar Apache

Apache es el servidor web que servirá las páginas HTML y las solicitudes HTTP.

```bash
sudo apt install apache2
```

Una vez instalado, puedes verificar que Apache esté funcionando abriendo tu navegador web y accediendo a http://localhost. Si ves una página de bienvenida de Apache, la instalación fue exitosa.

3. Instalar MySQL

Para la gestión de bases de datos, instalaremos MySQL.

```bash
sudo apt install mysql-server
```

Luego, asegúrate de que el servicio de MySQL esté corriendo:

```bash
sudo systemctl status mysql
```

Para mejorar la seguridad de la base de datos, ejecuta el siguiente script:

```bash
sudo mysql_secure_installation
```

Este comando te guiará por una serie de pasos como eliminar usuarios anónimos, deshabilitar el acceso remoto al root y eliminar la base de datos de prueba.

4. Instalar PHP

PHP es el lenguaje de programación que genera contenido dinámico en las aplicaciones web. Se instalará junto con algunas extensiones necesarias para que Apache y MySQL trabajen con PHP.

```bash
sudo apt install php libapache2-mod-php php-mysql
```

Una vez instalado PHP, verifica la versión instalada con:

```bash
php -v
```

5. Configurar Apache para usar PHP

En esta etapa, configuraremos Apache para priorizar archivos PHP sobre los archivos HTML. Abre el archivo de configuración principal de Apache:

```bash
sudo vim /etc/apache2/mods-enabled/dir.conf
```

Busca la línea que tiene el siguiente contenido:

```bash
<IfModule mod_dir.c>
    DirectoryIndex index.html index.cgi index.pl index.php index.xhtml index.htm
</IfModule>
```

Asegúrate de que index.php aparezca primero en la lista, así:

```bash
<IfModule mod_dir.c>
    DirectoryIndex index.php index.html index.cgi index.pl index.xhtml index.htm
</IfModule>
```

Guarda el archivo y sal del editor.

6. Reiniciar Apache

Para que los cambios surtan efecto, reinicia Apache con el siguiente comando:

```bash
sudo systemctl restart apache2
```

7. Probar PHP

Crea un archivo PHP en el directorio de Apache para verificar que PHP esté funcionando correctamente.

```bash

sudo vim /var/www/html/info.php

Agrega el siguiente contenido:

```php
<?php
phpinfo();
?>
```

Guarda el archivo y accede a http://localhost/info.php desde tu navegador. Deberías ver una página con información detallada sobre la instalación de PHP.
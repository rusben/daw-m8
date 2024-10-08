# apache2

`apache2` es un servidor web de código abierto que se utiliza para servir contenido en la web. Es uno de los servidores más populares y confiables, conocido por su flexibilidad y capacidad de configuración.

## Módulos de apache2

`apache2` tiene una arquitectura modular que permite a los administradores habilitar o deshabilitar funcionalidades según sus necesidades. Algunos de los módulos más comunes son:

* **mod_ssl**: Permite la comunicación segura a través de HTTPS.
* **mod_rewrite**: Proporciona la capacidad de reescribir URLs, lo que es útil para SEO y usabilidad.
* **mod_proxy**: Permite que Apache actúe como un proxy inverso, redirigiendo las solicitudes a otros servidores.
* **mod_php**: Permite ejecutar scripts PHP en el servidor.
* **mod_auth_basic**: Proporciona autenticación básica para proteger recursos.

## Configuració de `VirtualHost` amb apache2

* [Introducció](#introduccio)
* [Creació de l'estructura de directoris](#estructura-directoris)
* [Definició del `VirtualHost`](#definicio-virtual-host)
* [Habilitar el `VirtualHost`](#habilitar-virtual-host)
* [Reiniciar `apache2`](#reiniciar-apache2)
* [Modificar `/etc/hosts` per incloure el nostre domini](#modificar-etc-hosts)
* [Comprovar el funcionament](#comprovar-funcionament)
* [En cas d'error comprovar el registre d'`apache2`](#comprovar-registre-apache2)
  * [Registre d'errors d'`apache2`](#registre-errors-apache2)
  * [Registre d'accés d'`apache2`](#registre-acces-apache2)
* [Assignació de permisos](#assignacio-permisos)

### Introducció <a name="introduccio"></a>
Un servidor web està dissenyat per poder allotjar sites de forma independent. Llavors podem tenir més d'un site dintre d'un mateix servidor web. Per aconseguir que cada site funcioni de forma independent es configuren el que s'anomenen `VirtualHost`. Cada `VirtualHost` que definim en la nostra configuració representarà una web independent instal·lada al nostre servidor.

Amb el `VirtualHost` creat podrem accedir a la nostra instal·lació des d'un navegador introduint el nom que hagim seleccionat.

### Creació de l'estructura de directoris <a name="estructura-directoris"></a>

Hem de crear l'estructura de directoris on s'allotjarà la nostra instal·lació, en aquest cas la nostra web s'anomenarà:​ `domini.local` i la instal·larem al directori per defecte on apache espera que guardem els nostres sites a `/var/www/`. De fet, el podriem allotjar a qualsevol lloc, però el farem servir per tenir les nostres webs endreçades.

​Creem el directori de la següent manera:

```console
sudo mkdir /var/www/domini.local
```

### Definició del `VirtualHost` <a name="definicio-virtual-host"></a>

Ara hem de definir els arxius de configuració de l'`apache2` que representen  `VirtualHost` on instal·larem la nostra aplicació web. L'arxiu `domini.local.conf` guardarà la configuració del nostre `VirtualHost`

```console
sudo vim /etc/apache2/sites-available/domini.local.conf
```

Editem el fitxer modificant `domini` pel nom que haguem escollit.

```console
# domini.local.conf
<VirtualHost *:80>
    ServerAdmin admin@domini.local
    ServerName www.domini.local
    ServerAlias domini.local
    DocumentRoot /var/www/domini.local
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

Guardem l'arxiu i ja tenim el fitxer de configuració del `VirtualHost`.

### Habilitar el `VirtualHost` <a name="habilitar-virtual-host"></a>

Un cop definits el `VirtualHost` els hem d'habilitar. Ho farem mitjançant la comanda `a2ensite`. ​Aquesta comanda es pot llegir com `apache2 enable site`)​

Aquesta comanda espera que estiguem al directori:
```console
/etc/apache2/sites-available
```
En cas de no estar en aquest directori ens posicionem:

```console
cd /etc/apache2/sites-available
```

Finalment per habilitar el `VirtualHost` hem d'escriure la següent comanda:

```console
sudo a2ensite domini.local.conf
```

### Reiniciar apache2 <a name="reiniciar-apache2"></a>
Després de fer canvis de configuració és necessari reiniciar l'`apache2`

```console
sudo service apache2 restart
```
o
```console
sudo systemctl restart apache2
```

El `VirtualHost` ja està definit i preparat per a executar­-se.

### Modificar `/etc/hosts` per incloure el nostre domini <a name="modificar-etc-hosts"></a>

Per a poder accedir als nostres hosts mitjançant el nom hem d'indicar-lo al fitxer de hosts coneguts de la nostra màquina, aquest fitxer el podem trobar a `/etc/hosts`.

Hem d'incloure'ls apuntant a la IP del `localhost`, per a que quan el navegador busqui el nom de la nostra web busqui directament a la nostra màquina.

Edita el fitxer `/etc/hosts`, és necessari tenir permisos d'administració per a poder modificar el fitxer. ​

```console
sudo vi /etc/hosts
```

Afegeix la següent línia per a que la nostra màquina busqui `www.domini.local` a la nostra web:

```console
127.0.0.1   www.domini.local
```

Guarda i tanca l'arxiu.

### Comprovar el funcionament <a name="comprovar-funcionament"></a>
Has de poder accedir a la web que hem preparat per al nostre domini. Entra amb el navegador web a l'adreça del nostre domini i comprova que pots veure el contingut de la teva web (si encara no hem portat res al directori hauria d'apareixer el directori buit).

`http://www.domini.local`

### En cas d'error comprovar el registre d'apache2 <a name="comprovar-registre-apache2"></a>

#### Registre d'errors del servidor `ErrorLog` <a name="registre-errors-apache2"></a>
El registre d'errors del servidor, el nom i la ubicació del qual s'especifica a la norma `ErrorLog`, és el més important de tots els registres, `apache2` enviarà qualsevol informació de diagnòstic i registrarà qualsevol error que trobi en processar peticions al fitxer de registre seleccionat.

És el primer lloc on ha de mirar quan sorgeixi un problema en iniciar el servidor o durant la seva operació normal, perquè sovint hi trobareu informació detallada de què ha anat malament i com solucionar el problema.

El fitxer d'error de l'`apache2` l'hem configurat a:

```console
cat /var/log/apache2/error.log
```

#### Registre d'accés del servidor `CustomLog` <a name="registre-acces-apache2"></a>
El servidor emmagatzema al registre d'accés informació sobre totes les peticions que processa. La ubicació del fitxer de registre i el contingut que es registra es poden modificar amb la directiva `CustomLog`.

El fitxer d'accés de l'`apache2` l'hem configurat a:

```console
cat /var/log/apache2/access.log
```

### Assignació de permisos <a name="assignacio-permisos"></a>
L'usuari que executa `apache2` és, per defecte, `www-data` i voldrem que qualsevol instal·lació que fem al nostre servidor no tingui problemes de permisos. Per canviar el propietari a l'usuari loginat en la sessió utilitzarem la següent comanda:

```console
sudo chown -R $USER:www-data /var/www/domini.local
```

Després d'això els arxius i subdirectoris (el paràmetre `-­R` aplica recursivament l'acció a tots els arxius i subdirectoris) estàn ara sota el propietari de la sessió actual.

La variable `$USER` conté el valor de l'usuari loginat. Llavors en el moment que premeu `ENTER` es substituirà pel seu valor.

En el meu cas, el meu usuari és `rusben`, llavors és equivalen a fer el següent:

```console
sudo chown -R rusben:www-data /var/www/domini.local
```

Si quan executem la comanda estem loginats amb l'usuari `root` seria equivalent a fer:

```console
sudo chown -R root:www-data /var/www/domini.local
```

​Encara hem de canviar alguns permisos. Habilitem l'accés de lectura de tot el directori web i els seus subdirectoris, al propietari i al grup, mentre que la resta d'usuaris només podrá llegir i escriure (`rwxrwxr-x`).

```console
sudo chmod -R 775  /var/www/domini.local
```

### Extra: accedir a l'aplicació web d'un company o companya
Enlloc d'utilitzar la IP de `localhost` podeu utilitzar la IP de la vostra màquina.

Com a curiositat, ja que teniu instal·lat un servidor web i esteu en una xarxa interna, potser heu pensat com accedir als dominis dels vostres companys i companyes. Ho podeu fer substituint al fitxer `/etc/hosts` la IP de `localhost` per la IP del vostre company o companya i assignant-li un nom de domini.

# Aplicació de permisos a les nostres aplicacions web
Un cop descomprimits els fitxers de l'aplicació web al directori `/var/www/html`, apliquem els següents permisos al directori `/var/www/html`

```console
cd /var/www/html
chmod -R 775 .
chown -R usuario:www-data .
```

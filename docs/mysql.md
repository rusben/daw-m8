# Configuració de MySQL
## Instal·lació de MySQL
```console
sudo apt install mysql-server
```

## Accedim a la consola de MySQL
Des d'un terminal on siguem `root` hem d'executar la següent comanda:
```console
root@elpuig:~$ mysql
```

## Creació de la base de dades:
Un cop dins la consola de MySQL executem les comandes per a crear la base de dades. En aquest cas estem creant una base de dades amb el nom `bbdd`.

```console
CREATE DATABASE bbdd;
```

## Creació d'un usuari
Tingueu en compte que s'haurà d'identificar la IP des de la qual s'accedirà a la base de dades, en aquest cas, `localhost`.

```console
CREATE USER 'usuario'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password';
```

## Donem privilegis a l'usuari:
```console
GRANT ALL ON bbdd.* to 'usuario'@'localhost';
```

## Sortim de la base de dades
```console
exit
```

## Probem la connexió a la base de dades
Des d'un terminal amb un usuari sense privilegis hem de ser capaços de connectar introduïnt la nostra contrassenya.

```console
alumne@elpuig:~$ mysql -u usuario -p
```

# Extra: permetre la connexió des d'una màquina remota
Per seguretat, MySQL no permet per defecte connexions que no siguin des de localhost. Si volem canviar aquest comportament hem de crear un altre usuari que accedirà des d'una màquina remota i estarà identificat pel nom d'usuari i la seva IP. Així doncs, poden existir diferents usuaris anomenats `usuario` que connecten des de diferents màquines.

## Canviem l'accés per defecte a la nostra màquina
Permetem l'accés des de qualsevol equip a la nostra base de dades.

```console
cat /etc/mysql/mysql.conf.d/mysqld.cnf | grep bind-address
bind-address = 127.0.0.1
```

Hem de canviar bind-address per `0.0.0.0`
```console
bind-address = 0.0.0.0
```

## Reiniciem el servidor
```console
systemctl restart mysql
```

## Creació d'un usuari per a accedir des d'una màquina remota
Per accedir des d'una màquina remota, hauriem de crear un usuari nou identificat pel nom d'usuari i la IP de la màquina des de la qual accedirà.

```console
CREATE USER 'usuario'@'192.168.22.100' IDENTIFIED WITH mysql_native_password BY 'password';
```

Hem de donar privilegis a l'usuari que accedirà des de la màquina remota.
Per accedir des de fora, hauriem de donar-li també privilegis a l'usuari a l'altra màquina:

```console
GRANT ALL ON bbdd.* to 'usuario'@'192.168.22.100';
```

# Aplicació de permisos a les nostres aplicacions web
Un cop descomprimits els fitxers de l'aplicació web al directori `/var/www/html`, apliquem els següents permisos al directori `/var/www/html`

```console
cd /var/www/html
chmod -R 775 .
chown -R root:www-data .
```

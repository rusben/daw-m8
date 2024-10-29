# Autenticación con PHP y MySQL

Para implementar autenticación en una página web usando `PHP` y `MySQL`, se deben gestionar varios aspectos clave: la validación de usuarios, la gestión de sesiones y cookies, la seguridad de contraseñas y el manejo de sesiones de usuario. Primero es esencial comprender el flujo completo de autenticación, que incluye:

* Registro de usuario.
* Inicio de sesión.
* Gestión de la sesión.
* Uso de cookies (opcional, para mantener la sesión activa).
* Protección y seguridad de contraseñas.

## Registro de usuarios (MySQL)

Primero, necesitas una tabla para almacenar la información de los usuarios. Un diseño básico de la tabla usuarios podría ser el siguiente:

```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Contraseña hasheada
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

La columna `password` debe almacenar la contraseña en formato hasheado para que no se guarde en texto plano.

## Guardar contraseñas de forma segura (PHP)

Cuando un usuario se registra, la contraseña debe ser hasheada antes de ser guardada en la base de datos. Para esto, PHP ofrece una función segura llamada `password_hash()`. Es esencial nunca guardar contraseñas en texto plano.


```php

<?php
// Supongamos que recibimos el nombre de usuario, email y contraseña desde un formulario
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

// Hasheamos la contraseña
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Guardamos en la base de datos
$pdo = new PDO('mysql:host=localhost;dbname=mi_base_de_datos', 'usuario', 'contraseña');
$sql = "INSERT INTO usuarios (username, email, password) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$username, $email, $hashed_password]);

echo "Usuario registrado exitosamente";
?>
```

## Inicio de Sesión de Usuario

Para autenticar un usuario al iniciar sesión, el flujo es el siguiente:

* Verificar si el usuario existe en la base de datos.
* Validar la contraseña ingresada contra la almacenada en la base de datos utilizando password_verify().
* Si la autenticación es exitosa, crear una sesión de usuario.

```php

<?php
session_start(); // Inicia la sesión

// Supongamos que recibimos el nombre de usuario y la contraseña desde un formulario
$username = $_POST['username'];
$password = $_POST['password'];

// Verificamos si el usuario existe
$sql = "SELECT * FROM usuarios WHERE username = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    // Contraseña correcta, guardamos el id del usuario en la sesión
    $_SESSION['user_id'] = $user['id'];
    echo "Inicio de sesión exitoso";
} else {
    echo "Nombre de usuario o contraseña incorrectos";
}
?>
```

## Inicio de y gestión de Sesiones

Al iniciar sesión, debes verificar las credenciales del usuario comparando la contraseña ingresada con la contraseña almacenada en la base de datos. Aquí es donde utilizamos la función `password_verify()`.

```php

$username = $_POST['username'];
$password = $_POST['password'];

// Consultar base de datos
$stmt = $conn->prepare("SELECT id, password FROM usuarios WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    // La autenticación es correcta
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $username;
    // Redirigir al usuario a su perfil o a la página de inicio
    header("Location: perfil.php");
} else {
    // Usuario o contraseña incorrectos
    echo "Nombre de usuario o contraseña incorrectos.";
}
```

Cuando un usuario inicia sesión, almacenamos su `user_id` en la variable de sesión `$_SESSION`. Esto permite que en cualquier página protegida puedas verificar si el usuario está autenticado o no.

Para iniciar y destruir sesiones:

* **Iniciar una sesión**: Usa `session_start()` al inicio del archivo `PHP`.
* **Cerrar sesión**: Elimina la sesión con `session_destroy()` cuando el usuario decide cerrar su sesión.

Ejemplo para verificar si un usuario está autenticado:

**Iniciar una sesión**
```php

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirigir al usuario a la página de inicio de sesión
    header('Location: login.php');
    exit();
}
?>
```

**Cerrar Sesión:**

```php

<?php
session_start();
session_destroy(); // Destruye la sesión
header('Location: login.php'); // Redirige al login
?>
```

## Uso de Cookies para Mantener Sesión Activa




4. Uso de Cookies para Mantener la Sesión Activa

Para recordar la sesión de un usuario incluso después de cerrar el navegador, puedes utilizar cookies. Esto implica generar un token seguro y almacenarlo tanto en una cookie del navegador como en la base de datos.
Ejemplo de creación de token con cookies (opcional)

Al momento de iniciar sesión, genera un token y almacénalo en la base de datos y en una cookie.

```php

if ($user && password_verify($password, $user['password'])) {
    session_start();
    $_SESSION['user_id'] = $user['id'];
    
    // Genera un token de sesión para recordar al usuario
    $token = bin2hex(random_bytes(16));
    setcookie("session_token", $token, time() + (86400 * 30), "/"); // 30 días

    // Guarda el token en la base de datos
    $stmt = $conn->prepare("UPDATE usuarios SET session_token = ? WHERE id = ?");
    $stmt->bind_param("si", $token, $user['id']);
    $stmt->execute();

    header("Location: perfil.php");
}
```

En cada carga de página, verifica si la cookie `session_token` está presente y coincide con el token en la base de datos.

```php

    session_start();
    if (isset($_COOKIE['session_token'])) {
        $token = $_COOKIE['session_token'];
        
        $stmt = $conn->prepare("SELECT id, username FROM usuarios WHERE session_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
        } else {
            // Token inválido
            setcookie("session_token", "", time() - 3600, "/"); // Eliminar cookie
            header("Location: login.php");
            exit();
        }
    }
```

Para mantener una sesión activa incluso después de que el usuario cierre el navegador, puedes usar cookies. Al iniciar sesión, puedes crear una cookie con un token único y almacenar el token en la base de datos, ligado al usuario.

### Guardar token en una cookie y en la base de datos:

```php

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];

    // Generar un token único
    $token = bin2hex(random_bytes(16));
    setcookie("login_token", $token, time() + (86400 * 30), "/"); // 30 días

    // Guardar el token en la base de datos
    $sql = "UPDATE usuarios SET token = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$token, $user['id']]);
}
```

### Autenticación Automática con Cookie

Cuando el usuario vuelva, puedes verificar si la cookie login_token está presente y, si coincide con el token en la base de datos, autenticarlos automáticamente.

```php

if (isset($_COOKIE['login_token'])) {
    $sql = "SELECT * FROM usuarios WHERE token = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_COOKIE['login_token']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
    }
}
```

## Seguridad Adicional

**Hasheo de Contraseñas**

* `password_hash()` y `password_verify()` ofrecen protección contra ataques de fuerza bruta.
* Nunca guardes contraseñas en texto plano.

**Prevención de Ataques XSS y CSRF**

* Para prevenir ataques `XSS`, escapa las variables de usuario en `HTML` con `htmlspecialchars()`.
* Implementa tokens `CSRF` para formularios críticos (por ejemplo, al cambiar la contraseña).

**Usar HTTPS**
Implementa `HTTPS` en el servidor para proteger los datos de usuario en tránsito.

## Seguridad y Buenas Prácticas
Sanitización de entradas

Utiliza declaraciones preparadas con bind_param para evitar ataques de SQL Injection.
Protección contra ataques CSRF

Cuando trabajes con formularios sensibles, considera agregar un token CSRF.

```php

// Genera el token y guárdalo en la sesión
$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;

// Incluir el token en el formulario
echo '<input type="hidden" name="csrf_token" value="'.$csrf_token.'">';
```

Luego, al recibir el formulario:

```php

if ($_POST['csrf_token'] != $_SESSION['csrf_token']) {
    die("CSRF token inválido.");
}
```

### Uso de HTTPS

Es fundamental servir el sitio a través de HTTPS para asegurar que los datos estén encriptados en tránsito, especialmente si se usan cookies para mantener sesiones activas.

**Expiración de la Sesión**

Para proteger sesiones inactivas, establece una duración de sesión máxima.

```php
session_start();
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
$_SESSION['last_activity'] = time();
```

### Resumen del flujo

* **Registrar**: Hashear la contraseña y guardarla. El usuario se registra y su contraseña se almacena de forma segura en la base de datos.
* **Iniciar Sesión**: Verificar la contraseña y crear una sesión. Durante el inicio de sesión, se verifica la contraseña y, si es correcta, se inicia la sesión y se guarda información del usuario.
* **Gestión de Sesión**: Verificar `$_SESSION['user_id']` para confirmar si el usuario está autenticado. Las páginas restringidas verifican que el usuario tenga una sesión activa (o un token válido si se usa una cookie).
* **Cookies (opcional)**: Crear un token para mantener la sesión si el usuario quiere estar autenticado. Se gestionan las sesiones con vencimiento automático, y el usuario puede cerrar la sesión en cualquier momento.
* **Cerrar Sesión**: Usar `session_destroy()` y limpiar cookies.

Este flujo básico cubre las prácticas recomendadas de autenticación y seguridad en `PHP` y `MySQL`.

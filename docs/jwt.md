# JWT (JSON Web Tokens)

Los `SWT (Security Web Tokens)`, mejor conocidos como `JWT (JSON Web Tokens)`, son una forma de autenticar y autorizar usuarios de manera segura en aplicaciones web y móviles. Estos tokens están diseñados para ser compactos y autoverificables, lo que permite su uso sin necesidad de hacer consultas constantes a la base de datos para autenticar al usuario.

## ¿Qué es un JWT?

Un JWT es un token en formato JSON que contiene información sobre el usuario o entidad autenticada. Los `JWT` son usados comúnmente para autenticar peticiones y controlar el acceso a diferentes recursos de una aplicación web. Un JWT incluye tres componentes principales:

* **Header (Encabezado)**: Define el tipo de token (JWT) y el algoritmo de cifrado, generalmente HMAC o RSA.
* **Payload (Cuerpo o Datos)**: Contiene los "claims" (reclamos o afirmaciones), que son la información que queremos almacenar sobre el usuario, como el user_id, role, y datos que pueden ser requeridos en una sesión.
* **Signature (Firma)**: Se utiliza para verificar que el token no ha sido manipulado. Es el resultado de aplicar un algoritmo de cifrado (por ejemplo, HMAC SHA256) al header y al payload, usando una clave secreta o privada.

Ejemplo de un JWT básico:

`eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c`

## Funcionamiento de un JWT

1. Autenticación del usuario:
* El cliente envía las credenciales de autenticación al servidor (por ejemplo, usuario y contraseña).
* El servidor verifica las credenciales, y si son correctas, crea un JWT y lo envía de vuelta al cliente. El JWT contiene los datos necesarios para identificar al usuario y está firmado digitalmente.

2. Almacenamiento del JWT en el cliente:
* El cliente (generalmente en una aplicación web o móvil) almacena el JWT en un lugar seguro, como localStorage o sessionStorage en el caso de aplicaciones web, o en un almacenamiento seguro para móviles.
* En cada solicitud subsiguiente al servidor, el cliente envía el JWT en los headers de autenticación (habitualmente en el header `Authorization: Bearer <token>)`.

3. Validación del JWT en el servidor:
* El servidor recibe el JWT, verifica su firma y, si es válida, extrae el payload.
* Con base en los datos del payload, el servidor puede identificar al usuario y procesar la solicitud sin necesidad de consultar la base de datos para autenticar al usuario.

4. Expiración del Token:
* Los JWT pueden configurarse con una fecha de expiración (exp). Una vez expiran, el usuario tendrá que autenticarse de nuevo.

## Implementación de JWT

La implementación de JWT incluye varios pasos:

1. Crear el JWT:
Se genera en el servidor después de autenticar al usuario. En la mayoría de los lenguajes existen bibliotecas que facilitan este proceso, como jsonwebtoken en `Node.js` o pyjwt en `Python`.

2. Firmar el JWT:
Para evitar la manipulación, el token se firma con una clave secreta (HS256 para HMAC o RS256 para RSA). La firma es parte esencial, ya que asegura la integridad del token.

3. Enviar el JWT al cliente:
Una vez creado, el JWT se envía al cliente. Normalmente se envía en el cuerpo de la respuesta o en las cookies.

4. Almacenar el JWT en el cliente:
En el lado del cliente, el JWT se guarda en localStorage o sessionStorage en aplicaciones web, o en el almacenamiento seguro del sistema en aplicaciones móviles.

5. Enviar el JWT con cada solicitud:
Cada vez que el cliente realiza una solicitud, incluye el JWT en el header de la solicitud, típicamente con el formato Authorization: Bearer <token>.

6. Validar el JWT en el servidor:
En el servidor, cada vez que se recibe un JWT, se verifica la firma con la clave secreta o la clave pública (si se usa RSA). Si la verificación es correcta, se permite al usuario acceder al recurso.


## Buenas prácticas y consideraciones

* **Almacena los tokens en un lugar seguro**: Evita guardar tokens en localStorage si hay riesgo de ataques XSS. Considera usar httpOnly cookies para mayor seguridad.
* **Manejo de expiración y refresco de tokens**: Usa un sistema de "refresh tokens" para evitar la necesidad de pedir constantemente credenciales al usuario.
* **Protección de la clave secreta**: La clave secreta usada para firmar el JWT debe mantenerse segura y fuera del código fuente.
* **Uso de algoritmos seguros**: Preferiblemente utiliza HS256 o RS256, y revisa posibles vulnerabilidades de implementación.

## Ventajas y Desventajas de JWT

**Ventajas:**

* **Autocontenido**: Toda la información necesaria para la autenticación está en el token.
* **Sin estado**: No requiere almacenamiento en el servidor.
* **Escalabilidad**: Ideal para arquitecturas distribuidas.

**Desventajas:**

* **Revocación**: Difícil revocar un token antes de su expiración.
* **Seguridad**: Si alguien obtiene el token, puede usarlo hasta que expire.


JWT es una solución ampliamente utilizada para la autenticación y autorización en aplicaciones modernas debido a su eficiencia y facilidad de integración.

## Implementación básica en PHP

A continuación una implementación de JWT en PHP sin usar ninguna biblioteca externa. La idea es seguir el estándar de JWT y construir el token manualmente.

### Explicación del proceso

* **Encabezado (Header)**: Se codifica en Base64 el tipo de token (JWT) y el algoritmo de firma (HS256).
* **Payload (Carga de Datos)**: Incluye los datos del usuario y se codifica en Base64.
* **Firma (Signature)**: Se crea a partir de una clave secreta usando el algoritmo HMAC-SHA256 y se codifica en Base64.

### Ejemplo completo en PHP

```php

<?php
// Clave secreta para firmar el token
$secret_key = "mi_clave_secreta";

// Función para generar el JWT
function generarJWT($header, $payload, $secret_key) {
    // Codificar en Base64 URL el header y el payload
    $header_encoded = base64UrlEncode(json_encode($header));
    $payload_encoded = base64UrlEncode(json_encode($payload));
    
    // Crear la firma
    $signature = hash_hmac('sha256', "$header_encoded.$payload_encoded", $secret_key, true);
    $signature_encoded = base64UrlEncode($signature);
    
    // Combinar todos los elementos en el JWT
    return "$header_encoded.$payload_encoded.$signature_encoded";
}

// Función para verificar el JWT
function verificarJWT($jwt, $secret_key) {
    // Separar el token en sus tres partes
    list($header_encoded, $payload_encoded, $signature_encoded) = explode('.', $jwt);
    
    // Verificar la firma
    $signature = base64UrlEncode(hash_hmac('sha256', "$header_encoded.$payload_encoded", $secret_key, true));
    
    return ($signature === $signature_encoded);
}

// Función auxiliar para codificar en Base64 URL seguro
function base64UrlEncode($data) {
    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
}

// Datos para el JWT
$header = [
    'alg' => 'HS256',
    'typ' => 'JWT'
];

$payload = [
    'user_id' => 123,
    'username' => 'usuario123',
    'exp' => time() + 3600 // Expira en 1 hora
];

// Generar el JWT
$jwt = generarJWT($header, $payload, $secret_key);
echo "JWT generado: " . $jwt . "\n";

// Verificar el JWT
if (verificarJWT($jwt, $secret_key)) {
    echo "JWT válido\n";
} else {
    echo "JWT inválido\n";
}
?>
```

### Explicación del código

* Función `generarJWT`: Esta función crea un JWT tomando el header, payload, y secret_key.
        Primero codifica en Base64 URL el encabezado y los datos.
        Luego, crea la firma usando hash_hmac con el algoritmo HS256.
        Finalmente, une las tres partes (encabezado, datos, y firma) con puntos (.).

* Función `verificarJWT`: Toma el JWT completo y verifica su autenticidad.
        Separa el token en sus partes (header, payload, signature).
        Vuelve a calcular la firma usando el encabezado y los datos.
        Compara la firma calculada con la firma del JWT recibido; si coinciden, el JWT es válido.

    Función base64UrlEncode: Codifica los datos en Base64 en un formato seguro para URLs, reemplazando ciertos caracteres y eliminando el relleno =.

### Ejecución

Este script primero genera un JWT y lo muestra en pantalla. Luego, verifica la validez del JWT y, si es correcto, imprime "JWT válido".

### Consideraciones adicionales

* Expiración del token (exp): En el ejemplo, el campo exp en el payload indica el tiempo de expiración. Esto no se verifica automáticamente, así que debes agregar una validación en la función de verificación para comprobar si el token ha expirado.

```php
    function verificarJWT($jwt, $secret_key) {
        list($header_encoded, $payload_encoded, $signature_encoded) = explode('.', $jwt);
        $signature = base64UrlEncode(hash_hmac('sha256', "$header_encoded.$payload_encoded", $secret_key, true));

        if ($signature !== $signature_encoded) {
            return false;
        }

        // Decodificar el payload para verificar el tiempo de expiración
        $payload = json_decode(base64_decode($payload_encoded), true);
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return false; // Token expirado
        }

        return true; // Token válido
    }
```

Este ejemplo es una implementación básica, pero cumple con los principios fundamentales de los JWT.

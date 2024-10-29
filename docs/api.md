# API RESTful

La creación de una API RESTful (Representational State Transfer) es una metodología de diseño para servicios web que permite la comunicación entre sistemas de manera sencilla, escalable y eficiente. Las APIs RESTful están basadas en el protocolo HTTP y se utilizan para que los sistemas puedan intercambiar datos y realizar operaciones como crear, leer, actualizar y eliminar recursos. A continuación, te detallo cada uno de los conceptos clave, junto con buenas prácticas y recomendaciones esenciales para una correcta implementación.

## Conceptos Básicos
Recursos

    En REST, los recursos son las entidades principales con las que trabajaremos. Estos suelen representarse como entidades de negocio, por ejemplo, "usuarios", "productos" o "pedidos".
    Cada recurso se identifica mediante una URL única y, en general, los recursos se expresan en plural (ej. /usuarios, /productos).

Operaciones CRUD

    Los servicios RESTful se basan en las operaciones CRUD (Create, Read, Update, Delete), que se asocian con los métodos HTTP:
        POST: Crea un nuevo recurso.
        GET: Obtiene uno o más recursos.
        PUT: Actualiza un recurso existente.
        PATCH: Modifica parcialmente un recurso.
        DELETE: Elimina un recurso.

## Endpoints en REST

Un endpoint es una URL específica que expone una operación sobre un recurso. La estructura básica de un endpoint REST es:

bash

https://api.dominio.com/{recurso}/{id}

Estructura Típica de Endpoints

* GET /usuarios: Recupera una lista de usuarios.
* GET /usuarios/{id}: Recupera un usuario específico por su ID.
* POST /usuarios: Crea un nuevo usuario.
* PUT /usuarios/{id}: Actualiza todos los datos de un usuario específico.
* PATCH /usuarios/{id}: Modifica parcialmente los datos de un usuario.
* DELETE /usuarios/{id}: Elimina un usuario específico.

Buenas Prácticas para Diseñar Endpoints

* Nombres de recursos en plural: Es común usar el plural en los nombres de los recursos (/usuarios en lugar de /usuario) para facilitar la lectura.
* Evita verbos en los nombres de los endpoints: Los métodos HTTP ya indican la acción, por lo que no es necesario nombrar el endpoint como /crearUsuario; simplemente usa POST /usuarios.
* Anidación de recursos: Cuando un recurso está relacionado jerárquicamente con otro (ej. obtener pedidos de un usuario), puedes anidar los endpoints, por ejemplo, GET /usuarios/{id}/pedidos.
* Versionado: Para mantener compatibilidad en futuras versiones, se recomienda versionar las APIs (ej. /v1/usuarios), especialmente en aplicaciones públicas.

## Respuestas de la API

Las respuestas de la API contienen el estado de la operación y los datos solicitados. Generalmente, siguen el siguiente formato JSON:

```json
{
  "status": "success",
  "data": { /* datos del recurso */ },
  "message": "Operación exitosa"
}
```

Códigos de Estado HTTP

* **200 OK**: Operación exitosa, por ejemplo, en una solicitud GET.
* **201 Created**: Recurso creado exitosamente, se utiliza con POST.
* **204 No Content**: Operación exitosa sin contenido de respuesta, común con DELETE.
* **400 Bad Request**: Error en la solicitud, por ejemplo, datos inválidos.
* **401 Unauthorized**: Falta de autenticación para acceder al recurso.
* **403 Forbidden**: Autenticado pero sin permiso para acceder.
* **404 Not Found**: El recurso solicitado no existe.
* **500 Internal Server Error**: Error en el servidor al procesar la solicitud.

Estructura de las Respuestas

* **Datos**: Utiliza una estructura clara y concisa que facilite la lectura, por ejemplo, evita datos innecesarios o redundantes en las respuestas.
* **Mensajes de error específicos**: En caso de errores, proporciona mensajes detallados para que el cliente entienda qué salió mal.
* **Links para paginación**: En listados, ofrece soporte para paginación usando parámetros como limit y offset.

## Autenticación y Autorización

* La autenticación se encarga de verificar la identidad del usuario, mientras que la autorización determina si tiene permisos para realizar una acción.
* **Token JWT (JSON Web Token)**: Es un método común para la autenticación en APIs RESTful. El cliente envía el token en el encabezado Authorization con cada solicitud.
* **OAuth2**: Ideal para escenarios donde los usuarios necesitan autenticarse a través de un tercero (por ejemplo, Google o Facebook).

## Buenas Prácticas en APIs REST

* **Estandariza las Respuestas**: Estandariza el formato de las respuestas, para que sea fácil de manejar por los clientes y reducir errores.
* **Implementa Manejo de Errores**: Proporciona respuestas consistentes ante errores y devuelve mensajes informativos.
* **Usa HATEOAS (Hypermedia as the Engine of Application State)**: Añade enlaces a otros endpoints en las respuestas para facilitar la navegación entre recursos.
* **Documenta la API**: Utiliza herramientas como Swagger o OpenAPI para generar documentación y ayudar a los desarrolladores a entender cómo interactuar con tu API.
* **Optimiza para rendimiento**:
    * Usa caché para respuestas frecuentes, especialmente en peticiones GET.
    * Minimiza el tamaño de las respuestas incluyendo solo los campos necesarios.

## Herramientas de Prueba

* **Postman o Insomnia**: Para probar las rutas y verificar el funcionamiento de los endpoints.
* **JMeter**: Para pruebas de carga y rendimiento.

## Ejemplo de implementación básica

Para una API básica en PHP, usaremos el archivo index.php para manejar todas las solicitudes. La API responderá a los métodos `HTTP` (`GET`, `POST`, `PUT`, `DELETE`) y procesará las solicitudes en función del tipo de recurso y del ID, si está presente.


### Estructura del Proyecto

* `index.php`: archivo principal que contiene toda la lógica de la API.
* `usuarios.json`: archivo donde se almacenarán los datos de los usuarios en formato JSON, simulando una base de datos.

### Código de `index.php`

```php
<?php
// Archivo de datos JSON para simular la base de datos
define('DATABASE', 'usuarios.json');

// Función para cargar los usuarios desde el archivo JSON
function cargarUsuarios() {
    if (!file_exists(DATABASE)) {
        file_put_contents(DATABASE, json_encode([])); // Crear archivo vacío si no existe
    }
    return json_decode(file_get_contents(DATABASE), true);
}

// Función para guardar usuarios en el archivo JSON
function guardarUsuarios($usuarios) {
    file_put_contents(DATABASE, json_encode($usuarios));
}

// Configura los encabezados de respuesta
header("Content-Type: application/json");

// Obtener el método HTTP y el identificador de usuario (si existe)
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"), true);
$path = explode('/', trim($_SERVER['PATH_INFO'], '/'));

// Verifica que el recurso sea "usuarios"
if ($path[0] !== 'usuarios') {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Recurso no encontrado"]);
    exit();
}

// Obtiene el ID si está presente en la URL
$id = isset($path[1]) ? (int)$path[1] : null;

// Cargar la "base de datos" (archivo JSON)
$usuarios = cargarUsuarios();

switch ($method) {
    case 'GET':
        if ($id) {
            // Obtener un usuario específico
            $usuario = array_filter($usuarios, fn($u) => $u['id'] === $id);
            if (empty($usuario)) {
                http_response_code(404);
                echo json_encode(["status" => "error", "message" => "Usuario no encontrado"]);
            } else {
                echo json_encode(["status" => "success", "data" => array_values($usuario)[0]]);
            }
        } else {
            // Obtener todos los usuarios
            echo json_encode(["status" => "success", "data" => $usuarios]);
        }
        break;

    case 'POST':
        // Crear un nuevo usuario
        if (!isset($input['nombre']) || !isset($input['email'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Datos incompletos"]);
            exit();
        }
        $nuevoUsuario = [
            "id" => end($usuarios)['id'] + 1 ?? 1,
            "nombre" => $input['nombre'],
            "email" => $input['email']
        ];
        $usuarios[] = $nuevoUsuario;
        guardarUsuarios($usuarios);
        http_response_code(201);
        echo json_encode(["status" => "success", "data" => $nuevoUsuario]);
        break;

    case 'PUT':
        // Actualizar un usuario completo
        if (!$id || !isset($input['nombre']) || !isset($input['email'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Datos incompletos o ID no especificado"]);
            exit();
        }
        $actualizado = false;
        foreach ($usuarios as &$usuario) {
            if ($usuario['id'] === $id) {
                $usuario['nombre'] = $input['nombre'];
                $usuario['email'] = $input['email'];
                $actualizado = true;
                break;
            }
        }
        if ($actualizado) {
            guardarUsuarios($usuarios);
            echo json_encode(["status" => "success", "data" => $usuario]);
        } else {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Usuario no encontrado"]);
        }
        break;

    case 'DELETE':
        // Eliminar un usuario
        if (!$id) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "ID no especificado"]);
            exit();
        }
        $index = array_search($id, array_column($usuarios, 'id'));
        if ($index === false) {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Usuario no encontrado"]);
        } else {
            array_splice($usuarios, $index, 1);
            guardarUsuarios($usuarios);
            http_response_code(204); // No content
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["status" => "error", "message" => "Método no permitido"]);
        break;
}
?>
```

### Explicación del Código

1. Carga y Guardado de Usuarios:
* La función `cargarUsuarios()` carga el contenido del archivo usuarios.json.
* La función `guardarUsuarios()` guarda los datos en el archivo JSON.

2. Encabezados y Método `HTTP`:
* Configuramos el encabezado Content-Type para devolver datos en JSON.
* Capturamos el método `HTTP` (`GET`, `POST`, `PUT`, `DELETE`) para determinar la operación solicitada.

3. Procesamiento de Solicitudes por Método HTTP:
* **GET**:
    * **Sin ID**: devuelve todos los usuarios.
    * **Con ID**: devuelve un usuario específico.
* **POST**:
    * Crea un nuevo usuario si se proporcionan los datos nombre y email.
* **PUT**:
    * Actualiza todos los campos de un usuario específico. Verifica la existencia del ID y de los campos requeridos (nombre, email).
* **DELETE**:
    * Elimina un usuario específico usando su ID.


4. Gestión de Respuestas y Códigos de Estado HTTP:
* Los códigos de estado (`200`, `201`, `204`, `404`, `400`, `405`) y los mensajes se utilizan para comunicar el estado de la solicitud.
* En caso de éxito, se envía la información solicitada o un mensaje de éxito.

### Archivo `usuarios.json`

Inicialmente, el archivo usuarios.json estará vacío (`[]`). Cada vez que se agrega o modifica un usuario, se actualiza con los nuevos datos.

```json
[]
```

### Probando la API

Puedes probar esta API usando herramientas como `Postman` o `cURL` desde la terminal.

#### Ejemplos de Pruebas

* `GET` todos los usuarios:

```bash
curl -X GET http://localhost/mi-api/index.php/usuarios
```

* `POST` crear usuario:

```bash
curl -X POST http://localhost/mi-api/index.php/usuarios -d '{"nombre": "Juan", "email": "juan@example.com"}'
```

* `GET` usuario específico:

```bash
curl -X GET http://localhost/mi-api/index.php/usuarios/1
```

* `PUT` actualizar usuario:

```bash
curl -X PUT http://localhost/mi-api/index.php/usuarios/1 -d '{"nombre": "Juan Actualizado", "email": "juanactualizado@example.com"}'
```

* `DELETE` eliminar usuario:

```bash
curl -X DELETE http://localhost/mi-api/index.php/usuarios/1
```

### Consideraciones Finales

Este ejemplo implementa una `API RESTful` básica sin autenticación, sin manejo avanzado de errores y sin soporte para paginación o validaciones de datos complejas. Para un entorno de producción, se recomendaría usar un framework para simplificar estas tareas y añadir características avanzadas como autenticación, validación de datos y seguridad.
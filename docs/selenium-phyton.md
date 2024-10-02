# Selenium Python
`Selenium` es una herramienta poderosa para la automatización de navegadores web. Con `Selenium` en Python, puedes controlar un navegador como si estuvieras interactuando con él manualmente: puedes hacer clic en botones, rellenar formularios, extraer datos, navegar entre páginas, y mucho más. Es especialmente útil para la automatización de pruebas web y el web scraping (extracción de datos de sitios web).

## Configuración de entorno virtual en Python

1. Descargar el paquete:
```bash
sudo apt install python3-venv -y
```

2. Crear el entorno virtual en la carpeta que indiquemos, en este caso `ws`:
```bash
python3 -m venv ws
```
```bash
cd ws
```

3. Activar el entorno virtual:
```bash
source bin/activate
```

## ¿Cómo Funciona Selenium?

`Selenium` trabaja a través de controladores de navegador conocidos como `WebDrivers`, que actúan como un "puente" entre tu código `Python` y el navegador (como Chrome, Firefox, etc.). El código escrito en `Python` usa comandos de `Selenium` para interactuar con el `WebDriver`, que a su vez controla el navegador para realizar las acciones solicitadas.

## Pasos para usar Selenium con Python
1. Instalar Selenium

Primero, necesitas instalar la biblioteca Selenium en tu entorno de Python. Puedes hacerlo mediante pip:

```bash
pip install -U selenium
```

2. Instalar WebDriver

Selenium necesita un WebDriver para controlar un navegador específico. Por ejemplo:

    Chrome usa ChromeDriver
    Firefox usa GeckoDriver
    Edge usa EdgeDriver

Descarga el controlador correspondiente a tu navegador y asegúrate de que esté en tu ruta del sistema (o especifica su ruta en tu código).

3. Iniciar el Navegador

Después de instalar Selenium y el WebDriver, puedes iniciar el navegador y realizar tareas básicas como abrir una página web.

```python

from selenium import webdriver

# Inicializar WebDriver de Chrome
driver = webdriver.Chrome(executable_path='/ruta/a/chromedriver')

# Abrir una página web
driver.get("https://www.google.com")

# Imprimir el título de la página
print(driver.title)

# Cerrar el navegador
driver.quit()
```

4. Localización de Elementos Web

Una vez que el navegador esté abierto, puedes interactuar con los elementos de la página, como campos de texto, botones, enlaces, etc. Selenium te permite localizar estos elementos utilizando varias estrategias:

* **ID**: `find_element(By.ID, 'id_del_elemento')`
* **Nombre**: `find_element(By.NAME, 'nombre_del_elemento')`
* **Clase**: `find_element(By.CLASS_NAME, 'nombre_clase')`
* **XPath**: `find_element(By.XPATH, 'ruta_xpath')`
* **CSS Selector**: `find_element(By.CSS_SELECTOR, 'selector_css')`

Ejemplo: Realizar una Búsqueda en Google

```python

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys

# Inicializar WebDriver
driver = webdriver.Chrome()

# Abrir Google
driver.get("https://www.google.com")

# Localizar la caja de búsqueda por su nombre
search_box = driver.find_element(By.NAME, "q")

# Escribir en la caja de búsqueda
search_box.send_keys("Selenium Python")

# Presionar la tecla Enter
search_box.send_keys(Keys.RETURN)

# Imprimir el título de la página de resultados
print(driver.title)

# Cerrar el navegador
driver.quit()
```

## Interacción con Elementos Web

Puedes interactuar con los elementos de varias maneras:

* **Enviar texto a un campo de entrada**: `element.send_keys("texto")`
* **Hacer clic en un botón o enlace**: `element.click()`
* **Extraer texto o atributos de un elemento**: `element.text` o `element.get_attribute('nombre_atributo')`

Ejemplo: Clic en un Botón

```python
# Localizar un botón por su ID
boton = driver.find_element(By.ID, "boton_enviar")
boton.click()
```

## Esperas en Selenium

A veces, los elementos de una página no se cargan de inmediato, por lo que debes esperar. Selenium proporciona dos tipos de esperas:

* **Espera Implícita**: Espera un tiempo específico antes de lanzar una excepción si el elemento no está presente.

```python
driver.implicitly_wait(10)  # Espera hasta 10 segundos
```

* **Espera Explícita**: Espera a que una condición específica se cumpla, como que un elemento sea clicable.

```python
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

# Espera hasta que el botón sea clicable
button = WebDriverWait(driver, 10).until(
    EC.element_to_be_clickable((By.ID, "boton_enviar"))
)

button.click()
```

## Ejemplo Completo: Envío de un Formulario

Aquí tienes un ejemplo completo de cómo automatizar el envío de un formulario:

```python

from selenium import webdriver
from selenium.webdriver.common.by import By

# Iniciar WebDriver
driver = webdriver.Chrome()

# Abrir una página de ejemplo con un formulario
driver.get("https://www.ejemplo.com/formulario")

# Rellenar campos del formulario
nombre = driver.find_element(By.NAME, "nombre")
nombre.send_keys("Juan Pérez")

email = driver.find_element(By.NAME, "email")
email.send_keys("juan.perez@ejemplo.com")

# Enviar el formulario
submit_button = driver.find_element(By.ID, "enviar")
submit_button.click()

# Imprimir confirmación
print("Formulario enviado")

# Cerrar el navegador
driver.quit()
```

## Otras Funcionalidades de Selenium

* **Manejo de ventanas**: Puedes abrir, cambiar y cerrar ventanas de navegador.
* **Manejo de alertas**: Selenium te permite aceptar, rechazar o interactuar con cuadros de alerta de JavaScript.
* **Captura de pantalla**: Puedes capturar imágenes de la página actual.

```python
driver.save_screenshot('captura.png')
```

## Aplicaciones de Selenium
* **Automatización de pruebas**: Prueba aplicaciones web automáticamente en múltiples navegadores.
* **Web Scraping**: Extrae información de páginas web interactuando con los elementos de la página.
* **Automatización de tareas repetitivas**: Por ejemplo, llenado de formularios o navegación automática entre páginas.


## Ejemplo de acceso a una web `selen.py`

```python
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.options import Options
import time

#URL of CNN's homepage
cnn_url = "https://www.cnn.com/"

#Function to scrape headlines using Selenium
def scrape_with_selenium(url):
    options = Options()
    options.headless = False  # Set to True for headless mode
    driver = webdriver.Chrome(options=options)

    #Navigate to the webpage
    driver.get(url)

    #Interact with the webpage using Selenium
    # Example: Click on a button that loads more articles

    cookies_button = driver.find_element(By.ID, 'onetrust-accept-btn-handler')
    cookies_button.click();

    ## no_of_jobs = int(wd.find_element(By.CSS_SELECTOR, 'h1>span'))
    ## load_more_button = driver.find_element_by_css_selector('.load-more-button')
    ## load_more_button = driver.find_element(By.CSS_SELECTOR, '.load-more-button')
    ## load_more_button.click()

    #Allow time for dynamic content to load (you may need to use WebDriverWait for more robust waiting)
    time.sleep(3)

    #Extract and print headlines after loading more content
    ## headlines = driver.find_elements_by_css_selector('.card h3')
    headlines = driver.find_elements(By.TAG_NAME, 'h2')
    for headline in headlines:
        print(headline.text)

    #Close the browser window
    driver.quit()

#Scrape headlines using Selenium
scrape_with_selenium(cnn_url)
```

## Enlaces relacionados

1. Documentación oficial de Selenium Python
https://selenium-python.readthedocs.io/index.html

2. Comparativa Selenium vs. BeautifulSoup
https://medium.com/@udofiaetietop/webscrapping-beautifulsoup-or-selenium-3467edb3c0d9


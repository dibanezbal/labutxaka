# labutxaka

## Demo para portfolio y Netlify

Demo interactiva de LaButxaka para un portfolio de UX Engineer / Product Designer.
Conserva la identidad visual y las secciones Resumen, Movimientos, Cuentas y Categorías
del proyecto PHP. No incluye deudas, división de gastos, login ni conexión bancaria.

- Los datos iniciales son ficticios; las fechas se adaptan al mes actual.
- Permite crear, editar y eliminar movimientos, cuentas y categorías, y filtrar movimientos.
- Los cambios permanecen solo en memoria durante la visita. Recargar o reiniciar recupera la semilla.
- No envía los datos introducidos a un backend ni los guarda en el navegador.
- Shoelace y la fuente se descargan desde sus CDN, por lo que necesitan conexión.

### Probar la demo localmente

Requiere Node.js 22 o posterior, sin dependencias npm adicionales:

```sh
npm test
npm run build
npm start
```

Abre http://127.0.0.1:4173. Si el puerto está ocupado, usa `PORT=4174 npm start`.

### Publicar en Netlify

Conecta este repositorio al sitio existente https://labutxaka-demo.netlify.app/.
La configuración está en `netlify.toml`:

- Base directory: raíz del repositorio, vacía.
- Build command: `npm test && npm run build`.
- Publish directory: `dist`.
- Node.js: 22.

Elimina cualquier ajuste anterior que publique la raíz del repositorio o `app/`.
El build copia únicamente los archivos de `demo/`, estilos y cuatro recursos gráficos
seleccionados. No copia PHP, SQL, variables de entorno ni copias de seguridad.
También se puede subir manualmente la carpeta `dist` generada a Netlify.
La navegación utiliza hashes, por lo que no necesita reglas SPA de redirección.

### Privacidad y publicación en GitHub

Se han retirado los archivos de despliegue personal de ZimaOS, contenedores y scripts
de backup. Los `.env` y las copias de seguridad quedan excluidos por `.gitignore`.
La semilla SQL del proyecto PHP contiene únicamente registros ficticios y un usuario
sin contraseña válida; no se publica en Netlify.

Antes de un commit, revisa `git diff` y los archivos que añadirás. No incluyas bases de
datos reales ni credenciales. `.gitignore` no elimina archivos ya versionados ni borra
el historial. Si alguna credencial se publicó anteriormente, revócala o rótala antes
de valorar una limpieza del historial. El despliegue personal existente no se modifica.

---

## Aplicación PHP original

Aplicación web en PHP (MVC) para gestionar el control de gastos e ingresos a través de **movimientos**, **cuentas** y **categorías**, con un panel de **resumen**. La UI basada en **Shoelace** y estilos propios.

## Estructura del proyecto

- `index.php`: raíz del proyecto
- `app/index.php`: arranque interno
- `app/core/routes.php`: router (`?c=...&a=...`)
- `app/controllers/`: controladores
- `app/models/`: modelos (MySQLi)
- `app/views/`: vistas + templates
- `app/assets/`: CSS/JS/imagenes
  - `app/assets/js/components/`: componentes JS (p. ej. `card-list`, `modal`)
  - `app/assets/css/components/`: estilos por componente

## Requisitos

- PHP 8.x
- MySQL
- Extensiones PHP:
  - `mysqli`
- Servidor web (Apache/Nginx) o PHP built-in server

## Configuración

### Configuración de la base de datos

Configurar las credenciales en:

- `app/config/config.php`
- `app/config/database.php`

> Nota: el proyecto usa MySQLi y modelos que llaman a la conexión desde la capa `config`.

La contraseña de MySQL se obtiene exclusivamente de la variable de entorno `DB_PASS`.
La demo de Netlify no necesita esta configuración.

### Base de datos

Crea la base de datos y tablas necesarias (usuarios, cuentas, categorías, movimientos).  
El proyecto asume relaciones por `usuario_id` y claves foráneas (p. ej. movimientos > usuarios).

## Ejecutar en local

Desde la raíz del proyecto, según el puerto que tengas configurado:

```
php -S localhost:8000
```

```
php -S localhost:8888
```
Luego abre:

- http://localhost:8000/
- http://localhost:8888/

## Rutas principales

El router funciona con query params (?key=value):

- Login/Registro:
  - `index.php?c=usuarios&a=login`
  - `index.php?c=usuarios&a=signup`
- Movimientos:
  - `index.php?c=movimientos&a=index`
  - `index.php?c=movimientos&a=create`
  - `index.php?c=movimientos&a=save`
  - `index.php?c=movimientos&a=edit&id=$`
  - `index.php?c=movimientos&a=update`
- Resumen:
  - `index.php?c=movimientos&a=resumen`
- Cuentas:
  - `index.php?c=cuentas&a=index`
- Categorías:
  - `index.php?c=categorias&a=index`

## Desarrollo

### Estilos
- Los estilos están separados en varios archivos por componente o funcionalidad, importándolos en styles.css, con la estructura del layout y el reset basado en: https://piccalil.li/blog/a-modern-css-reset/:

- CSS principal: `app/assets/css/styles.css`
- Componentes: `app/assets/css/components/*.css`

### JS
- Script general: `app/assets/js/script.js`
- Componentes: `app/assets/js/components/*.js`


### Frontend
- Los componentes se han basado en Shoelace `sl-*` (inputs, dialogs, buttons) enlazando a través de CDN y personalizándolos para este proyecto.

### Imágenes e iconos
- Basados en Figma y en librerías como la propia Shoelace o 


### La estructura del MVC está basada en las plantillas de estos repositorios:
- https://github.com/informaticacba/plantilla-mvc-php/tree/master/mvc
- https://github.com/ivalshamkya/php-pdo-mvc/


## Comentarios
- Proyecto 3. Aplicación interactiva
- Grado en Técnicas de Interacción Digital y Multimedia - UOC
- Diciembre 2025

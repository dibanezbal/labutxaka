# labutxaka

Aplicación web en PHP (MVC) para gestionar **movimientos**, **cuentas** y **categorías**, con un panel de **resumen**. UI basada en **Shoelace** y estilos propios.

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

### 1 Configuración de la base de datos

Configurar las credenciales en:

- `app/config/config.php`
- `app/config/database.php`

> Nota: el proyecto usa MySQLi y modelos que llaman a la conexión desde la capa `config`.

### 2 Base de datos

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


### La estructura del MVC está basada en las plantillas de estos repositorios:
- https://github.com/informaticacba/plantilla-mvc-php/tree/master/mvc
- https://github.com/ivalshamkya/php-pdo-mvc/


## Comentarios
- Proyecto 3. Aplicación interactiva
- Grado en Técnicas de Interacción Digital y Multimedia - UOC
- Diciembre 2025

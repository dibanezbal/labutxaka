# labutxaka

Aplicación web en PHP (MVC) para gestionar **movimientos**, **cuentas** y **categorías**, con un panel de **resumen**. UI basada en **Shoelace** y estilos propios.

## Estructura del proyecto

- `index.php`: entrypoint (raíz del proyecto)
- `app/index.php`: bootstrap interno
- `app/core/routes.php`: router (`?c=...&a=...`)
- `app/controllers/`: controladores
- `app/models/`: modelos (MySQLi)
- `app/views/`: vistas + templates
- `app/assets/`: CSS/JS/imagenes
  - `app/assets/js/components/`: componentes JS (p. ej. `card-list`, `modal`)
  - `app/assets/css/components/`: estilos por componente

## Requisitos

- PHP 8.x
- MySQL/MariaDB
- Extensiones PHP:
  - `mysqli`
  - `mbstring` (recomendado)
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

Desde la raíz del proyecto:

```
php -S localhost:8000
```

o 

```
php -S localhost:8888
```


Luego abre:

- http://localhost:8000/
- http://localhost:8888/

## Rutas principales

El router funciona con query params:

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

## Frontend (Shoelace)

Se usan componentes de Shoelace `sl-*` (inputs, dialogs, buttons).  

## Desarrollo

### Estilos
- CSS principal: `app/assets/css/styles.css`
- Componentes: `app/assets/css/components/*.css`

### JS
- Script general: `app/assets/js/script.js`
- Componentes: `app/assets/js/components/*.js`

###
- La estructura del MVC está basada en las plantillas de estos repositorios:
https://github.com/informaticacba/plantilla-mvc-php/tree/master/mvc
https://github.com/ivalshamkya/php-pdo-mvc/


###
# Proyecto 3. Aplicación interactiva
# Grado en Técnicas de Interacción Digital y Multimedia - UOC
# Diciembre 2025

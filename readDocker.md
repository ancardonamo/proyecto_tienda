# Manual de Despliegue con Docker - Sistema de Microservicios

## Requisitos Previos
- **Firebase**: El archivo `serviceAccountKey.json` debe estar dentro de la carpeta `express-firebase`.
- **Comando Unificado**: Para encender todo el sistema:
  `docker-compose up --build -d`

## Paso 1: API Gateway (Laravel)
- **Puerto**: 8080
- **Configuración**: El contenedor usa automáticamente `php artisan serve --port=8080`.
- **Base de Datos**: Se conecta al servicio `db-mysql` usando la base de datos `proyecto_laravel`.
- **Comando de Tablas**: `docker-compose exec api-gateway php artisan migrate`

## Paso 2: Productos (Django)
- **Puerto**: 8000
- **Configuración**: El contenedor usa `python manage.py runserver 0.0.0.0:8000`.
- **Base de Datos**: Se conecta al servicio `db-mysql` usando la base de datos `proyecto_django`.
- **Comando de Tablas**: `docker-compose exec productos-django python manage.py migrate`

## Paso 3: Pedidos (Flask)
- **Puerto**: 5000
- **Configuración**: El contenedor usa `flask run --port=5000`.
- **Base de Datos**: Se conecta al servicio `db-mysql` usando la base de datos `proyecto_pedidos`.
- **Comando de Tablas**: `docker-compose exec pedidos-flask flask db upgrade`

## Paso 4: Inventario (Flask)
- **Puerto**: 5001
- **Configuración**: El contenedor usa `flask run --port=5001`.
- **Base de Datos**: Se conecta al servicio `db-postgres` (PostgreSQL).
- **Comando de Tablas**: `docker-compose exec inventario-flask flask db upgrade`

## Paso 5: Usuarios (Express)
- **Puerto**: 3000
- **Configuración**: El contenedor usa `npm start`.
- **Requisito**: El archivo de credenciales de Firebase debe estar dentro del contenedor (se copia automáticamente si está en la carpeta del microservicio).

## Credenciales de Bases de Datos (Docker)
- **Usuario**: root
- **Contraseña**: root_password
- **Hosts**: `db-mysql` para MySQL y `db-postgres` para PostgreSQL.


## Para reiniciar todo
- `docker-compose down -v`
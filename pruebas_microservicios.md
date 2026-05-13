# Guía de Pruebas de Microservicios (Thunder Client / Postman)

Este documento detalla el flujo de pruebas para validar el sistema de microservicios a través del API Gateway.

---

# 1. Fase de Autenticación (Laravel Gateway)

> **Importante:**  
> Tras el login, copia el `access_token` y úsalo como **Bearer Token** en todas las peticiones siguientes.

## Registro de Usuario

- **Método:** `POST`
- **URL:** `http://localhost:8080/api/register`

### Body (JSON)

```json
{
    "name": "Andres Cardona",
    "email": "andres@example.com",
    "password": "root_password",
    "question": "Nombre de mi mascota",
    "answer": "Firulais"
}
```

---

## Login (Obtener Token)

- **Método:** `POST`
- **URL:** `http://localhost:8080/api/login`

### Body (JSON)

```json
{
    "email": "andres@example.com",
    "password": "root_password"
}
```

---

## Restablecer Contraseña

- **Método:** `POST`
- **URL:** `http://localhost:8080/api/password_reset`

### Body (JSON)

```json
{
    "email": "andres@example.com",
    "pregunta_seguridad": "respuesta",
    "new_password": "nueva_password_123"
}
```

---

# 2. Microservicio: Usuarios (Express -> Firebase)

Gestiona registros en la nube de Firebase.

## Crear Usuario

- **Método:** `POST`
- **URL:** `http://localhost:8080/api/usuarios`

### Body (JSON)

```json
{
  "name": "Juan Perez",
  "email": "juan@example.com"
}
```

---

## Actualizar Usuario

- **Método:** `PUT`
- **URL:** `http://localhost:8080/api/usuarios/{id_firebase}`

### Body (JSON)

```json
{
  "name": "Juan Perez",
  "email": "juan@example.com"
}
```

---

# 3. Microservicio: Productos (Django -> MySQL)

Operaciones sobre la base de datos `proyecto_django`.

## Agregar Producto

- **Método:** `POST`
- **URL:** `http://localhost:8080/api/productos`

### Body (JSON)

```json
{
    "nombre": "Monitor Gamer 24p",
    "descripcion": "144Hz, 1ms respuesta",
    "precio": 150.00
}
```

---

## Editar Producto

- **Método:** `PUT`
- **URL:** `http://localhost:8000/api/productos/{id}`

### Body (JSON)

```json
{
    "precio": 135.50,
    "stock": 15
}
```

---

# 4. Microservicio: Pedidos (Flask -> MySQL)

Gestión de órdenes en `proyecto_pedidos`.

## Registrar Pedido

- **Método:** `POST`
- **URL:** `http://localhost:8080/api/pedidos`

### Body (JSON)

```json
{
    "usuario_id": 1,
    "total": 275.00
}
```

---

## Actualizar Estado de Pedido

- **Método:** `PUT`
- **URL:** `http://localhost:8080/api/pedidos/{id}`

### Body (JSON)

```json
{
    "estado": "Pagado"
}
```

---

# 5. Microservicio: Inventario (Flask -> PostgreSQL)

Control de stock en la base de datos `mydatabase`.

## Registrar Entrada de Inventario

- **Método:** `POST`
- **URL:** `http://localhost:8080/api/inventario`

### Body (JSON)

```json
{
  "producto_id": 1,
  "cantidad": 50,
  "ubicacion": "Bodega Norte"
}
```



---

## Ajustar Inventario

- **Método:** `PUT`
- **URL:** `http://localhost:8080/api/inventario/{id}`

### Body (JSON)

```json
{
  "cantidad": 52,
  "ubicacion": "Bodega Norte"
}
```

---

# 6. Endpoints de Consulta (GET) y Eliminación (DELETE)

Para estos endpoints no necesitas enviar un cuerpo JSON, solo el ID en la URL y el Bearer Token.

| Microservicio | Consultar Todos | Consultar por ID | Eliminar |
|---|---|---|---|
| Usuarios | `GET /api/usuarios` | `GET /api/usuarios/{id}` | `DELETE /api/usuarios/{id}` |
| Productos | `GET /api/productos` | `GET /api/productos/{id}` | `DELETE /api/productos/{id}` |
| Pedidos | `GET /api/pedidos` | `GET /api/pedidos/{id}` | `DELETE /api/pedidos/{id}` |
| Inventario | `GET /api/inventario` | `GET /api/inventario/{id}` | `DELETE /api/inventario/{id}` |

---

# 7. Cierre de Sesión

- **Método:** `POST`
- **URL:** `http://localhost:8080/api/logout`


# docker compose exec api-gateway ./vendor/bin/phpunit --filter MicroservicesTest --testdox

## 1. Detener y borrar los contenedores actuales
docker compose down

# 2. Levantar y forzar el "build" para que lea el nuevo MicroservicesTest.php
docker compose up -d --build

# 3. Instalar dependencias (por si acaso faltan en el vendor)
docker compose exec api-gateway composer install

# 4. Generar la llave de la aplicación
docker compose exec api-gateway php artisan key:generate

# 5. Limpiar toda la caché de Laravel
docker compose exec api-gateway php artisan config:clear
docker compose exec api-gateway php artisan cache:clear
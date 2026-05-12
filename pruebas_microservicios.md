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
    "password_confirmation": "root_password"
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
- **URL:** `http://localhost:3000/api/usuarios`

### Body (JSON)

```json
{
    "nombre": "Juan Perez",
    "email": "juan@example.com",
    "rol": "cliente"
}
```

---

## Actualizar Usuario

- **Método:** `PUT`
- **URL:** `http://localhost:3000/api/usuarios/{id_firebase}`

### Body (JSON)

```json
{
    "nombre": "Juan Perez Actualizado",
    "rol": "admin"
}
```

---

# 3. Microservicio: Productos (Django -> MySQL)

Operaciones sobre la base de datos `proyecto_django`.

## Agregar Producto

- **Método:** `POST`
- **URL:** `http://localhost:8000/api/productos`

### Body (JSON)

```json
{
    "nombre": "Monitor Gamer 24p",
    "precio": 150.00,
    "descripcion": "144Hz, 1ms respuesta",
    "stock": 20
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
- **URL:** `http://localhost:5000/api/pedidos`

### Body (JSON)

```json
{
    "producto_id": 1,
    "cantidad": 2,
    "total": 271.00,
    "estado": "pendiente"
}
```

---

## Actualizar Estado de Pedido

- **Método:** `PUT`
- **URL:** `http://localhost:5000/api/pedidos/{id}`

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
- **URL:** `http://localhost:5001/api/inventario`

### Body (JSON)

```json
{
    "name": "Lote Monitores Mayo",
    "email": "bodega@tienda.com"
}
```

> **Nota:**  
> El script actual usa `name` y `email` como campos de ejemplo para la tabla `users` en PostgreSQL.

---

## Ajustar Inventario

- **Método:** `PUT`
- **URL:** `http://localhost:5001/api/inventario/{id}`

### Body (JSON)

```json
{
    "name": "Lote Actualizado",
    "email": "supervisor@tienda.com"
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



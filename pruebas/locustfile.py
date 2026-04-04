from locust import HttpUser, task, between
import random
import string

def random_string(length=8):
    return ''.join(random.choices(string.ascii_lowercase, k=length))

class TiendaVirtualUser(HttpUser):
    wait_time = between(1, 3)
    
    #Como estamos usando una API GATEWAY esto hará que el locust se pueda autenticar para que todo funcione.
    token = None

    # ==========================================
    # 1. AUTH Y USUARIOS LOCALES (LARAVEL - MySQL)
    # ==========================================

    def on_start(self):
        """
        Este método se ejecuta UNA VEZ por cada usuario simulado al iniciar.
        Hace el login y guarda el token para usarlo en el resto de peticiones.
        """
        data = {
            "email": "juan.perez@example.com", 
            "password": "password123"
        }
        
        # Petición de login al API Gateway
        response = self.client.post("/api/login", json=data)
        
        if response.status_code == 200:
            # Extraemos el token del JSON. Busca "access_token"
            json_response = response.json()
            self.token = json_response.get("access_token")
            print(f"Login exitoso para {data['email']}")
        else:
            print(f"Fallo el login. Status: {response.status_code}. Respuesta: {response.text}")

    def get_auth_header(self):
        """Genera la cabecera de autorización si hay un token válido"""
        if self.token:
            return {"Authorization": f"Bearer {self.token}"}
        return {}

    # ==========================================
    # 2. MICROSERVICIO: USUARIOS (EXPRESS - Firebase)
    # ==========================================
    @task(3)
    def get_usuarios_firebase(self):
        self.client.get("/api/usuarios", headers=self.get_auth_header())

    @task(1)
    def create_usuario_firebase(self):
        data = {
            "name": "Usuario Firebase",
            "email": f"firebase_{random_string()}@correo.com"
        }
        self.client.post("/api/usuarios", json=data, headers=self.get_auth_header())

    @task(1)
    def update_usuario_firebase(self):
        data = {
            "name": "Usuario Firebase Editado"
        }
        # Usando el ID que indicaste
        self.client.put("/api/usuarios/-OpK973GEslsdPV9YsgX", json=data, headers=self.get_auth_header()) 

    # ==========================================
    # 3. MICROSERVICIO: PRODUCTOS (DJANGO - MySQL)
    # ==========================================
    @task(4)
    def get_productos(self):
        self.client.get("/api/productos", headers=self.get_auth_header())

    @task(1)
    def create_producto(self):
        data = {
            "nombre": "Producto Locust",
            "descripcion": "Descripción generada automáticamente por la prueba de carga",
            "precio": 15000.00
        }
        self.client.post("/api/productos", json=data, headers=self.get_auth_header())

    @task(1)
    def update_producto(self):
        data = {
            "nombre": "Producto Actualizado",
            "descripcion": "Descripción actualizada",
            "precio": 16500.00
        }
        self.client.put("/api/productos/1", json=data, headers=self.get_auth_header())

    # ==========================================
    # 4. MICROSERVICIO: PEDIDOS (FLASK - MySQL)
    # ==========================================
    @task(3)
    def get_pedidos(self):
        self.client.get("/api/pedidos", headers=self.get_auth_header())

    @task(1)
    def create_pedido(self):
        data = {
            "usuario_id": 1, 
            "total": 45000.50,
            "estado": "Pendiente"
        }
        self.client.post("/api/pedidos", json=data, headers=self.get_auth_header())

    @task(1)
    def update_pedido(self):
        data = {
            "estado": "Pagado"
        }
        self.client.put("/api/pedidos/1", json=data, headers=self.get_auth_header())

    # ==========================================
    # 5. MICROSERVICIO: INVENTARIO (FLASK - PostgreSQL)
    # ==========================================
    @task(3)
    def get_inventario(self):
        self.client.get("/api/inventario", headers=self.get_auth_header())

    @task(1)
    def create_inventario(self):
        data = {
            "producto_id": 1,
            "cantidad": 100,
            "ubicacion": "Bodega Norte"
        }
        self.client.post("/api/inventario", json=data, headers=self.get_auth_header())

    @task(1)
    def update_inventario(self):
        data = {
            "cantidad": 95
        }
        self.client.put("/api/inventario/1", json=data, headers=self.get_auth_header())
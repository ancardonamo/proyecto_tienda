from locust import HttpUser, task, between
import random
import string

def random_string(length=8):
    """Genera una cadena de texto aleatoria para los correos"""
    return ''.join(random.choices(string.ascii_lowercase, k=length))

class LaravelAuthUser(HttpUser):
    # Simula el tiempo de espera de un usuario real entre clics
    wait_time = between(1, 3)
    
    token = None

    def get_auth_header(self):
        """Devuelve el header de autorización si el usuario tiene un token"""
        if self.token:
            return {"Authorization": f"Bearer {self.token}"}
        return {}

    # ==========================================
    # PRUEBAS PARA LARAVEL (AUTH)
    # ==========================================

    @task(1)
    def test_register(self):
        """Prueba de estrés para la creación de usuarios"""
        email_random = f"carga_{random_string(6)}@correo.com"
        data = {
            "name": "Usuario Locust",
            "email": email_random,
            "password": "password123",
            "password_confirmation": "password123",
            "question": "¿Cuál es el nombre de tu primera mascota?",
            "answer": "Firulais"
        }
        self.client.post("/api/register", json=data)

    @task(2)
    def test_login(self):
        """Prueba de estrés para el inicio de sesión y obtención de token"""
        data = {
            "email": "juan.perez@example.com", 
            "password": "password123"
        }
        
        response = self.client.post("/api/login", json=data)
        
        # Si el login es exitoso, guardamos el token para poder probar el logout luego
        if response.status_code == 200:
            json_response = response.json()
            self.token = json_response.get("access_token")

    @task(1)
    def test_password_reset(self):
        """Prueba para el restablecimiento de contraseña"""
        data = {
            "email": "juan.perez@example.com",
            "answer": "Firulais", 
            "password": "password123", 
            "password_confirmation": "password123"
        }
        self.client.post("/api/password_reset", json=data)

    @task(1)
    def test_logout(self):
        """Prueba para cerrar sesión (requiere estar logueado)"""
        # Solo intenta hacer logout si previamente consiguió un token en test_login
        if self.token:
            with self.client.post("/api/logout", headers=self.get_auth_header(), catch_response=True) as response:
                if response.status_code in [200, 204]:
                    # Borramos el token de la memoria porque la sesión ya se cerró
                    self.token = None 
                    response.success()
                else:
                    response.failure(f"Fallo al hacer logout. Status: {response.status_code}")
<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class MicroservicesTest extends TestCase
{
    // Crea las tablas en la RAM antes de cada test y las borra al terminar
    use RefreshDatabase;
    // Evita redirecciones al login y peticiones de tokens
    use WithoutMiddleware;

    /** 1. Prueba: Registro de Usuario (Auth Gateway) */
    public function test_auth_register_success()
    {
        $response = $this->post('/api/register', [
            "name" => "Andres Cardona",
            "email" => "andres@example.com",
            "password" => "root_password",
            "question" => "Nombre de mi mascota",
            "answer" => "Firulais"
        ]);

        $response->assertStatus(200);
    }

    /** 2. Prueba: Login (Auth Gateway) */
    public function test_auth_login_success()
    {
        // Registramos primero al usuario en la RAM
        $this->post('/api/register', [
            "name" => "Andres",
            "email" => "andres@example.com",
            "password" => "password123",
            "question" => "mascota",
            "answer" => "perro"
        ]);

        $response = $this->post('/api/login', [
            "email" => "andres@example.com",
            "password" => "password123"
        ]);

        $response->assertStatus(200);
    }

    /** 3. Microservicio Usuarios: Crear Usuario (Express -> Firebase) */
    public function test_create_user_express_firebase()
    {
        Http::fake(['http://usuarios-express:3000/*' => Http::response(['id' => 'fb123', 'name' => 'Juan Perez'], 201)]);

        $response = $this->post('/api/usuarios', [
            "name" => "Juan Perez",
            "email" => "juan@example.com"
        ]);

        $response->assertStatus(201);
        $this->assertEquals("Juan Perez", $response->json()['name']);
    }

    /** 4. Microservicio Productos: Agregar Producto (Django -> MySQL) */
    public function test_add_product_django_mysql()
    {
        Http::fake(['http://productos-django:8000/*' => Http::response(['id' => 1, 'nombre' => 'Monitor Gamer 24p'], 201)]);

        $response = $this->post('/api/productos', [
            "nombre" => "Monitor Gamer 24p",
            "descripcion" => "144Hz",
            "precio" => 150.00
        ]);

        $response->assertStatus(201);
        $this->assertNotNull($response->json()['id']);
    }

    /** 5. Microservicio Productos: Listado de productos (Django) */
    public function test_get_all_products_count()
    {
        Http::fake(['http://productos-django:8000/*' => Http::response([['id' => 1], ['id' => 2]], 200)]);

        $response = $this->get('/api/productos');

        $this->assertCount(2, $response->json());
    }

    /** 6. Microservicio Pedidos: Registrar Pedido (Flask -> MySQL) */
    public function test_register_order_flask_mysql()
    {
        Http::fake(['http://pedidos-flask:5000/*' => Http::response(['id' => 1, 'total' => 275.00], 201)]);

        $response = $this->post('/api/pedidos', [
            "usuario_id" => 1,
            "total" => 275.00
        ]);

        $response->assertStatus(201);
    }

    /** 7. Microservicio Pedidos: Actualizar Estado */
    public function test_update_order_status_gateway()
    {
        Http::fake(['http://pedidos-flask:5000/*' => Http::response(['estado' => 'Pagado'], 200)]);

        $response = $this->put('/api/pedidos/1', ["estado" => "Pagado"]);

        $this->assertEquals("Pagado", $response->json()['estado']);
    }

    /** 8. Microservicio Inventario: Registrar Entrada (Flask -> PostgreSQL) */
    public function test_register_inventory_flask_postgres()
    {
        Http::fake(['http://inventario-flask:5001/*' => Http::response(['id' => 1], 201)]);

        $response = $this->post('/api/inventario', [
            "producto_id" => 1,
            "cantidad" => 50,
            "ubicacion" => "Bodega Norte"
        ]);

        $response->assertStatus(201);
    }

    /** 9. Microservicio Inventario: Ajustar Inventario */
    public function test_adjust_inventory_flask_postgres()
    {
        Http::fake(['http://inventario-flask:5001/*' => Http::response(['cantidad' => 52], 200)]);

        $response = $this->put('/api/inventario/1', [
            "cantidad" => 52,
            "ubicacion" => "Bodega Norte"
        ]);

        $this->assertEquals(52, $response->json()['cantidad']);
    }

    /** 10. Microservicio Productos: Eliminar Producto */
    public function test_delete_product_gateway()
    {
        Http::fake(['http://productos-django:8000/*' => Http::response(null, 204)]);
        
        $response = $this->delete('/api/productos/1');

        $this->assertEquals(204, $response->status());
    }
}
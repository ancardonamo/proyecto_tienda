from flask import jsonify, request
from models import Inventario, db

def register_routes(app):
    @app.route('/api/inventario', methods=['GET'])
    def get_inventarios():
        inventarios = Inventario.query.all()
        return jsonify([i.to_dict() for i in inventarios])

    @app.route('/api/inventario/<int:id>', methods=['GET'])
    def get_inventario(id):
        inv = Inventario.query.get_or_404(id)
        return jsonify(inv.to_dict())

    @app.route('/api/inventario', methods=['POST'])
    def create_inventario():
        data = request.get_json()
        nuevo_inv = Inventario(
            producto_id=data['producto_id'],
            cantidad=data['cantidad'],
            ubicacion=data['ubicacion']
        )
        db.session.add(nuevo_inv)
        db.session.commit()
        return jsonify(nuevo_inv.to_dict()), 201

    @app.route('/api/inventario/<int:id>', methods=['PUT'])
    def update_inventario(id):
        inv = Inventario.query.get_or_404(id)
        data = request.get_json()
        inv.cantidad = data.get('cantidad', inv.cantidad)
        inv.ubicacion = data.get('ubicacion', inv.ubicacion)
        db.session.commit()
        return jsonify(inv.to_dict())

    @app.route('/api/inventario/<int:id>', methods=['DELETE'])
    def delete_inventario(id):
        inv = Inventario.query.get_or_404(id)
        db.session.delete(inv)
        db.session.commit()
        return jsonify({'message': 'Eliminado'})
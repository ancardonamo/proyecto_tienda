from flask import jsonify, request
from models import Pedido, db

def register_routes(app):
    @app.route('/api/pedidos', methods=['GET'])
    def get_pedidos():
        pedidos = Pedido.query.all()
        return jsonify([p.to_dict() for p in pedidos])

    @app.route('/api/pedidos/<int:id>', methods=['GET'])
    def get_pedido(id):
        pedido = Pedido.query.get_or_404(id)
        return jsonify(pedido.to_dict())

    @app.route('/api/pedidos', methods=['POST'])
    def create_pedido():
        data = request.get_json()
        nuevo_pedido = Pedido(
            usuario_id=data['usuario_id'],
            total=data['total'],
            estado=data.get('estado', 'Pendiente')
        )
        db.session.add(nuevo_pedido)
        db.session.commit()
        return jsonify(nuevo_pedido.to_dict()), 201

    @app.route('/api/pedidos/<int:id>', methods=['PUT'])
    def update_pedido(id):
        pedido = Pedido.query.get_or_404(id)
        data = request.get_json()
        pedido.estado = data.get('estado', pedido.estado)
        db.session.commit()
        return jsonify(pedido.to_dict())

    @app.route('/api/pedidos/<int:id>', methods=['DELETE'])
    def delete_pedido(id):
        pedido = Pedido.query.get_or_404(id)
        db.session.delete(pedido)
        db.session.commit()
        return jsonify({'message': 'Eliminado'})
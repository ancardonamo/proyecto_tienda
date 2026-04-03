from flask_sqlalchemy import SQLAlchemy
from datetime import datetime

db = SQLAlchemy()

class Pedido(db.Model):
    __tablename__ = 'pedidos'
    
    id = db.Column(db.Integer, primary_key=True)
    usuario_id = db.Column(db.String(100), nullable=False) 
    total = db.Column(db.Float, nullable=False)
    estado = db.Column(db.String(20), default='Pendiente')
    fecha = db.Column(db.DateTime, default=datetime.utcnow)

    def to_dict(self):
        return {
            'id': self.id,
            'usuario_id': self.usuario_id,
            'total': self.total,
            'estado': self.estado,
            'fecha': self.fecha.isoformat()
        }
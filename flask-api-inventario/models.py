from flask_sqlalchemy import SQLAlchemy

db = SQLAlchemy()

class Inventario(db.Model):
    __tablename__ = 'inventarios'

    id = db.Column(db.Integer, primary_key=True)
    producto_id = db.Column(db.Integer, nullable=False)
    cantidad = db.Column(db.Integer, nullable=False, default=0)
    ubicacion = db.Column(db.String(50), nullable=False)

    def to_dict(self):
        return {
            'id': self.id,
            'producto_id': self.producto_id,
            'cantidad': self.cantidad,
            'ubicacion': self.ubicacion
        }
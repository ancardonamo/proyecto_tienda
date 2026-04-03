from flask import Flask
from flask_migrate import Migrate
from flask_cors import CORS
from config import Config
from models import db
import routes

app = Flask(__name__)
app.config.from_object(Config)

db.init_app(app)
migrate = Migrate(app, db)
CORS(app)

routes.register_routes(app)

if __name__ == '__main__':
    # Puerto 5000 para pedidos
    app.run(debug=True, port=5001)
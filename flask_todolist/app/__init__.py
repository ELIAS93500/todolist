from flask import Flask
from flask_sqlalchemy import SQLAlchemy

db = SQLAlchemy()  # ⚠️ Déclare db ici, avant d'importer les routes

def create_app():
    app = Flask(__name__)
    app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///todolist.db'  # ou MySQL selon ton setup
    app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

    db.init_app(app)  # Initialise la db avec l'app

    # Importer les modèles ici pour éviter la circular import
    from app import models

    # Importer et enregistrer les routes / blueprints
    from app.routes import main
    app.register_blueprint(main)

    return app

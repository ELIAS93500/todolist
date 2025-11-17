# app/models.py
from datetime import datetime
from app import db  # <-- Utiliser l'instance déjà créée dans __init__.py

class Task(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(100), nullable=False)
    description = db.Column(db.String(200))
    deadline = db.Column(db.DateTime)
    priority = db.Column(db.String(10))
    status = db.Column(db.String(20), default="en attente")
    created_at = db.Column(db.DateTime, default=datetime.utcnow)

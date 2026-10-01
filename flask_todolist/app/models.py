from datetime import datetime
from app import db

class Task(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(100), nullable=False)
    description = db.Column(db.String(200))
    deadline = db.Column(db.DateTime)
    start_time = db.Column(db.String(10), nullable=True) # Ex: "09:00"
    end_time = db.Column(db.String(10), nullable=True)   # Ex: "10:30"
    priority = db.Column(db.String(10))
    status = db.Column(db.String(20), default="en attente")
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
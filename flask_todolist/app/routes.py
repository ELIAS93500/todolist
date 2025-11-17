from flask import Blueprint, render_template, request, redirect, url_for
from app.models import db, Task
from datetime import datetime

main = Blueprint('main', __name__)

# Dashboard - vue globale
@main.route('/')
@main.route('/dashboard')
def dashboard():
    tasks = Task.query.order_by(Task.deadline).all()
    return render_template('dashboard.html', tasks=tasks)

# Page Tâches - gestion détaillée
@main.route('/tasks')
def tasks_page():
    tasks = Task.query.order_by(Task.deadline).all()
    return render_template('tasks.html', tasks=tasks)

# Ajouter une tâche
@main.route('/add_task', methods=['POST'])
def add_task():
    title = request.form.get('title')
    description = request.form.get('description')
    deadline_str = request.form.get('deadline')
    priority = request.form.get('priority')

    deadline = datetime.strptime(deadline_str, '%Y-%m-%d') if deadline_str else None

    new_task = Task(
        title=title,
        description=description,
        deadline=deadline,
        priority=priority,
        status='en attente'  # Par défaut la tâche est en attente
    )
    db.session.add(new_task)
    db.session.commit()
    return redirect(request.referrer or url_for('main.tasks_page'))

# Supprimer une tâche
@main.route('/delete_task/<int:id>')
def delete_task(id):
    task = Task.query.get_or_404(id)
    db.session.delete(task)
    db.session.commit()
    return redirect(request.referrer or url_for('main.tasks_page'))

# Valider une tâche
@main.route('/validate_task/<int:id>')
def validate_task(id):
    task = Task.query.get_or_404(id)
    task.status = 'validée'
    db.session.commit()
    return redirect(request.referrer or url_for('main.tasks_page'))

# Mettre une tâche en attente
@main.route('/pending_task/<int:id>')
def pending_task(id):
    task = Task.query.get_or_404(id)
    task.status = 'en attente'
    db.session.commit()
    return redirect(request.referrer or url_for('main.tasks_page'))

# Modifier une tâche
@main.route('/edit_task/<int:id>', methods=['GET', 'POST'])
def edit_task(id):
    task = Task.query.get_or_404(id)
    if request.method == 'POST':
        task.title = request.form.get('title')
        task.description = request.form.get('description')
        deadline_str = request.form.get('deadline')
        task.deadline = datetime.strptime(deadline_str, '%Y-%m-%d') if deadline_str else None
        task.priority = request.form.get('priority')
        db.session.commit()
        return redirect(url_for('main.tasks_page'))
    return render_template('edit_task.html', task=task)

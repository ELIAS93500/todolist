from flask import Blueprint, render_template, request, redirect, url_for, jsonify
from app.models import db, Task
from datetime import datetime

main = Blueprint('main', __name__)

@main.route('/')
@main.route('/overview')
def overview():
    tasks = Task.query.all()
    completed = [t for t in tasks if t.status == 'validée']
    high_priority = [t for t in tasks if t.priority == 'Haute']
    return render_template('overview.html', tasks=tasks, completed=completed, high_priority=high_priority)

@main.route('/board')
@main.route('/dashboard')
def board():
    tasks = Task.query.order_by(Task.deadline).all()
    pending_tasks = [t for t in tasks if t.status != 'validée']
    completed_tasks = [t for t in tasks if t.status == 'validée']
    return render_template('dashboard.html', pending_tasks=pending_tasks, completed_tasks=completed_tasks)

@main.route('/list')
@main.route('/tasks')
def tasks_page():
    tasks = Task.query.order_by(Task.deadline).all()
    return render_template('tasks.html', tasks=tasks)

@main.route('/timeline')
def timeline():
    tasks = Task.query.filter(Task.deadline != None).order_by(Task.deadline).all()
    return render_template('timeline.html', tasks=tasks)

@main.route('/calendar')
def calendar_view():
    return render_template('calendar.html')

# API JSON pour FullCalendar avec gestion des heures précises
@main.route('/api/tasks-events')
def tasks_events():
    tasks = Task.query.filter(Task.deadline != None).all()
    events = []
    
    for t in tasks:
        date_str = t.deadline.strftime('%Y-%m-%d')
        
        # Si une heure de début est spécifiée
        if t.start_time:
            start_iso = f"{date_str}T{t.start_time}:00"
            end_iso = f"{date_str}T{t.end_time}:00" if t.end_time else f"{date_str}T{t.start_time}:00"
            all_day = False
        else:
            start_iso = date_str
            end_iso = date_str
            all_day = True

        color = '#ef4444' if t.priority == 'Haute' else ('#ea580c' if t.priority == 'Moyenne' else '#2563eb')

        events.append({
            'id': t.id,
            'title': t.title,
            'start': start_iso,
            'end': end_iso,
            'allDay': all_day,
            'color': color,
            'textColor': '#ffffff',
            'status': t.status
        })
    return jsonify(events)

@main.route('/analytics')
def analytics_dashboard():
    tasks = Task.query.all()
    total = len(tasks)
    completed_count = len([t for t in tasks if t.status == 'validée'])
    pending_count = total - completed_count
    high_count = len([t for t in tasks if t.priority == 'Haute'])
    med_count = len([t for t in tasks if t.priority == 'Moyenne'])
    low_count = len([t for t in tasks if t.priority == 'Faible'])

    return render_template('analytics.html', 
                           total=total, 
                           completed=completed_count, 
                           pending=pending_count,
                           high=high_count, 
                           med=med_count, 
                           low=low_count)

@main.route('/workflow')
def workflow():
    return render_template('workflow.html')

@main.route('/add_task', methods=['POST'])
def add_task():
    title = request.form.get('title')
    description = request.form.get('description')
    deadline_str = request.form.get('deadline')
    start_time = request.form.get('start_time') or None
    end_time = request.form.get('end_time') or None
    priority = request.form.get('priority')
    
    deadline = datetime.strptime(deadline_str, '%Y-%m-%d') if deadline_str else None

    new_task = Task(
        title=title, 
        description=description, 
        deadline=deadline,
        start_time=start_time,
        end_time=end_time,
        priority=priority, 
        status='en attente'
    )
    db.session.add(new_task)
    db.session.commit()
    return redirect(request.referrer or url_for('main.calendar_view'))

@main.route('/validate_task/<int:id>')
def validate_task(id):
    task = Task.query.get_or_404(id)
    task.status = 'validée'
    db.session.commit()
    return redirect(request.referrer or url_for('main.board'))

@main.route('/pending_task/<int:id>')
def pending_task(id):
    task = Task.query.get_or_404(id)
    task.status = 'en attente'
    db.session.commit()
    return redirect(request.referrer or url_for('main.board'))

@main.route('/delete_task/<int:id>')
def delete_task(id):
    task = Task.query.get_or_404(id)
    db.session.delete(task)
    db.session.commit()
    return redirect(request.referrer or url_for('main.board'))

@main.route('/edit_task/<int:id>', methods=['GET', 'POST'])
def edit_task(id):
    task = Task.query.get_or_404(id)
    if request.method == 'POST':
        task.title = request.form.get('title')
        task.description = request.form.get('description')
        deadline_str = request.form.get('deadline')
        task.deadline = datetime.strptime(deadline_str, '%Y-%m-%d') if deadline_str else None
        
        # Récupération des horaires
        task.start_time = request.form.get('start_time') or None
        task.end_time = request.form.get('end_time') or None
        
        task.priority = request.form.get('priority')
        db.session.commit()
        return redirect(url_for('main.board'))
    return render_template('edit_task.html', task=task)
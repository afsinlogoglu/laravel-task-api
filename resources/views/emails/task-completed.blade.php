<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Task Completed</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .content { background-color: #ffffff; padding: 20px; border: 1px solid #dee2e6; border-radius: 5px; }
        .task-info { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .footer { text-align: center; margin-top: 20px; color: #6c757d; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Task Completed</h2>
        </div>
        
        <div class="content">
            <p>Hi {{ $user->name }},</p>
            
            <p>A task in your team has been completed:</p>
            
            <div class="task-info">
                <h3>{{ $task->title }}</h3>
                @if($task->description)
                    <p><strong>Description:</strong> {{ $task->description }}</p>
                @endif
                <p><strong>Team:</strong> {{ $task->team->name }}</p>
                <p><strong>Completed by:</strong> {{ $task->assignedUser->name }}</p>
                <p><strong>Completed on:</strong> {{ now()->format('M d, Y H:i') }}</p>
            </div>
            
            <p>Great job! The task is now marked as completed.</p>
            
            <p>Thanks,<br>Task Management System</p>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please don't reply to this.</p>
        </div>
    </div>
</body>
</html> 
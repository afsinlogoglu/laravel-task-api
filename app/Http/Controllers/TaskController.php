<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\TaskFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    public function store(TaskRequest $request): JsonResponse
    {
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? Task::STATUS_PENDING,
            'assigned_user_id' => $request->assigned_user_id,
            'due_date' => $request->due_date,
            'team_id' => $request->team_id,
            'created_by' => $request->user()->id,
        ]);

        return response()->json([
            'success' => true,
            'data' => $task->load(['team', 'assignedUser', 'creator']),
            'message' => 'Task created successfully',
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $query = Task::with(['team', 'assignedUser', 'creator', 'files'])
            ->where(function ($q) use ($user) {
                $q->where('team_id', function ($subQ) use ($user) {
                    $subQ->select('team_id')
                        ->from('team_members')
                        ->where('user_id', $user->id);
                })
                ->orWhere('assigned_user_id', $user->id)
                ->orWhere('created_by', $user->id);
            });

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('assigned_user_id')) {
            $query->where('assigned_user_id', $request->assigned_user_id);
        }

        if ($request->has('team_id')) {
            $query->where('team_id', $request->team_id);
        }

        $tasks = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $tasks,
            'message' => 'Tasks retrieved successfully',
        ]);
    }

    public function update(TaskRequest $request, int $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $user = $request->user();

        if (!$task->team->hasMember($user)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not a member of this team',
            ], 403);
        }

        $task->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => $task->load(['team', 'assignedUser', 'creator']),
            'message' => 'Task updated successfully',
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $user = $request->user();

        if (!$task->team->hasMember($user)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not a member of this team',
            ], 403);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully',
        ]);
    }

    public function uploadFile(Request $request, int $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $user = $request->user();

        if (!$task->team->hasMember($user)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not a member of this team',
            ], 403);
        }

        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $filePath = 'task-files/' . $filename;

        Storage::disk('public')->put($filePath, file_get_contents($file));

        $taskFile = TaskFile::create([
            'task_id' => $task->id,
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
        ]);

        return response()->json([
            'success' => true,
            'data' => $taskFile,
            'message' => 'File uploaded successfully',
        ], 201);
    }
}

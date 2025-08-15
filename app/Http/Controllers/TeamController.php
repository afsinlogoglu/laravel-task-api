<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamRequest;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function store(TeamRequest $request): JsonResponse
    {
        $team = Team::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => $request->user()->id,
        ]);

        $team->members()->attach($request->user()->id);

        return response()->json([
            'success' => true,
            'data' => $team->load(['owner', 'members']),
            'message' => 'Team created successfully',
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $teams = Team::where('owner_id', $user->id)
            ->orWhereHas('members', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['owner', 'members'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $teams,
            'message' => 'Teams retrieved successfully',
        ]);
    }

    public function addMember(Request $request, int $id): JsonResponse
    {
        $team = Team::findOrFail($id);
        $user = $request->user();

        if (!$team->isOwner($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Only team owners can add members',
            ], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $memberUser = User::findOrFail($request->user_id);

        if ($team->hasMember($memberUser)) {
            return response()->json([
                'success' => false,
                'message' => 'User is already a member of this team',
            ], 400);
        }

        $team->members()->attach($request->user_id);

        return response()->json([
            'success' => true,
            'data' => $team->load(['owner', 'members']),
            'message' => 'Member added successfully',
        ]);
    }

    public function removeMember(Request $request, int $id, int $userId): JsonResponse
    {
        $team = Team::findOrFail($id);
        $user = $request->user();

        if (!$team->isOwner($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Only team owners can remove members',
            ], 403);
        }

        if ($team->owner_id === $userId) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot remove team owner',
            ], 400);
        }

        $team->members()->detach($userId);

        return response()->json([
            'success' => true,
            'data' => $team->load(['owner', 'members']),
            'message' => 'Member removed successfully',
        ]);
    }
}

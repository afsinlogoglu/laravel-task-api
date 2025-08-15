<?php

namespace App\Http\Middleware;

use App\Models\Team;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamMemberCheck
{
    public function handle(Request $request, Closure $next): Response
    {
        $teamId = $request->route('id') ?? $request->input('team_id');
        
        if (!$teamId) {
            return response()->json([
                'success' => false,
                'message' => 'Team ID is required',
            ], 400);
        }

        $team = Team::findOrFail($teamId);
        $user = $request->user();

        if (!$team->hasMember($user)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not a member of this team',
            ], 403);
        }

        return $next($request);
    }
}

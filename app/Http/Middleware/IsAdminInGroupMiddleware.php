<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdminInGroupMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $groupId = request()->input('group_id');
        $auth = auth()->user();
        $user = User::findOrFail($auth->id);
        $isAdmin = $user->groups()->where('group_id', $groupId)->where('isAdmin', true)->first();
        if ($isAdmin === null) {
            return response()->json(['message' => 'Unauthorized - You must be an admin of this group'], 403);
        }

        return $next($request);
    }
}

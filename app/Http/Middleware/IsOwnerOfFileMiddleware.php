<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsOwnerOfFileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $groupId = $request->input('group_id');
        $fileIds = explode(',', $request->route('ids'));
        $user = User::findOrFail(Auth::user()->id);

        $isAdmin = $user->groups()->where('group_id', $groupId)->where('isAdmin', true)->first();

        $filesCount = $user->files()->whereIn('id', $fileIds)->count();
        $isOwner = $filesCount === count($fileIds);

        if ($isAdmin ===null && !$isOwner) {
            return response()->json(['message' => 'Unauthorized - You must be an admin of the group or the owner of all specified files'], 403);
        }

        return $next($request);
    }
    }


<?php


namespace App\Http\Middleware;

use Closure;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckDownload
{
    public function handle(Request $request, Closure $next)
    {
        $user = User::findOrFail(Auth::user()->id);
        $fileIds = request()->ids;

        if (!$fileIds) {
            return response()->json(['message' => 'No file ID provided'], 400);
        }

        $fileIdsArray = explode(',', $fileIds);

        $files = File::with('groups')->findMany($fileIdsArray);

        if ($files->isEmpty()) {
            return response()->json(['message' => 'One or more files not found'], 404);
        }

        foreach ($files as $file) {
            $fileGroupIds = $file->groups->pluck('id')->toArray();
            $isMember = $user->groups()->whereIn('group_id', $fileGroupIds)->first();
            if ($isMember===null) {
                return response()->json(['message' => "Unauthorized - You are not a member of any group associated with file ID: {$file->id}"], 403);
            }
        }

        return $next($request);
    }

}

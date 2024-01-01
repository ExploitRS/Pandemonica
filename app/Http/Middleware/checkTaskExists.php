<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkTaskExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $task = $request->route('task');
        // if (!$task) {
        //     return response()->json(['message' => 'Task not found'], 404);
        // }
        $taskId = $request->route('tasks');

        if (! Task::find($taskId)) {
            abort(404);
        }

        return $next($request);
    }
}

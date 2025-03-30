<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TaskResource::collection(Task::with('user')
        ->paginate());
    }

    public function getUserTask()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $tasks = Task::where('user_id', $user->id)->latest()->get();
        return TaskResource::collection($tasks);
    }

    public function getTasksOfUser(User $user)
    {
        $tasks = Task::where('user_id', $user->id)->latest()->get();
        return TaskResource::collection($tasks);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $task = Task::create(
            [...$request->validate(
            [
            'name' => 'required|string|max:50',
            'description' => 'required|string',
            'long_description' => 'string|nullable'
            ],
        ),'user_id' => $user->id]);
        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load('user');
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        // $user = JWTAuth::parseToken()->authenticate();
        $task->update(
            $request->validate(
            [
            'name' => 'sometimes|string|max:50',
            'description' => 'sometimes|string',
            'long_description' => 'sometimes|string',
            'completed' => 'sometimes|boolean',
            ],
        ));
        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response(status:201);
    }
}

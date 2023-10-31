<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $task = Task::all();
        return TaskResource::collection($task);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "is_done" => "required|boolean"
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->all(), 422);
        }

        $task = Task::create($request->all());
        return (new TaskResource($task))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        //
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "is_done" => "required|boolean"
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->all(), 422);
        }


        $task->update($request->all());

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
        $task->delete();
        return response("Deleted", 204);
    }
}

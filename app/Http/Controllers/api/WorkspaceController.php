<?php

namespace App\Http\Controllers\api;

use App\Models\Workspace;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WorkspaceResource;
use Illuminate\Support\Facades\Validator;

class WorkspaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workspace = Workspace::all();
        return WorkspaceResource::collection($workspace);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            "title" => "required"
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->all(), 422);
        }

        $workspace = Workspace::create($request->all());
        return (new WorkspaceResource($workspace))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Workspace $workspace)
    {
        //
        return new WorkspaceResource($workspace);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Workspace $workspace)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required"
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->all(), 422);
        }


        $workspace->update($request->all());

        return new WorkspaceResource($workspace);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workspace $workspace)
    {
        //
        $workspace->delete();
        return response("Deleted", 204);
    }
}

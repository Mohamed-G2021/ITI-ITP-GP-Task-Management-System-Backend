<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Phase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PhaseResource;

class PhaseController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $phases = Phase::all();
        if($phases->isEmpty()){
            return response('Empty',200);
        }
        return PhaseResource::collection($phases);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title"=> "required|min:3",
            "position"=> "required|numeric",
            "board_id"=> "required",
        ]);

        if($validator->fails()){
            return response($validator->errors()->all(), 422);
        }

        $phase = Phase::create($request->all());
        return response($phase)->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $phase = Phase::find($id);
        if(!$phase){
            return response('Data not found',404);
        }
        return new PhaseResource($phase);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $phase = Phase::find($id);
        if(!$phase){
            return response('Data not found',404);
        }
        $validator = Validator::make($request->all(), [
            "title"=> "required|min:3",
            "position"=> "required|numeric",
            "board_id"=> "required",
        ]);

        if($validator->fails()){
            return response($validator->errors()->all(), 422);
        }


       $phase->update($request->all());

       return new PhaseResource($phase);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $phase = Phase::find($id);
       if(!$phase){
            return response('Data not found',404);
        }else{
            $phase->delete();
        }
        return response('deleted',202);
    }
}

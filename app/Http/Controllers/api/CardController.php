<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Models\Card;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $cards = $user->cards->sortBy('position')->values();
        return CardResource::collection($cards);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'phase_id' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response($validator->errors()->all(), 422);
        }

        $card = Card::create($request->all());
        $user = Auth::user();
        $user->cards()->attach($card->id);


        return (new CardResource($card))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Card $card)
    {
        return new CardResource($card);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Card $card)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'phase_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response($validator->errors()->all(), 422);
        }

        $card->update($request->all());

        if ($request->category_id) {
            $category = Category::find($request->category_id);
            if ($category) {
                $category->cards()->attach($card->id);
            } else {
                response('Not found', 404);
            }
        }
        return (new CardResource($card))->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        $card->delete();
        return response('deleted', 202);
    }
}

<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\Request;
use App\Http\Resources\BoardResource;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BoardController extends Controller
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
        $boards = $user->boards->sortByDesc('updated_at')->values();
        return BoardResource::collection($boards);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required|min:3",
            "workspace_id" => 'required'
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->all(), 422);
        }

        $owns_boards = DB::table('user_board')
            ->where('user_id', Auth::id())
            ->count();
        $user = User::find(Auth::id());

        if ($owns_boards >= 1 && $user->subscribed == 'no') {
            return redirect(env('FRONTEND_DOMAIN') . '/pricing');
        } else {
            $board = Board::create($request->all());

            $user = Auth::user();
            $user->boards()->attach($board->id);
        }

        return (new BoardResource($board))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Board $board)
    {
        return new BoardResource($board);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Board $board)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required|min:3",
        ]);
        /*         $request->validate([
            "title" => "required|min:3",
            "workspace_id" => 'required'
        ]);
 */
        if ($validator->fails()) {
            return response($validator->errors()->all(), 422);
        }


        $board->update($request->all());

        return new BoardResource($board);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board)
    {
        $board->delete();
        return response('deleted', 202);
    }
}

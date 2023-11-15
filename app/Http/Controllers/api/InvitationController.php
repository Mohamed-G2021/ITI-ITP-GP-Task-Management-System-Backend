<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invitation;
use App\Mail\InvitationEmail;
use App\Models\Board;
use App\Models\Card;
use App\Models\Phase;
use App\Models\Workspace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function sendInvitation(Request $request)
    {
        switch ($request->invitation_on) {
            case 'workspace':
                $request->validate([
                    'email' => 'required|email|exists:App\Models\User,email',
                    'invitation_on_id' => 'required|exists:App\Models\Workspace,id'
                ], [
                    'email.exists' => 'You can invite users who have accounts only',
                    'invitation_on_id.exists' => 'Enter a valid workspace id'
                ]);
                break;
            case 'board':
                $request->validate([
                    'email' => 'required|email|exists:App\Models\User,email',
                    'invitation_on_id' => 'required|exists:App\Models\Board,id'
                ], [
                    'email.exists' => 'You can invite users who have accounts only',
                    'invitation_on_id.exists' => 'Enter a valid board id'

                ]);
                break;
            case 'card':
                $request->validate([
                    'email' => 'required|email|exists:App\Models\User,email',
                    'invitation_on_id' => 'required|exists:App\Models\Card,id'
                ], [
                    'email.exists' => 'You can invite users who have accounts only',
                    'invitation_on_id.exists' => 'Enter a valid card id'
                ]);
                break;
        }


        $query = Invitation::where('email', $request->email)
            ->where('invitation_on', $request->invitation_on)
            ->where('invitation_on_id', $request->invitation_on_id)
            ->where('status', 'pending')
            ->orwhere([
                ['invitation_on', $request->invitation_on],
                ['invitation_on_id', $request->invitation_on_id],
                ['status', 'accepted']
            ])
            ->count();
        if ($query) {
            return response()->json(['message' => 'Invitation sent already']);
        }

        $invitation = Invitation::create([
            'email' => $request->email,
            'invitation_on' => $request->invitation_on,
            'invitation_on_id' => $request->invitation_on_id,
        ]);

        Mail::to($invitation->email)->send(new InvitationEmail($invitation, env('FRONTEND_DOMAIN') . '/invitation?id=' . $invitation->id));

        return response()->json([
            'message' => 'Invitation sent successfully',
        ]);
    }

    public function acceptInvitation($id)
    {
        $invitation = Invitation::find($id);

        if (Auth::user()->email == $invitation->email) {

            if ($invitation->status == 'accepted') {
                return response()->json(
                    ['message' => 'Invitation has been accepted already']
                );
            } else if ($invitation->status == 'pending') {
                switch ($invitation->invitation_on) {
                    case 'workspace':
                        Auth::user()->workspaces()->attach(
                            ['workspace_id' => $invitation->invitation_on_id],
                            ['role' => 'member']
                        );

                        break;

                    case 'board':
                        $board = Board::find($invitation->invitation_on_id);

                        $record_exists = DB::table('user_workspace')
                            ->where('user_id', Auth::id())
                            ->where('workspace_id', $board->workspace_id)
                            ->exists();

                        if (!$record_exists) {
                            Auth::user()->workspaces()->attach(
                                ['workspace_id' => $board->workspace_id],
                                ['role' => 'member']
                            );
                            Auth::user()->boards()->attach(
                                ['board_id' => $invitation->invitation_on_id],
                                ['role' => 'member']
                            );
                        } else {
                            Auth::user()->boards()->attach(
                                ['board_id' => $invitation->invitation_on_id],
                                ['role' => 'member']
                            );
                        }

                        break;

                    case 'card':
                        $card = Card::find($invitation->invitation_on_id);
                        $phase = Phase::find($card->phase_id);
                        $board = Board::find($phase->board_id);

                        $record_exists = DB::table('user_workspace')
                            ->where('user_id', Auth::id())
                            ->where('workspace_id', $board->workspace_id)
                            ->exists();

                        if (!$record_exists) {
                            Auth::user()->workspaces()->attach(
                                ['workspace_id' => $board->workspace_id],
                                ['role' => 'member']
                            );
                            Auth::user()->boards()->attach(
                                ['board_id' => $board->id],
                                ['role' => 'member']
                            );
                            Auth::user()->cards()->attach(
                                ['card_id' => $invitation->invitation_on_id],
                                ['role' => 'member']
                            );
                        } else {
                            Auth::user()->cards()->attach(
                                ['card_id' => $invitation->invitation_on_id],
                                ['role' => 'member']
                            );
                        }

                        break;
                }

                if ($invitation) {
                    $invitation->update(['status' => 'accepted']);
                    return response()->json(['message' => 'Invitation accepted successfully']);
                } else {
                    return response()->json(
                        ['message' => 'Invalid invitation'],
                        404
                    );
                }
            } else if ($invitation->status == 'declined') {
                return response()->json(
                    ['message' => 'Request not allowed'],
                    405
                );
            }
        } else {
            return response()->json(
                ['message' => 'unauthorized'],
                403
            );
        }
    }

    public function declineInvitation($id)
    {
        $invitation = Invitation::where('id', $id)->first();

        if (Auth::user()->email == $invitation->email) {

            if ($invitation->status == 'declined') {
                return response()->json(
                    ['message' => 'Invitation has been declined already']
                );
            } else if ($invitation->status == 'pending') {
                if ($invitation) {
                    $invitation->update(['status' => 'declined']);

                    return response()->json(['message' => 'Invitation declined successfully']);
                } else {
                    return response()->json(
                        ['message' => 'Invalid invitation'],
                        404
                    );
                }
            } else if ($invitation->status == 'accepted') {
                return response()->json(
                    ['message' => 'Request not allowed'],
                    405
                );
            }
        } else {
            return response()->json(
                ['message' => 'unauthorized'],
                403
            );
        }
    }
}

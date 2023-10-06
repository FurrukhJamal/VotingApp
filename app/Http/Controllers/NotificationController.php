<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class NotificationController extends Controller
{
    public function getNotifications(Request $request)
    {
        // dd($request->user()) //if its a inertia request but have to make manual request here
        $data = $request->json()->all();
        $user = User::find($data["user"]["id"]);
        if (!$user) {
            abort(Response::HTTP_FORBIDDEN);
        }
        // return ["notifications" => $user->unreadNotifications];
        $notifications = [];
        foreach ($user->unreadNotifications as $notification) {
            $notifications[] = [
                "created_at" => $notification->created_at,
                "data" => $notification->data
            ];
        }
        return [
            "notifications" => $notifications,
            "user_hasNotifications" => $user->has_notifications,
            "numberOfNotifications" => $user->numNotification,
        ];
    }

    public function notificationsChecked(Request $request)
    {
        // dd($request->user());
        $request->user()->setHasNoNotifications();
        //reset number of notifications
        $request->user()->resetNumberOfNotifications();
    }

    public function markNotificationsAsRead(Request $request)
    {
        // dd("hitting");
        $request->user()->unreadNotifications->markAsRead();
        $request->user()->resetNumberOfNotifications();
        $request->user()->setHasNoNotifications();
    }
}

<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Mail;

class NotificationsController extends Controller
{
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        $input = $request->all();
        $input['status'] = 'pending';
        $notification = Notification::create($input);

        if ($request->type == 'now') {
            $email = "response2shiv@gmail.com";
            $messageData = ['name' => 'Shiv', 'messageText' => $request->message];
            Mail::send('email.notification', $messageData, function ($message) use ($email) {
                $message->to($email)->subject('Hostcob Solutions Pvt Ltd: notification');
            });

            if ($notification->id) {
                Notification::where('id', $notification->id)->update(['status' => 'sent']);
            }
        }

        $response = [
            'success' => true,
            'message' => 'Notification created successfully'
        ];

        return response()->json($response, 200);
    }

    public function list(Request $request)
    {
        $recordPerPage = 2;
        $notifications = Notification::paginate($recordPerPage);

        $response = [
            'success' => true,
            'data' => $notifications,
            'message' => 'Notifications List'
        ];

        return response()->json($response, 200);
    }
}

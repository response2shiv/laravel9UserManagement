<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'password' => 'required',
            'state' => 'required',
            'gender' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        // $success['token'] = $user->createToken('MyApp')->plainTextToken;
        // $success['name'] = $user->name;

        $email = $request->email;
        $token = base64_encode($email);
        $link = url('/verify-account/'. $token);
        $messageData = ['name' => $request->name, 'email' => $request->email, 'phone' => $request->phone, 'link' => $link];
        Mail::send('email.account-verification', $messageData, function ($message) use ($email) {
            $message->to($email)->subject('Hostcob Solutions Pvt Ltd: account verification link');
        });

        $response = [
            'success' => true,
            'message' => 'User created successfully'
        ];

        return response()->json($response, 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            if($user->email_verified_at){
                $success['token'] = $user->createToken('MyApp')->plainTextToken;
                $success['name'] = $user->name;
                
                $response = [
                    'success' => true,
                    'data' => $success,
                    'message' => 'User login successfully'
                ];
            } else {
                $success['name'] = $user->name;
                $response = [
                    'success' => false,
                    'data' => $success,
                    'message' => 'Hi '. $user->name . ', Please activate your account to get access your account.'
                ];
            }

            return response()->json($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'Unauthorized'
            ];
            return response()->json($response, 200);
        }
    }

    public function sendOtpMail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required'
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        $fourDigitOtp = str_pad(rand(0, pow(10, 4) - 1), 4, '0', STR_PAD_LEFT);
        $email = $request->email;
        $messageData = ['name' => $request->name, 'email' => $request->email, 'phone' => $request->phone, 'code' => $fourDigitOtp];
        Mail::send('email.otp-verification', $messageData, function ($message) use ($email) {
            $message->to($email)->subject('Hostcob Solutions Pvt Ltd: phone verification by OTP');
        });

        $response = [
            'success' => true,
            'data' => ['otp' => $fourDigitOtp],
            'message' => 'Otp Send successfully.'
        ];
        return response()->json($response, 200);
    }

    public function verifyAccount(Request $request){
        $email = base64_decode($request->token);
        $user = User::where('email',$email)->first();
        $user->email_verified_at = date('Y-m-d H:i:s');
        $user->save();

        $response = [
            'success' => true,
            'data' => ['name' => $user->name],
            'message' => 'Account is verified successfully.'
        ];
        return response()->json($response, 200);
    }
}

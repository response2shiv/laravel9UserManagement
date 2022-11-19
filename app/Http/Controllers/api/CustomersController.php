<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Customer;

class CustomersController extends Controller
{
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required'
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        $input = $request->all();
        $customer = Customer::create($input);

        $response = [
            'success' => true,
            'message' => 'Customer created successfully'
        ];

        return response()->json($response, 200);
    }

    public function list(Request $request)
    {
        $recordPerPage = 2;
        if ($request->search) {
            $customers = Customer::where('name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('email', 'LIKE', '%' . $request->search . '%')
                ->orWhere('mobile', 'LIKE', '%' . $request->search . '%')
                ->paginate($recordPerPage);
        } else {
            $customers = Customer::paginate($recordPerPage);
        }

        $response = [
            'success' => true,
            'data' => $customers,
            'search' => $request->search,
            'message' => 'Customer List'
        ];

        return response()->json($response, 200);
    }
}

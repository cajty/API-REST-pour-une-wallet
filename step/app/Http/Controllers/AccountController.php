<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Str;


class AccountController extends Controller
{
    public function createAccount(Request $request)
    {
 
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'balance' => ['required', 'numeric']
                ]
            );
    
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
    
            $numericAccountNumber = Str::uuid();
            
        
    
            $account = Account::create([
                'user_id' => $request->user()->id,
                'account_number' => $numericAccountNumber,
                'balance' => $request->balance,
            ]);
    
         
    
            return response()->json([
                'status' => true,
                'message' => 'Account created successfully',
                'account' => $account
            ], 200);
        } catch (\Throwable $exception) {
        
            
            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    public function accountAction(Request $request)
    {
        try {
        
            $user =   User::find($request->user()->id);
           

            return response()->json([
                'status' => true,
                'history' => $user->account()->get()
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}

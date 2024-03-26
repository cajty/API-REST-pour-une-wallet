<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;



class TransactionController extends Controller
{
    public function sender(Request $request)
    {
       
        try {
            
            $validateUser = Validator::make(
                $request->all(),
                [
                    'sender' => ['required', 'string'],
                    'receiver' => ['required', 'string'],
                    'amount' => ['required', 'numeric','min:0' ],
                    
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $sender = $request->input('sender');
            $receiver = $request->input('receiver');
            $amount = $request->input('amount');

          
            $sender = Account::where('account_number', $sender)->where('user_id', $request->user()->id)->first();
            $receiver = Account::where('account_number', $receiver)->first();

            if (!$sender) {
                return response()->json(['error' => 'Sender account not found'], 404);
            }


            if (!$receiver) {
                return response()->json(['error' => 'Receiver account not found'], 404);
            }


            if ($sender->balance < $amount) {
                return response()->json(['error' => 'Insufficient balance'], 400);
            }




            DB::beginTransaction();
            $sender->balance -= $amount;
            $sender->save();

            $receiver->balance += $amount;
            $receiver->save();

            $transaction = Transaction::create([
                'sender_id' => $sender->id,
                'recipient_id' => $receiver->id,
                'amount' => $amount,
            ]);
            DB::commit();

            return response()->json($transaction, 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
}

<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Account;
use App\Models\Transaction;


class TransactionController extends Controller
{
    public function sender(Request $request)
    {
        try {
            $request->validate([
                'sender' => 'required|numeric',
                'receiver' => 'required|numeric',
                'amount' => 'required|numeric|min:0',
            ]);

            $sender = $request->input('sender');
            $receiver = $request->input('receiver');
            $amount = $request->input('amount');

      
            $sender = Account::where('account_number', $sender)->first();
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

 
            $transaction = Transaction::create([
                'sender_id' => $sender->id,
                'recipient_id' => $receiver->id,
                'amount' => $amount,
            ]);


            $sender->balance -= $amount;
            $sender->save();

            $receiver->balance += $amount;
            $receiver->save();

            return response()->json($transaction, 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
}

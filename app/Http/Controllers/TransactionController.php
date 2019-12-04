<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Ticket;
use App\Transaction;
use App\User;
use Illuminate\Support\Facades\Log;
use Exception;
class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function detail($transaction_id)
    {
        $transaction = Transaction::find($transaction_id);
        if(!$transaction)
        {
            $response = [
                'code' => 404,
                'message' => 'No Transaction found',
                'data' => []
            ];
            return response()->json($response, 404);
        }
        $response = [
            'code' => 1,
            'message' => 'Detail Transaction',
            'data' => [
                $transaction,
            ]

        ];
        return response()->json($response, 200);
    }
    public function book(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::find($user_id);
        $ticket_id = $request->ticket_id;
        $total_ticket = $request->total_ticket;

        $ticket = Ticket::find($ticket_id);
        if(!$user)
        {
            $response = [
                'code' => 404,
                'message' => 'User Not Found',
                'data' => []
            ];
            return response()->json($response, 404);
        }
        if(!$ticket)
        {
            $response = [
                'code' => 404,
                'message' => 'Not found',
                'data' => []
            ];
            return response()->json($response, 504);
        }
        
        try{
            DB::beginTransaction();

            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->ticket_id = $ticket_id;
            $transaction->total = ($total_ticket * $ticket->price);
            $ticket->stock -= $total_ticket;
            
            $transaction->status_id = Transaction::BOOKED;
            $ticket->save();
            $transaction->save();
            DB::commit();
        }catch(Exception $e)
        {
            DB::rollback();
            Log::error($e);

            $response = [
                'code' => 504,
                'message' => 'Internal Server Error',
                'data' => []
            ];
            return response()->json($response, 504);
        }

        $response = [
            'code' => 200,
            'message' => 'Book Succeeded',
            'data' => []
        ];
        return response()->json($response, 200);
    }

    public function pay(Request $request)
    {
        $transaction_id = $request->transaction_id;

        $transaction = Transaction::find($transaction_id);

        if(!$transaction)
        {
            $response = [
                'code' => 404,
                'message' => 'Transaction not found',
                'data' => [],
            ];

            return response()->json($response, 404);
        }
        if($transaction->status_id == Transaction::CANCELLED)
        {
            $response = [
                'code' => 201,
                'message' => 'Transaction has been cancelled',
                'data' => [],
            ];
            return response()->json($response, 201);
        }
        try{   
            DB::beginTransaction();
            $transaction->status_id = Transaction::PAID;
            $transaction->save();
            
            
            DB::commit();

        } catch(Exception $e)
        {
            DB::rollback();
            Log::error($e);
            $response = [
                'code' => 504,
                'message' => 'Internal Server Error',
                'data' => [],
            ];

            return response()->json($response, 504);
        }

        $response = [
            'code' => 200,
            'message' => 'Paid Succeeded',
            'data' => [],
        ];
        return response()->json($response, 200);
    }
    
    public function cancel(Request $request)
    {
        $transaction_id = $request->transaction_id;

        $transaction = Transaction::find($transaction_id);

        if(!$transaction)
        {
            $response = [
                'code' =>  404,
                'message' => 'Transaction not found',
                'data' => [],
            ];

            return response()->json($response, 404);
        }
        if($transaction->status_id == Transaction::PAID)
        {
            $response = [
                'code' => 201,
                'message' => 'Transaction is already paid',
                'data' => [],
            ];
            return response()->json($response, 201);
        }
        try{
            DB::beginTransaction();
            $transaction->status_id = Transaction::CANCELLED;
            $transaction->save();

            DB::commit();
        } catch(Exception $e)
        {
            DB::rollback();
            Log::error($e);
            $response = [
                'code' =>  504,
                'message' => 'Internal Server Error',
                'data' => [],
            ];

            return response()->json($response, 504);
        }
        $response = [
            'code' =>  200,
            'message' => 'Transaction cancelled',
            'data' => [],
        ];

        return response()->json($response, 200);
    }
    //
}

<?php

namespace App\Http\Controllers;
use App\Ticket;

class TicketController extends Controller
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

    public function tickets()
    {
        $tickets = Ticket::all();

        $response =[
            'code' => 1,
            'message' => 'Ticket List',
            'data' => [$tickets,]
        ];

        return response()->json($response, 200);
    }

    public function ticketDetail($ticket_id)
    {
        $ticket = Ticket::find($ticket_id);

        $response = [
            'code' => 1,
            'message' => 'Ticket',
            'data' => [$ticket],
        ];
        return response()->json($response, 200);
    }

    //
}

<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TicketsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        if(DB::table('tickets')->get()->count() == 0)
        {
            DB::table('tickets')->insert([
                ['name' => 'Konser Heboh', 'price' => 100000, 'stock' => 5, 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')],
                ['name' => 'Konser Ambyar', 'price' => 150000, 'stock' => 10, 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')],
                ['name' => 'Konser Dangdut', 'price' => 50000, 'stock' => 50, 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ]);
        }
    }
}

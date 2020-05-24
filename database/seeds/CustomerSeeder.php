<?php

use Illuminate\Database\Seeder;
use App\Http\Models\Entities\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $customers = [ 
            ["name"=> "Jose Luis", "lastname" => "Otero Lopez", "subdomain" => "oteroweb", "currency_id" => 1, "active" => 1, 'user_id' => 1],
            ["name"=> "Aura Rosa", "lastname" => "Griman Garcia", "subdomain" => "argriman", "currency_id" => 112, "active" => 1, 'user_id' => 2],
            ];
        foreach($customers as $customer){
            Customer::create($customer);
        }
    }
}

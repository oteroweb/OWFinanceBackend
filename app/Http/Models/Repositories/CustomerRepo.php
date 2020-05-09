<?php
    
    namespace App\Http\Models\Repositories;
    
    use Mockery\Matcher\Type;
    use Illuminate\Support\Facades\Log;
    use App\Http\Models\Entities\Customer;
    
    class CustomerRepo {
        public function all() {
            $currency = Customer::whereIn('active', [1,0])->with([])
            ->get();            
            return $currency;
        }
        public function find($id) {
            $currency = Customer::with([])->find($id);
            return $currency;
        }        
        public function store($data) {            
            $currency = new Customer();
            $currency->fill($data);
            $currency->save();
            return $currency;
        }        
        public function update($currency, $data) {
            $currency->fill($data);
            $currency->save();
            return $currency;
        }
        public function delete($currency, $data) {
            $currency->fill($data);
            $currency->save();
            return $currency;
        }
    }
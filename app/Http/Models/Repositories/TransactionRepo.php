<?php
    
    namespace App\Http\Models\Repositories;
    
    use Mockery\Matcher\Type;
    use Illuminate\Support\Facades\Log;
    use App\Http\Models\Entities\Transaction;
    
    class TransactionRepo {
        public function all() {
            $transaction = Transaction::whereIn('active', [1,0])->with([])
            ->get();            
            return $transaction;
        }
        public function find($id) {
            $transaction = Transaction::with([])->find($id);
            return $transaction;
        }        
        public function store($data) {            
            $transaction = new Transaction();
            $transaction->fill($data);
            $transaction->save();
            return $transaction;
        }        
        public function update($transaction, $data) {
            $transaction->fill($data);
            $transaction->save();
            return $transaction;
        }
        public function delete($transaction, $data) {
            $transaction->fill($data);
            $transaction->save();
            return $transaction;
        }
    }
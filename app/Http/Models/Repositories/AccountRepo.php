<?php
    
    namespace App\Http\Models\Repositories;
    
    use Mockery\Matcher\Type;
    use Illuminate\Support\Facades\Log;
    use App\Http\Models\Entities\Account;
    
    class AccountRepo {
        public function all() {
            $account = Account::whereIn('active', [1,0])->with(['transaction','currency','customer'])
            ->get();            
            return $account;
        }
        public function find($id) {
            $account = Account::with(['transaction','currency','customer'])->find($id);
            return $account;
        }        
        public function store($data) {            
            $account = new Account();
            $account->fill($data);
            $account->save();
            return $account;
        }        
        public function update($account, $data) {
            $account->fill($data);
            $account->save();
            return $account;
        }
        public function delete($account, $data) {
            $account->fill($data);
            $account->save();
            return $account;
        }
    }
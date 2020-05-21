<?php
    
    namespace App\Http\Models\Repositories;
    
    use Mockery\Matcher\Type;
    use Illuminate\Support\Facades\Log;
    use App\Http\Models\Entities\CategoryTransaction;
    
    class CategoryTransactionRepo {
        public function all() {
            $categorytransaction = CategoryTransaction::whereIn('active', [1,0])->with(['transaction'])
            ->get();            
            return $categorytransaction;
        }
        public function find($id) {
            $categorytransaction = CategoryTransaction::with(['transaction'])->find($id);
            return $categorytransaction;
        }        
        public function store($data) {            
            $categorytransaction = new CategoryTransaction();
            $categorytransaction->fill($data);
            $categorytransaction->save();
            return $categorytransaction;
        }        
        public function update($categorytransaction, $data) {
            $categorytransaction->fill($data);
            $categorytransaction->save();
            return $categorytransaction;
        }
        public function delete($categorytransaction, $data) {
            $categorytransaction->fill($data);
            $categorytransaction->save();
            return $categorytransaction;
        }
    }
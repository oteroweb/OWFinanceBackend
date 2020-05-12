<?php
    
    namespace App\Http\Models\Repositories;
    
    use Mockery\Matcher\Type;
    use Illuminate\Support\Facades\Log;
    use App\Http\Models\Entities\Item;
    
    class ItemRepo {
        public function all() {
            $item = Item::whereIn('active', [1,0])->with([])
            ->get();            
            return $item;
        }
        public function find($id) {
            $item = Item::with([])->find($id);
            return $item;
        }        
        public function store($data) {            
            $item = new Item();
            $item->fill($data);
            $item->save();
            return $item;
        }        
        public function update($item, $data) {
            $item->fill($data);
            $item->save();
            return $item;
        }
        public function delete($item, $data) {
            $item->fill($data);
            $item->save();
            return $item;
        }
    }
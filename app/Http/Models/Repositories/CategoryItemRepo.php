<?php
    
    namespace App\Http\Models\Repositories;
    
    use Mockery\Matcher\Type;
    use Illuminate\Support\Facades\Log;
    use App\Http\Models\Entities\CategoryItem;
    
    class CategoryItemRepo {
        public function all() {
            $categoryitem = CategoryItem::whereIn('active', [1,0])->with(['item'])
            ->get();            
            return $categoryitem;
        }
        public function find($id) {
            $categoryitem = CategoryItem::with([])->find($id);
            return $categoryitem;
        }        
        public function store($data) {            
            $categoryitem = new CategoryItem();
            $categoryitem->fill($data);
            $categoryitem->save();
            return $categoryitem;
        }        
        public function update($categoryitem, $data) {
            $categoryitem->fill($data);
            $categoryitem->save();
            return $categoryitem;
        }
        public function delete($categoryitem, $data) {
            $categoryitem->fill($data);
            $categoryitem->save();
            return $categoryitem;
        }
    }
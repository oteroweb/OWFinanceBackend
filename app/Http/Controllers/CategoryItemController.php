<?php

namespace App\Http\Controllers;

use App\Http\Models\Repositories\CategoryItemRepo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CategoryItemController extends Controller
{   
    private $CategoryItemRepo;
    public function __construct(CategoryItemRepo $CategoryItemRepo) {
        $this->CategoryItemRepo = $CategoryItemRepo;
    }
    public function all() {
        $fields =DB::getSchemaBuilder()->getColumnListing('transactions'); 
        $dataRequest = null;
        foreach ($fields as $value) {
            if( $value === "id" || $value === "created_at" || $value === "updated_at" || $value === "deleted_at"|| $value === "active" ) { continue; } 
            $dataRequest .= 'if (($request->input('.$value.'))) { $data += ['.$value.' => $request->input('.$value.')]; };';
        }
        var_dump($dataRequest);
        exit;
        try { $categoryitem = $this->CategoryItemRepo->all();
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => __('CategoryItem Obtained Correctly'),
                'data'    => $categoryitem,
            ];
            return response()->json($response, 200);
        } catch (\Exception $ex) {
            Log::error($ex);
            $response = [
                'status'  => 'FAILED',
                'code'    => 500,
                'message' => __('An error has occurred') . '.',
            ];
            return response()->json($response, 500);
        }
    }     

    public function find($id) {
        try {
            $categoryitem = $this->CategoryItemRepo->find($id);
            if (isset($categoryitem->id)) {
                $response = [
                    'status'  => 'OK',
                    'code'    => 200,
                    'message' => __('CategoryItem Obtained Correctly'),
                    'data'    => $categoryitem,
                ];
                return response()->json($response, 200);
            }
            $response = [
                'status'  => 'FAILED',
                'code'    => 404,
                'message' => __('Not Data with this CategoryItem') . '.',
            ];
            return response()->json($response, 200);
        } catch (\Exception $ex) {
            $response = [
                'status'  => 'FAILED',
                'code'    => 500,
                'message' => __('An error has occurred') . '.',
            ];
            return response()->json($response, 500);
        }
    }
    
    public function save(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'parent'=> 'integer',
            'depth'=>'required',
        ], $this->custom_message());
        if ($validator->fails()) {
            $response = [
                'status'  => 'FAILED',
                'code'    => 400,
                'message' => __('Incorrect Params'),
                'data'    => $validator->errors()->getMessages(),
            ];
            return response()->json($response);
        }
        try {
            $data = [ 
            'name'=> $request->input('name'),
            'parent'=> $request->input('parent'),
            'depth'=> $request->input('depth'),
            ];
            $categoryitem= $this->CategoryItemRepo->store($data);
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => __('CategoryItem saved correctly'),
                'data'    => $categoryitem,
            ];
            return response()->json($response, 200);
        } catch (\Exception $ex) {
            Log::error($ex);
            $response = [
                'status'  => 'FAILED',
                'code'    => 500,
                'message' => __('An error has occurred') . '.',
            ];
            return response()->json($response, 500);
        }
    }

    public function update(Request $request, $id) {
        $categoryitem = $this->CategoryItemRepo->find($id);
        if (isset($categoryitem->id)) {
            $data= array();
            if (($request->input('name'))) { $data += ['name' => $request->input('name')]; }
            if (($request->input('parent'))) { $data += ['parent' => $request->input('parent')]; }
            if (($request->input('depth'))) { $data += ['depth' => $request->input('depth')]; }
            $categoryitem = $this->CategoryItemRepo->update($categoryitem, $data);
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => __('CategoryItem updated'),
                'data'    => $categoryitem,
            ];
            return response()->json($response, 200);
        }
        $response = [
            'status'  => 'FAILED',
            'code'    => 500,
            'message' => __('CategoryItem dont exists') . '.',
        ];
        return response()->json($response, 500);
    }

    public function delete(Request $request, $id) {
        try {
            if ($this->CategoryItemRepo->find($id)) {
                $categoryitem = $this->CategoryItemRepo->find($id);
                $categoryitem = $this->CategoryItemRepo->delete($categoryitem, ['active' => 0]);
                $categoryitem = $categoryitem->delete();
                $response = [ 
                    'status'  => 'OK',
                    'code'    => 200,
                    'message' => __('CategoryItem Deleted Successfully'),
                    'data'    => $categoryitem,
                ];
                return response()->json($response, 200);
            }
            else {
                $response = [
                    'status'  => 'OK',
                    'code'    => 404,
                    'message' => __('CategoryItem not Found'),
                ];
                return response()->json($response, 200);
            }
            
        } catch (\Exception $ex) {
            Log::error($ex);
            $response = [
                'status'  => 'FAILED',
                'code'    => 500,
                'message' => __('An error has occurred') . '.',
            ];
            return response()->json($response, 500);
        }
    }

    public function change_status(Request $request, $id) {
        
        $categoryitem = $this->CategoryItemRepo->find($id);
        if (isset($categoryitem->active)) {
            if($categoryitem->active == 0){
                $data = ['active' => 1];
            }else{
                $data = ['active' => 0];
            }
            $categoryitem = $this->CategoryItemRepo->update($categoryitem, $data);
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => __('Status CategoryItem updated'),
                'data'    => $categoryitem,
            ];
            return response()->json($response, 200);
        }
        $response = [
            'status'  => 'FAILED',
            'code'    => 500,
            'message' => __('CategoryItem does not exist') . '.',
        ];
        return response()->json($response, 500);
    }
    public function custom_message() {
        
        return [
            'name.required'=> __('The name is required'),
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Models\Repositories\CategoryTransactionRepo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CategoryTransactionController extends Controller
{   
    private $CategoryTransactionRepo;
    public function __construct(CategoryTransactionRepo $CategoryTransactionRepo) {
        $this->CategoryTransactionRepo = $CategoryTransactionRepo;
    }
    public function all() {
        try { $categorytransaction = $this->CategoryTransactionRepo->all();
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => __('CategoryTransaction Obtained Correctly'),
                'data'    => $categorytransaction,
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
            $categorytransaction = $this->CategoryTransactionRepo->find($id);
            if (isset($categorytransaction->id)) {
                $response = [
                    'status'  => 'OK',
                    'code'    => 200,
                    'message' => __('categorytransaction Obtained Correctly'),
                    'data'    => $categorytransaction,
                ];
                return response()->json($response, 200);
            }
            $response = [
                'status'  => 'FAILED',
                'code'    => 404,
                'message' => __('Not Data with this categorytransaction') . '.',
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
            ];
            $categorytransaction= $this->CategoryTransactionRepo->store($data);
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => __('CategoryTransaction saved correctly'),
                'data'    => $categorytransaction,
            ];
            return response()->json($response, 200);
        } catch (\Exception $ex) {
            Log::error($ex);
            if (strpos($ex->getMessage(), 'SQLSTATE[23000]') !== false) {
                $errorForeing = $this->get_string_between($ex->errorInfo[2],'CONSTRAINT', 'FOREIGN');
                $response = [
                    'status'  => 'FAILED',
                    'code'    => 500,
                    'message' => __($errorForeing.'error') . '.',
                ];
            }
            else{
                $response = [
                    'status'  => 'FAILED',
                    'code'    => 500,
                    'message' => __('An error has occurred') . '.',
                ];
            }
            return response()->json($response, 500);
        }
    }

    public function update(Request $request, $id) {
        $categorytransaction = $this->CategoryTransactionRepo->find($id);
        if (isset($categorytransaction->id)) {
            $data= array();
            if (($request->input('name'))) { $data += ['name' => $request->input('name')]; }
            $categorytransaction = $this->CategoryTransactionRepo->update($categorytransaction, $data);
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => __('CategoryTransaction updated'),
                'data'    => $categorytransaction,
            ];
            return response()->json($response, 200);
        }
        $response = [
            'status'  => 'FAILED',
            'code'    => 500,
            'message' => __('Item dont exists') . '.',
        ];
        return response()->json($response, 500);
    }

    public function delete(Request $request, $id) {
        try {
            if ($this->CategoryTransactionRepo->find($id)) {
                $categorytransaction = $this->CategoryTransactionRepo->find($id);
                $categorytransaction = $this->CategoryTransactionRepo->delete($categorytransaction, ['active' => 0]);
                $categorytransaction = $categorytransaction->delete();
                $response = [ 
                    'status'  => 'OK',
                    'code'    => 200,
                    'message' => __('CategoryTransaction Deleted Successfully'),
                    'data'    => $categorytransaction,
                ];
                return response()->json($response, 200);
            }
            else {
                $response = [
                    'status'  => 'OK',
                    'code'    => 404,
                    'message' => __('CategoryTransaction not Found'),
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
        
        $categorytransaction = $this->CategoryTransactionRepo->find($id);
        if (isset($categorytransaction->active)) {
            if($categorytransaction->active == 0){
                $data = ['active' => 1];
            }else{
                $data = ['active' => 0];
            }
            $categorytransaction = $this->CategoryTransactionRepo->update($categorytransaction, $data);
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => __('Status Item updated'),
                'data'    => $categorytransaction,
            ];
            return response()->json($response, 200);
        }
        $response = [
            'status'  => 'FAILED',
            'code'    => 500,
            'message' => __('Item does not exist') . '.',
        ];
        return response()->json($response, 500);
    }
    public function custom_message() {
        
        return [
            'name.required'=> __('The name is required'),
        ];
    }
}

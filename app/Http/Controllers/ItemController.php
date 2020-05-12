<?php

namespace App\Http\Controllers;

use App\Http\Models\Repositories\ItemRepo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{   
    private $ItemRepo;
    public function __construct(ItemRepo $ItemRepo) {
        $this->ItemRepo = $ItemRepo;
    }
    public function all() {
        try { $item = $this->ItemRepo->all();
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => __('Item Obtained Correctly'),
                'data'    => $item,
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
            $item = $this->ItemRepo->find($id);
            if (isset($item->id)) {
                $response = [
                    'status'  => 'OK',
                    'code'    => 200,
                    'message' => __('Item Obtained Correctly'),
                    'data'    => $item,
                ];
                return response()->json($response, 200);
            }
            $response = [
                'status'  => 'FAILED',
                'code'    => 404,
                'message' => __('Not Data with this Item') . '.',
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
            'name' =>'required|max:60|','cost_unit' =>'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/','total' =>'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/','notes' =>'required|max:255|','order' =>'required|max:11|','quantity' =>'required|max:11|','category_item_id' =>'required|max:20|','invoice_id' =>'required|max:20|',
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
            "name"=> $request->input("name"),"cost_unit"=> $request->input("cost_unit"),"total"=> $request->input("total"),"notes"=> $request->input("notes"),"order"=> $request->input("order"),"quantity"=> $request->input("quantity"),"category_item_id"=> $request->input("category_item_id"),"invoice_id"=> $request->input("invoice_id"),
            ];
            $item= $this->ItemRepo->store($data);
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => __('Item saved correctly'),
                'data'    => $item,
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
        $item = $this->ItemRepo->find($id);
        if (isset($item->id)) {
            $data= array();
            if (($request->input('name'))) { 
                if (($request->input("name"))) { $data += ["name" => $request->input("name")]; };if (($request->input("account_id"))) { $data += ["account_id" => $request->input("account_id")]; };if (($request->input("category_transaction_id"))) { $data += ["category_transaction_id" => $request->input("category_transaction_id")]; };if (($request->input("invoice_id"))) { $data += ["invoice_id" => $request->input("invoice_id")]; };if (($request->input("amount"))) { $data += ["amount" => $request->input("amount")]; };if (($request->input("comission"))) { $data += ["comission" => $request->input("comission")]; };if (($request->input("dolar_tax"))) { $data += ["dolar_tax" => $request->input("dolar_tax")]; };if (($request->input("dolar_tax_acquired"))) { $data += ["dolar_tax_acquired" => $request->input("dolar_tax_acquired")]; };if (($request->input("dolar_amount"))) { $data += ["dolar_amount" => $request->input("dolar_amount")]; };
            }
            $item = $this->ItemRepo->update($item, $data);
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => __('Item updated'),
                'data'    => $item,
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
            if ($this->ItemRepo->find($id)) {
                $item = $this->ItemRepo->find($id);
                $item = $this->ItemRepo->delete($item, ['active' => 0]);
                $item = $item->delete();
                $response = [ 
                    'status'  => 'OK',
                    'code'    => 200,
                    'message' => __('Item Deleted Successfully'),
                    'data'    => $item,
                ];
                return response()->json($response, 200);
            }
            else {
                $response = [
                    'status'  => 'OK',
                    'code'    => 404,
                    'message' => __('Item not Found'),
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
        
        $item = $this->ItemRepo->find($id);
        if (isset($item->active)) {
            if($item->active == 0){
                $data = ['active' => 1];
            }else{
                $data = ['active' => 0];
            }
            $item = $this->ItemRepo->update($item, $data);
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => __('Status Item updated'),
                'data'    => $item,
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

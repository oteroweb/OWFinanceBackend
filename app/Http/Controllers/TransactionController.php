<?php

namespace App\Http\Controllers;

use App\Http\Models\Repositories\TransactionRepo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{   
    private $TransactionRepo;
    public function __construct(TransactionRepo $TransactionRepo) {
        $this->TransactionRepo = $TransactionRepo;
    }
    public function all() {
        try { $transaction = $this->TransactionRepo->all();
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => __('Transaction Obtained Correctly'),
                'data'    => $transaction,
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
            $transaction = $this->TransactionRepo->find($id);
            if (isset($transaction->id)) {
                $response = [
                    'status'  => 'OK',
                    'code'    => 200,
                    'message' => __('Transaction Obtained Correctly'),
                    'data'    => $transaction,
                ];
                return response()->json($response, 200);
            }
            $response = [
                'status'  => 'FAILED',
                'code'    => 404,
                'message' => __('Not Data with this Transaction') . '.',
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
            'name' =>'required|max:60|',
            'account_id' =>'required|max:20|',
            'category_transaction_id' =>'required|max:20|',
            'invoice_id' =>'required|max:20|',
            'amount' =>'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'comission' =>'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'dolar_tax' =>'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'dolar_tax_acquired' =>'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'dolar_amount' =>'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            
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
            'account_id'=> $request->input('account_id'),
            'category_transaction_id'=> $request->input('category_transaction_id'),
            'invoice_id'=> $request->input('invoice_id'),
            'amount'=> $request->input('amount'),
            'comission'=> $request->input('comission'),
            'dolar_tax'=> $request->input('dolar_tax'),
            'dolar_tax_acquired'=> $request->input('dolar_tax_acquired'),
            'dolar_amount'=> $request->input('dolar_amount'),
            
            ];
            $transaction= $this->TransactionRepo->store($data);
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => __('Transaction saved correctly'),
                'data'    => $transaction,
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
        $transaction = $this->TransactionRepo->find($id);
        if (isset($transaction->id)) {
            $data= array();
            if (($request->input('name'))) { 
                if (($request->input('name'))) { $data += ['name' => $request->input('name')]; };
                if (($request->input('account_id'))) { $data += ['account_id' => $request->input('account_id')]; };
                if (($request->input('category_transaction_id'))) { $data += ['category_transaction_id' => $request->input('category_transaction_id')]; };
                if (($request->input('invoice_id'))) { $data += ['invoice_id' => $request->input('invoice_id')]; };
                if (($request->input('amount'))) { $data += ['amount' => $request->input('amount')]; };
                if (($request->input('comission'))) { $data += ['comission' => $request->input('comission')]; };
                if (($request->input('dolar_tax'))) { $data += ['dolar_tax' => $request->input('dolar_tax')]; };
                if (($request->input('dolar_tax_acquired'))) { $data += ['dolar_tax_acquired' => $request->input('dolar_tax_acquired')]; };
                if (($request->input('dolar_amount'))) { $data += ['dolar_amount' => $request->input('dolar_amount')]; };
                
            }
            $transaction = $this->TransactionRepo->update($transaction, $data);
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => __('Transaction updated'),
                'data'    => $transaction,
            ];
            return response()->json($response, 200);
        }
        $response = [
            'status'  => 'FAILED',
            'code'    => 500,
            'message' => __('Transaction dont exists') . '.',
        ];
        return response()->json($response, 500);
    }

    public function delete(Request $request, $id) {
        try {
            if ($this->TransactionRepo->find($id)) {
                $transaction = $this->TransactionRepo->find($id);
                $transaction = $this->TransactionRepo->delete($transaction, ['active' => 0]);
                $transaction = $transaction->delete();
                $response = [ 
                    'status'  => 'OK',
                    'code'    => 200,
                    'message' => __('Transaction Deleted Successfully'),
                    'data'    => $transaction,
                ];
                return response()->json($response, 200);
            }
            else {
                $response = [
                    'status'  => 'OK',
                    'code'    => 404,
                    'message' => __('Transaction not Found'),
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
        
        $transaction = $this->TransactionRepo->find($id);
        if (isset($transaction->active)) {
            if($transaction->active == 0){
                $data = ['active' => 1];
            }else{
                $data = ['active' => 0];
            }
            $transaction = $this->TransactionRepo->update($transaction, $data);
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => __('Status Transaction updated'),
                'data'    => $transaction,
            ];
            return response()->json($response, 200);
        }
        $response = [
            'status'  => 'FAILED',
            'code'    => 500,
            'message' => __('Transaction does not exist') . '.',
        ];
        return response()->json($response, 500);
    }
    public function custom_message() {
        
        return [
            'name.required'=> __('The name is required'),
        ];
    }
    protected function get_string_between( $string, $start, $end ) {
        $string = ' ' . $string;
        $ini = strpos( $string, $start );
        if ( $ini == 0 ) return '';
        $ini += strlen( $start );
        $len = strpos( $string, $end, $ini ) - $ini;
        return substr( $string, $ini, $len );
    }
}

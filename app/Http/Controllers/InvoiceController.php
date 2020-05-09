<?php

namespace App\Http\Controllers;

use App\Http\Models\Repositories\InvoiceRepo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{   
    private $InvoiceRepo;
    public function __construct(InvoiceRepo $InvoiceRepo) {
        $this->InvoiceRepo = $InvoiceRepo;
    }
    public function all() {
        try { $invoice = $this->InvoiceRepo->all();
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => __('Invoice Obtained Correctly'),
                'data'    => $invoice,
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
            $invoice = $this->InvoiceRepo->find($id);
            if (isset($invoice->id)) {
                $response = [
                    'status'  => 'OK',
                    'code'    => 200,
                    'message' => __('Invoice Obtained Correctly'),
                    'data'    => $invoice,
                ];
                return response()->json($response, 200);
            }
            $response = [
                'status'  => 'FAILED',
                'code'    => 404,
                'message' => __('Not Data with this Invoice') . '.',
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
            'cost_unit'=> 'required',
            'total'=> 'required',
            'notes'=> 'required',
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
            'cost_unit'=> $request->input('cost_unit'),
            'total'=> $request->input('total'),
            'notes'=> $request->input('notes'),
            ];
            $invoice= $this->InvoiceRepo->store($data);
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => __('Invoice saved correctly'),
                'data'    => $invoice,
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
        $invoice = $this->InvoiceRepo->find($id);
        if (isset($invoice->id)) {
            $data= array();
            if (($request->input('cost_unit'))) { $data += ['cost_unit' => $request->input('cost_unit')]; }
            if (($request->input('total'))) { $data += ['total' => $request->input('total')]; }
            if (($request->input('notes'))) { $data += ['notes' => $request->input('notes')]; }
            $invoice = $this->InvoiceRepo->update($invoice, $data);
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => __('Invoice updated'),
                'data'    => $invoice,
            ];
            return response()->json($response, 200);
        }
        $response = [
            'status'  => 'FAILED',
            'code'    => 500,
            'message' => __('Invoice dont exists') . '.',
        ];
        return response()->json($response, 500);
    }

    public function delete(Request $request, $id) {
        try {
            if ($this->InvoiceRepo->find($id)) {
                $invoice = $this->InvoiceRepo->find($id);
                $invoice = $this->InvoiceRepo->delete($invoice, ['active' => 0]);
                $invoice = $invoice->delete();
                $response = [ 
                    'status'  => 'OK',
                    'code'    => 200,
                    'message' => __('Invoice Deleted Successfully'),
                    'data'    => $invoice,
                ];
                return response()->json($response, 200);
            }
            else {
                $response = [
                    'status'  => 'OK',
                    'code'    => 404,
                    'message' => __('Invoice not Found'),
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
        
        $invoice = $this->InvoiceRepo->find($id);
        if (isset($invoice->active)) {
            if($invoice->active == 0){
                $data = ['active' => 1];
            }else{
                $data = ['active' => 0];
            }
            $invoice = $this->InvoiceRepo->update($invoice, $data);
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => __('Status Invoice updated'),
                'data'    => $invoice,
            ];
            return response()->json($response, 200);
        }
        $response = [
            'status'  => 'FAILED',
            'code'    => 500,
            'message' => __('Invoice does not exist') . '.',
        ];
        return response()->json($response, 500);
    }
    public function custom_message() {
        
        return [
            'name.required'=> __('The name is required'),
        ];
    }
}

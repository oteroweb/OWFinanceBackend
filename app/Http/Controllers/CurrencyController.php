<?php
    
    namespace App\Http\Controllers;
   
    use App\Http\Models\Repositories\CurrencyRepo;
    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller as BaseController;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\Log;
    
    class CurrencyController extends Controller {
        private $CurrencyRepo;
        public function __construct(CurrencyRepo $CurrencyRepo) {
            $this->CurrencyRepo = $CurrencyRepo;
        }
        public function all() {
            try { $currency = $this->CurrencyRepo->all();
                $response = [
                    'status'  => 'OK',
                    'code'    => 200,
                    'message' => __('Data Obtained Correctly'),
                    'data'    => $currency,
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
                $currency = $this->CurrencyRepo->find($id);
                if (isset($currency->id)) {
                    $response = [
                        'status'  => 'OK',
                        'code'    => 200,
                        'message' => __('Data Obtained Correctly'),
                        'data'    => $currency,
                    ];
                    return response()->json($response, 200);
                }
                $response = [
                    'status'  => 'FAILED',
                    'code'    => 404,
                    'message' => __('Not Data with this ID') . '.',
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
                'tax'=> 'required',                      
                'last_tax'=> 'required',                      
                'symbol'=> 'required',                      
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
                'tax'=> $request->input('tax'),
                'last_tax'=> $request->input('last_tax'),
                'symbol'=> $request->input('symbol'),
                ];
                $currency     = $this->CurrencyRepo->store($data);
                $response = [
                    'status'  => 'OK',
                    'code'    => 200,
                    'message' => __('Data saved correctly'),
                    'data'    => $currency,
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
                $currency = $this->CurrencyRepo->find($id);
                if (isset($currency->id)) {
                    $data= array();
                    if (($request->input('name'))) { $data += ['name' => $request->input('name')]; }
                    if (($request->input('tax'))) { $data += ['tax' => $request->input('tax')]; }
                    if (($request->input('last_tax'))) { $data += ['last_tax' => $request->input('last_tax')]; }
                    if (($request->input('symbol'))) { $data += ['symbol' => $request->input('symbol')]; }
                    $currency = $this->CurrencyRepo->update($currency, $data);
                    $response = [
                        'status'  => 'OK',
                        'code'    => 200,
                        'message' => __('Item updated'),
                        'data'    => $currency,
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
                if ($this->CurrencyRepo->find($id)) {
                    $currency = $this->CurrencyRepo->find($id);
                    $currency = $this->CurrencyRepo->delete($currency, ['active' => 0]);
                    $currency = $currency->delete();
                    $response = [ 
                        'status'  => 'OK',
                        'code'    => 200,
                        'message' => __('Deleted Successfully'),
                        'data'    => $currency,
                    ];
                    return response()->json($response, 200);
                }
                else {
                    $response = [
                        'status'  => 'OK',
                        'code'    => 404,
                        'message' => __('Currency not Found'),
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
            
            $currency = $this->CurrencyRepo->find($id);
            if (isset($currency->active)) {
                if($currency->active == 0){
                    $data = ['active' => 1];
                }else{
                    $data = ['active' => 0];
                }
                $currency = $this->CurrencyRepo->update($currency, $data);
                $response = [
                    'status'  => 'OK',
                    'code'    => 200,
                    'message' => __('Status Item updated'),
                    'data'    => $currency,
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

<?php
    
    namespace App\Http\Controllers;
   
    use App\Http\Models\Repositories\CustomerRepo;
    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller as BaseController;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\Log;
    
    class CustomerController extends BaseController {
        private $CustomerRepo;
        public function __construct(CustomerRepo $CustomerRepo) {
            $this->CustomerRepo = $CustomerRepo;
        }
        public function all() {
            try { $customer = $this->CustomerRepo->all();
                $response = [
                    'status'  => 'OK',
                    'code'    => 200,
                    'message' => __('Data Obtained Correctly'),
                    'data'    => $customer,
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
                $customer = $this->CustomerRepo->find($id);
                if (isset($customer->id)) {
                    $response = [
                        'status'  => 'OK',
                        'code'    => 200,
                        'message' => __('Data Obtained Correctly'),
                        'data'    => $customer,
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
                'lastname'=> 'required',
                'subdomain'=> 'required',                      
                'user_id'=> 'required',                      
                'currency_id'=> 'required',                      
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
                'lastname'=> $request->input('lastname'),
                'subdomain'=> $request->input('subdomain'),
                'user_id'=> $request->input('user_id'),
                'currency_id'=> $request->input('currency_id'),
                ];
                $customer     = $this->CustomerRepo->store($data);
                $response = [
                    'status'  => 'OK',
                    'code'    => 200,
                    'message' => __('Data saved correctly'),
                    'data'    => $customer,
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
                $customer = $this->CustomerRepo->find($id);
                if (isset($customer->id)) {
                    $data= array();
                    if (($request->input('name'))) { $data += ['name' => $request->input('name')]; }
                    if (($request->input('lastname'))) { $data += ['lastname' => $request->input('lastname')]; }
                    if (($request->input('subdomain'))) { $data += ['subdomain' => $request->input('subdomain')]; }
                    if (($request->input('user_id'))) { $data += ['user_id' => $request->input('user_id')]; }
                    if (($request->input('currency_id'))) { $data += ['currency_id' => $request->input('currency_id')]; }
                    $customer = $this->CustomerRepo->update($customer, $data);
                    $response = [
                        'status'  => 'OK',
                        'code'    => 200,
                        'message' => __('Item updated'),
                        'data'    => $customer,
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
                if ($this->CustomerRepo->find($id)) {
                    $customer = $this->CustomerRepo->find($id);
                    $customer = $this->CustomerRepo->delete($customer, ['active' => 0]);
                    $customer = $customer->delete();
                    $response = [ 
                        'status'  => 'OK',
                        'code'    => 200,
                        'message' => __('Deleted Successfully'),
                        'data'    => $customer,
                    ];
                    return response()->json($response, 200);
                }
                else {
                    $response = [
                        'status'  => 'OK',
                        'code'    => 404,
                        'message' => __('Customer not Found'),
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
            
            $customer = $this->CustomerRepo->find($id);
            if (isset($customer->active)) {
                if($customer->active == 0){
                    $data = ['active' => 1];
                }else{
                    $data = ['active' => 0];
                }
                $customer = $this->CustomerRepo->update($customer, $data);
                $response = [
                    'status'  => 'OK',
                    'code'    => 200,
                    'message' => __('Status Item updated'),
                    'data'    => $customer,
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

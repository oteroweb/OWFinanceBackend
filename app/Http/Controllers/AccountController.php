<?php
    
    namespace App\Http\Controllers;
   
    use App\Http\Models\Repositories\AccountRepo;
    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller as BaseController;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\Log;
    
    class AccountController extends Controller {
        private $AccountRepo;
        public function __construct(AccountRepo $AccountRepo) {
            $this->AccountRepo = $AccountRepo;
        }
        public function all() {
            try { $account = $this->AccountRepo->all();
                $response = [
                    'status'  => 'OK',
                    'code'    => 200,
                    'message' => __('Data Obtained Correctly'),
                    'data'    => $account,
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
                $account = $this->AccountRepo->find($id);
                if (isset($account->id)) {
                    $response = [
                        'status'  => 'OK',
                        'code'    => 200,
                        'message' => __('Data Obtained Correctly'),
                        'data'    => $account,
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
                'initial'=> 'required',
                'current'=> 'required',                      
                'rate'=> 'required',                      
                'customer_id'=> 'required',                      
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
                'initial'=> $request->input('initial'),
                'current'=> $request->input('current'),
                'rate'=> $request->input('rate'),
                'customer_id'=> $request->input('customer_id'),
                'currency_id'=> $request->input('currency_id'),
                ];
                $account     = $this->AccountRepo->store($data);
                $response = [
                    'status'  => 'OK',
                    'code'    => 200,
                    'message' => __('Data saved correctly'),
                    'data'    => $account,
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
                $account = $this->AccountRepo->find($id);
                if (isset($account->id)) {
                    $data= array();
                    if (($request->input('name'))) { $data += ['name' => $request->input('name')]; }
                    if (($request->input('initial'))) { $data += ['initial' => $request->input('initial')]; }
                    if (($request->input('current'))) { $data += ['current' => $request->input('current')]; }
                    if (($request->input('rate'))) { $data += ['rate' => $request->input('rate')]; }
                    if (($request->input('customer_id'))) { $data += ['customer_id' => $request->input('customer_id')]; }
                    if (($request->input('currency_id'))) { $data += ['currency_id' => $request->input('currency_id')]; }
                    $account = $this->AccountRepo->update($account, $data);
                    $response = [
                        'status'  => 'OK',
                        'code'    => 200,
                        'message' => __('Item updated'),
                        'data'    => $account,
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
                if ($this->AccountRepo->find($id)) {
                    $account = $this->AccountRepo->find($id);
                    $account = $this->AccountRepo->delete($account, ['active' => 0]);
                    $account = $account->delete();
                    $response = [ 
                        'status'  => 'OK',
                        'code'    => 200,
                        'message' => __('Deleted Successfully'),
                        'data'    => $account,
                    ];
                    return response()->json($response, 200);
                }
                else {
                    $response = [
                        'status'  => 'OK',
                        'code'    => 404,
                        'message' => __('Account not Found'),
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
            
            $account = $this->AccountRepo->find($id);
            if (isset($account->active)) {
                if($account->active == 0){
                    $data = ['active' => 1];
                }else{
                    $data = ['active' => 0];
                }
                $account = $this->AccountRepo->update($account, $data);
                $response = [
                    'status'  => 'OK',
                    'code'    => 200,
                    'message' => __('Status Item updated'),
                    'data'    => $account,
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

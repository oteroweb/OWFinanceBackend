<?php
    
    namespace App\Http\Models\Repositories;
    
    use Mockery\Matcher\Type;
    use Illuminate\Support\Facades\Log;
    use App\Http\Models\Entities\Invoice;
    
    class InvoiceRepo {
        public function all() {
            $invoice = Invoice::whereIn('active', [1,0])->with(['transaction','item'])
            ->get();            
            return $invoice;
        }
        public function find($id) {
            $invoice = Invoice::with([])->find($id);
            return $invoice;
        }        
        public function store($data) {            
            $invoice = new Invoice();
            $invoice->fill($data);
            $invoice->save();
            return $invoice;
        }        
        public function update($invoice, $data) {
            $invoice->fill($data);
            $invoice->save();
            return $invoice;
        }
        public function delete($invoice, $data) {
            $invoice->fill($data);
            $invoice->save();
            return $invoice;
        }
    }
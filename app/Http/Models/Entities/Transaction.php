<?php

namespace App\Http\Models\Entities;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;
    use Notifiable;
    protected $table      = 'transactions';
    protected $primaryKey = 'id';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
        'deleted_at' => 'datetime:Y-m-d',
    ];
    protected $fillable   = [ 'id', 'name', 'account_id', 'category_transaction_id', 'invoice_id', 'amount', 'comission', 'dolar_tax', 'dolar_tax_acquired', 'dolar_amount', 'active', 'created_at', 'updated_at', 'deleted_at' ];

    public function account()
    {
        return $this->belongsTo('App\Http\Models\Entities\Account');
    }
    public function categoryTransaction()
    {
        return $this->belongsTo('App\Http\Models\Entities\CategoryTransaction');
    }
    public function invoice()
    {   
        return $this->belongsTo('App\Http\Models\Entities\Invoice');
    }
}

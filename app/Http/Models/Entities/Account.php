<?php

namespace App\Http\Models\Entities;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;
    use Notifiable;
    protected $table      = 'accounts';
    protected $primaryKey = 'id';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
        'deleted_at' => 'datetime:Y-m-d',
    ];
    protected $fillable   = [
        'id',
        'name',
        'initial',
        'current',
        'rate',
        'customer_id',
        'currency_id',
        'active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function customer()
    {
        return $this->belongsTo('App\Http\Models\Entities\Customer');
    }
    public function account()
    {
        return $this->belongsTo('App\Http\Models\Entities\Account');
    }
    public function transaction()
    {
        return $this->hasOne('App\Http\Models\Entities\Transaction');
    }
}

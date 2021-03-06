<?php

namespace App\Http\Models\Entities;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use SoftDeletes;
    use Notifiable;
    protected $table      = 'currencies';
    protected $primaryKey = 'id';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
        'deleted_at' => 'datetime:Y-m-d',
    ];
    protected $fillable   = [ 'id', 'name', 'tax', 'last_tax', 'symbol', 'symbol_native', 'decimal_digits', 'rounding', 'name_plural', 'code', 'active', 'created_at', 'updated_at', 'deleted_at' ];

    public function customer()
    {
        return $this->hasOne('App\Http\Models\Entities\Customer');
    }
    public function account()
    {
        return $this->hasOne('App\Http\Models\Entities\Account');
    }
}

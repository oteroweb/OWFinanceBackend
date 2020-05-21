<?php

namespace App\Http\Models\Entities;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;
    use Notifiable;
    protected $table      = 'invoices';
    protected $primaryKey = 'id';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
        'deleted_at' => 'datetime:Y-m-d',
    ];
    protected $fillable   = [
        'id',
        'name',
        'cost_unit',
        'total',
        'notes',
        'active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public function transaction()
    {
        return $this->hasOne('App\Http\Models\Entities\Transaction');
    }
    public function item()
    {
        return $this->hasOne('App\Http\Models\Entities\Item');
    }
}

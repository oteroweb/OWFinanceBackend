<?php

namespace App\Http\Models\Entities;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryTransaction extends Model
{
    use SoftDeletes; 
    use Notifiable;
    protected $table      = 'category_transactions';
    protected $primaryKey = 'id';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
        'deleted_at' => 'datetime:Y-m-d',
    ];
    protected $fillable   = [
        'id',
        'name',
        'active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;
use App\Traits\HasUser;
use App\Models\Category;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use HasUuid, SoftDeletes, HasUser, LogsActivity;

    protected static $logName = 'product';
    protected static $logAttributes = ['*'];
    protected static $logAttributesToIgnore = ['uuid', 'id', 'user_id'];
    protected static $recordEvents = ['updated'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 
        'user_id',
        'name', 
        'description', 
        'category_id',
        'price',
        'stock',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

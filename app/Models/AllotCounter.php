<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AllotCounter extends Model
{
    use HasFactory,SoftDeletes;

    public function userName()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function counterName()
    {
        return $this->belongsTo(Counter::class,'counter_id','id');
    }
    public function serviceName()
    {
        return $this->belongsTo(Service::class,'service_id','id');
    }
}

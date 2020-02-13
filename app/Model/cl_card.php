<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class cl_card extends Model
{
    protected $table = 'cl_card';
    protected $primaryKey = 'id';

    public $timestamps = true;


    public function cl_goods()
    {
        return $this->hasMany(cl_goods::class, 'cd_id', 'id');
    }

}

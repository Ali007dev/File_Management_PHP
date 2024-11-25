<?php

namespace App\Traits;

use Exception;

trait HasFilter
{
    public function scopeFilter($query)
    {
        if(!request()->isMethod('get')){
            throw new Exception("You can only use filter for 'get' method.");
        }
        $filterBy = array_flip($this->filterableBy??[]);
        // dd($filterBy);
        $params = request()->all();
        // dd($params);
        $filters = array_intersect_key($params,$filterBy);
        // dd($filters);
        foreach($filters as $key=>$value){
            $query->where($key,$value);
        }
    }
}

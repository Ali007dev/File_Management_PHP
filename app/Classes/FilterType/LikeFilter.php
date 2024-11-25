<?php
namespace App\Classes\FilterType;
class LikeFilter extends FilterType{
    public function __construct($key) {
        parent::__construct($key);
    }
    public function apply($query){
        $value = request($this->key);
        if($value ==null){
            return;
        }
        $splittedKey = $this->splitSearchKey($this->key);
        $relation = $splittedKey['relation'];
        $field = $splittedKey['field'];
        if($relation){
            $query->whereHas($relation,fn($q)=>$q->where($field,'like',"%$value%"));
        }else {
            $query->where($field,'like',"%$value%");
        }
    }
}

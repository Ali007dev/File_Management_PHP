<?php

namespace App\Classes\FilterType;

class RangeFilter extends FilterType
{
    public function __construct($key)
    {
        parent::__construct($key);
    }
    public function apply($query)
    {
        $equalValue = request($this->key);
        $fromValue = request($this->key . "_from");
        $toValue = request($this->key . "_to");
        if (!$equalValue && !$fromValue && !$toValue) {
            return;
        }
        $splittedKey = $this->splitSearchKey($this->key);
        $relation = $splittedKey['relation'];
        $field = $splittedKey['field'];
        $queryFunction = fn ($q) =>
        $q->when($equalValue, fn ($qq) => $qq->where($field, $equalValue))
            ->when($fromValue, fn ($qq) => $qq->where($field, ">=", $fromValue))
            ->when($toValue, fn ($qq) => $qq->where($field, "<=", $toValue));
        if ($relation) {
            $query->whereHas(
                $relation,
                $queryFunction
            );
        } else {
            $queryFunction($query);
        }
    }
}

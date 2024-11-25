<?php

namespace App\Classes\FilterType;

class EqualFilter extends FilterType
{
    public function __construct($key)
    {
        parent::__construct($key);
    }
    public function setKey($key)
    {
        $this->key = $key;
    }
    public function apply($query)
    {
        $value = request($this->key);
        if ($value == null) {
            return;
        }
        $splittedKey = $this->splitSearchKey($this->key);
        if ($splittedKey['relation']) {
            $query->whereHas(
                $splittedKey['relation'],
                fn ($q) => $q->where($splittedKey['field'], $value)
            );
        } else {
            $query->where(
                $splittedKey['field'],
                $value
            );
        }
    }
}

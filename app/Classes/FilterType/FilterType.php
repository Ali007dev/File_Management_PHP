<?php
namespace App\Classes\FilterType;
abstract class FilterType {

    public string $key;
    protected function __construct(string $key) {
        $this->key = $key;
    }
    public abstract function apply($query);

    protected function splitSearchKey($searchKey)
    {
        $lastDotPosition = strrpos($searchKey, '.');
        if ($lastDotPosition === false) {
            return [
                'relation' => '',
                'field' => $searchKey,
            ];
        }
        $relation = substr($searchKey, 0, $lastDotPosition);
        $field = substr($searchKey, $lastDotPosition + 1);

        return [
            'relation' => $relation,
            'field' => $field,
        ];
    }
}

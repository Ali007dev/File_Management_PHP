<?php

namespace App\Traits;

use Illuminate\Pagination\AbstractPaginator;
trait ResourcePaginator
{
    public static function collection($data)
    {
        if (is_a($data, AbstractPaginator::class)) {
            $paginator = $data;
            $paginator->setCollection(
                $paginator->getCollection()->map(
                    //apply resource on this single instance
                    fn ($listing) => new static($listing)
                )
            );
            if (method_exists($data, 'total')) {
                $total = $data->total();
                // $paginator->appends(['total' => $total]);
                $paginationData = $paginator->toArray();
                $paginationData['total'] = $total;
                return $paginationData;
            }
            return $data;
        }
        return parent::collection($data);
    }
}

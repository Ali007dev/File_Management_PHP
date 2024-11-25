<?php

namespace App\Traits;

use Exception;
use App\Classes\FilterType\EqualFilter;
use App\Classes\FilterType\FilterType;
use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\LinesOfCode\IllogicalValuesException;

trait Filterable
{
    protected final function scopeFilter($query)
    {
        if (!request()->isMethod('get')) {
            throw new Exception("You can only use filters for 'get' method.");
        }
        $filterables = $this->getRawFilterables();
        $equalFilter = new EqualFilter("temp");
        foreach ($filterables as $filterable) {
            if ($filterable instanceof FilterType) {
                $filterable->apply($query);
            } else {
                $equalFilter->setKey($filterable);
                $equalFilter->apply($query);
            }
        }
    }

    public function getRawFilterables($prefix = null, $loadedModels = [])
    {
        $filters = [];
        $prefix = $prefix ? ($prefix . ".") : "";
        $loadedModels[] = $this::class;
        foreach ($this->filterable ?? [] as $key => $value) {
            if (is_int($key)) {
                $filters[] = $prefix . $value;
            } else {
                if (is_subclass_of($value, Model::class)) {
                    // Instance of model
                    if (!in_array($value, $loadedModels)) {
                        $filters = array_merge(
                            $filters,
                            app($value)->getRawFilterables($prefix . $key, $loadedModels)
                        );
                    }
                } elseif (is_subclass_of($value, FilterType::class)) {
                    // Value with special filter
                    $filters[] = new $value($prefix . $key);
                } else {
                    throw new IllogicalValuesException(
                        "Provided $value, Expected: subclass of Model, subclass of FilterType."
                    );
                }
            }
        }
        return array_values($filters);
    }

    public function getFilterables()
    {
        $rawFilterables = $this->getRawFilterables();
        $filters = [];
        foreach (collect($rawFilterables) as $filterable) {
            if ($filterable instanceof FilterType) {
                $filters[$filterable->key] = basename(str_replace('\\', '/', $filterable::class));
            } else {
                $filters[$filterable] = basename(str_replace('\\', '/', EqualFilter::class));
            }
        }
        return $filters;
    }
}

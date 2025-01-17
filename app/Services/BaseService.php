<?php

namespace App\Services;

use App\Traits\Filterable;
use App\Traits\HasFilter;
use App\Traits\StoresImage;

use Illuminate\Database\Eloquent\Model;

class BaseService
{
    protected $targetModel;
    protected $relations;

    public function __construct(Model $model)
    {
        $this->targetModel = $model;
    }


    public function index()
    {
        return $this->targetModel::when(
            $this->modelHasFilter(),
            fn ($q) => $q->filter()
        )->with($this->targetModel->relations ??[])
        ->when(
            $this->modelHasFilterableTrait(),
            fn ($q) => $q->filter()
        )->paginate();
    }
    public function all()
    {
        return $this->targetModel::when(
            $this->modelHasFilter(),
            fn ($q) => $q->filter()
        )
        ->with($this->targetModel->targetWith ??[])
        ->when(
            $this->modelHasFilterableTrait(),
            fn ($q) => $q->filter()
        )->get();
    }

    public function show($id)
    {
        $model = $this->targetModel::with(
            $this->targetModel->relations ??
                $this->targetModel::$Relations ??
                []
        )->findOrFail($id);
        return $model;
    }

    public function create($data)
    {
        $model = $this->targetModel::create($data);
        if ($this->shouldCallUpdateImage()) {
            $model->updateImage(
                request()->file(
                    $this->targetModel->getImageFieldName()
                )
            );
        }
        return $model;
    }

    public function update($id, $data)
    {
        $model = $this->targetModel::findOrFail($id);
        $model->update($data);
        if ($this->shouldCallUpdateImage()) {
            $model->updateImage(
                request()->file(
                    $this->targetModel->getImageFieldName()
                )
            );
        }
        return $model;
    }

    public function delete($ids)
    {
        $ids = explode(',', $ids);
        if ($this->modelStoresImage()) {
            $models = $this->targetModel::whereIn('id', $ids)
                ->get();
            foreach ($models as $model) {
                $model->deleteImage();
            }
        }
        $c = $this->targetModel::destroy($ids);
        return $c;
    }
    private function shouldCallUpdateImage(): bool
    {
        return
            $this->modelStoresImage()
            &&
            request()->hasFile(
                $this->targetModel->getImageFieldName()
            );
    }
    private function modelStoresImage(): bool
    {
        return
            in_array(
                StoresImage::class,
                class_uses($this->targetModel::class)
            );
    }

    private function modelHasFilter()
    {
        return in_array(
            HasFilter::class,
            class_uses($this->targetModel::class)
        );
    }
    private function modelHasFilterableTrait()
    {
        return in_array(
            Filterable::class,
            class_uses($this->targetModel::class)
        );
    }
}

<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\Traits\StoresImage;
use App\Traits\HasFilter;
use App\Traits\Filterable;

class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model::when($this->modelHasFilter(), fn ($q) => $q->filter())
            ->with($this->model->targetWith ?? [])
            ->when($this->modelHasFilterableTrait(), fn ($q) => $q->filter())
            ->get();
    }

    public function index()
    {
        return $this->model::when($this->modelHasFilter(), fn ($q) => $q->filter())
            ->with($this->model->targetWith ?? [])
            ->when($this->modelHasFilterableTrait(), fn ($q) => $q->filter())
            ->paginate(10);
    }

    public function show($id)
    {

        return $this->model::with($this->model->relations ?? $this->model::$Relations ?? [])
            ->findOrFail($id);
    }
    public function create($data)
    {
        $model = $this->model::create($data);
        if ($this->shouldCallUpdateImage()) {
            $model->updateImage(
                request()->file(
                    $this->model->getImageFieldName()
                )
            );
        }
        return $model;
    }

    public function update($id, $data)
    {
        $model = $this->model::findOrFail($id);
        $model->update($data);
        if ($this->shouldCallUpdateImage()) {
            $model->updateImage(
                request()->file(
                    $this->model->getImageFieldName()
                )
            );
        }
        return $model;
    }

    public function delete($ids)
    {
        $ids = explode(',', $ids);
        if ($this->modelStoresImage()) {
            $models = $this->model::whereIn('id', $ids)
                ->get();
            foreach ($models as $model) {
                $model->deleteImage();
            }
        }
        $c = $this->model::destroy($ids);
        return $c;
    }

    private function updateImage(Model $model)
    {
        if (request()->hasFile($this->model->getImageFieldName())) {
            $model->updateImage(request()->file($this->model->getImageFieldName()));
        }
    }

    private function modelStoresImage(): bool
    {
        return
            in_array(
                StoresImage::class,
                class_uses($this->model::class)
            );
    }

    private function modelHasFilter()
    {
        return in_array(
            HasFilter::class,
            class_uses($this->model::class)
        );
    }
    private function modelHasFilterableTrait()
    {
        return in_array(
            Filterable::class,
            class_uses($this->model::class)
        );
    }

    private function shouldCallUpdateImage(): bool
    {
        return
            $this->modelStoresImage()
            &&
            request()->hasFile(
                $this->model->getImageFieldName()
            );
    }
}

<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class BaseCRUDController extends Controller
{
    private String $createRequest, $updateRequest, $resource;
    protected $service;
    public function __construct($service, String $createReq, String $updateReq, String $resource)
    {
        $this->service = $service;
        $this->createRequest = $createReq;
        $this->updateRequest = $updateReq;
        $this->resource = $resource;
    }
    protected function createRequest()
    {
        return app($this->createRequest);
    }
    protected function updateRequest()
    {
        return app($this->updateRequest);
    }

    protected function modelFromParam(string $model,$paramName=null) : Model {
        if(!$paramName){
            $paramName = camelCase(collect(explode('\\',$model))->last());
        }
        $id = request()->route($paramName);
        return $model::findOrFail($id);
    }

    protected function index()
    {
        $res = $this->service->index();
        return $this->success($this->resource::collection($res), __('messages.success'));
    }
    protected function all()
    {
        $res = $this->service->all();
        return $this->success($this->resource::collection($res), __('messages.success'));
    }

    protected function show($id)
    {
        $res = $this->service->show($id);
        return $this->success($this->resource::make($res), __('messages.success'));
    }

    protected function create(Request $request)
    {
        $data = $this->createRequest()->validated();
        $res = $this->service->create($data);
        $this->refreshModel($res);
        return $this->success($this->resource::make($res), __('messages.success'));
    }

    protected function update(Request $request, $id)
    {
        $data = $this->updateRequest()->validated();
        $res = $this->service->update($id,$data);
        return $this->success($this->resource::make($res), __('messages.success'));
    }

    protected function delete($ids)
    {
        $res = $this->service->delete($ids);
        $msg = $res?__('messages.success'):__('messages.non_deleted');
        return $this->success(message:$msg);
    }

    private function refreshModel($model){
        $oldAttributes = $model->getAttributes();
       // $model->refresh();
        $model->setRawAttributes(array_merge($oldAttributes,$model->getAttributes()));
    }
}

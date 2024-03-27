<?php

namespace App\Http\Controllers\Api;

use App\Models\ModelType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ModelType\ModelTypeResource;
use App\Http\Resources\ModelType\ModelTypeCollect;

class ModelTypeController extends Controller
{
    public function index()
    {
        $model_types = ModelType::latest()->paginate(5);
        
        return new ModelTypeCollect($model_types);
    }

    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'item_type'     => 'required',
            'content'       => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        $image->storeAs('public/modeltypes', $image->hashName());

        $model_type = ModelType::create([
            'image'         => $image->hashName(),
            'item_type'     => $request->item_type,
            'content'       => $request->content,
        ]);

        return new ModelTypeResource(true, 'Data Model Type Berhasil Ditambahkan!', $model_type);
    }

    public function show($id)
    {
        //find post by ID
        $model_type = ModelType::find($id);

        //return single post as a resource
        return new ModelTypeResource(true, 'Detail Data Model Type!', $model_type);
    }

    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'item_type'     => 'required',
            'content'       => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        //find model_type by ID
        $model_type = ModelType::find($id);

        //check if image is not empty
        if ($request->hasFile('image')) {

            //upload image
            $image = $request->file('image');
            $image->storeAs('public/modeltypes', $image->hashName());

            //delete old image
            Storage::delete('public/modeltypes/' . basename($model_type->image));

            //update model_type with new image
            $model_type->update([
                'image'         => $image->hashName(),
                'item_type'     => $request->item_type,
                'content'       => $request->content,
            ]);
        } else {

            //update model_type without image
            $model_type->update([
                'item_type'     => $request->item_type,
                'content'       => $request->content,
            ]);
        }

        //return response
        return new ModelTypeResource(true, 'Data Model Type Berhasil Diubah!', $model_type);
    }

    public function destroy($id)
    {
        //find model_type by ID
        $model_type = ModelType::find($id);

        //delete image
        Storage::delete('public/modeltypes/'.basename($model_type->image));

        //delete model_type
        $model_type->delete();

        //return response
        return new ModelTypeResource(true, 'Data Model Type Berhasil Dihapus!', null);
    }
}

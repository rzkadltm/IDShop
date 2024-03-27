<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Validator;
use App\Models\ModelType;
use App\Models\Advertisement;
use App\Http\Controllers\Controller;
use App\Http\Resources\Advertisement\AdvertisementCollect;
use App\Http\Resources\Advertisement\AdvertisementResource;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::latest()->paginate(5);
        
        return new AdvertisementCollect($advertisements);
    }

    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'modeltype_id'  => 'required|exists:model_types,id',
            'title'         => 'required',
            'address'       => 'required',
            'description'   => 'required',
            'model_name'    => 'required',
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        $image->storeAs('public/advertisements', $image->hashName());
        $advertisement = Advertisement::create([
            'title'         => $request->title,
            'modeltype_id'  => $modelType->id,
            'address'       => $request->address,
            'description'   => $request->description,
            'model_name'    => $request->model_name,
            'image'         => $image->hashName(),
        ]);

        return new AdvertisementResource(true, 'Data Advertisement Berhasil Ditambahkan!', $advertisement);
    }

    public function show($id)
    {
        $advertisement = Advertisement::find($id);

        if (!$advertisement) {
            return new AdvertisementResource(false, 'Data Advertisement Tidak Ditemukan!', null);
        }

        return new AdvertisementResource(true, 'Data Detail Advertisement', $advertisement);
    }

    public function destroy($id)
    {
        $advertisement = Advertisement::find($id);

        if (!$advertisement) {
            return new AdvertisementResource(false, 'Data Advertisement Tidak Ditemukan!', null);
        }

        Storage::delete('public/advertisements/'.basename($advertisement->image));
    
        $advertisement->delete();

        return new AdvertisementResource(true, 'Data Model Type Berhasil Dihapus!', null);
    }
}

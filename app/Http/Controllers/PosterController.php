<?php

namespace App\Http\Controllers;

use App\Models\Poster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PosterController extends Controller
{
    public function index(Request $request)
    {
        $data = Poster::where('title', 'LIKE', '%' . $request->search . '%')
                ->orWhere('publishing_year', 'LIKE', '%' . $request->search . '%')
                ->orderBy('id', 'DESC')
                ->paginate($request->perPage);

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'title'             =>  'required',
            'publishing_year'   =>  'required|numeric',
            'poster'            =>  'required'
        ]);

        if ($validator->fails()) {
            $response = [
                'success'   =>  false,
                'message'   =>  $validator->errors(),
            ];
            return response()->json($response);
        }
        $foto = '';
        if ($request->file('poster')) {
            $length = 7;
            $random = '';
            for ($i = 0; $i < $length; $i++) {
                $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
            }
            $name = 'poster-' . Str::upper($random);

            $file                 =   $request->file('poster');
            $file_name            =   $file->getClientOriginalName();
            $file_extension       =   $file->getClientOriginalExtension();
            $nama_file            =   $name . '.' . $file_extension;
            $tujuan_upload        =   public_path('attach');
            $file->move($tujuan_upload, $nama_file);
            $foto                 =   $nama_file;
        }

        $poster                   = new Poster;
        $poster->title            = $request->title;
        $poster->publishing_year  = $request->publishing_year;
        $poster->poster           = $foto;
        $poster->save();

        return response()->json([
            'success'       =>  201,
            'message'       =>  'Create data success',
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator  = Validator::make($request->all(), [
            'title'             =>  'required',
            'publishing_year'   =>  'required|numeric'
        ]);

        if ($validator->fails()) {
            $response = [
                'success'   =>  false,
                'message'   =>  $validator->errors(),
            ];
            return response()->json($response);
        }

        $poster                   = Poster::find($id);

        $foto = $poster->poster;
        if ($request->file('poster')) {
            if ($foto != "") {
                $temp_path = public_path('attach') . "/" . $foto;
                File::delete($temp_path);
            }
            $length = 7;
            $random = '';
            for ($i = 0; $i < $length; $i++) {
                $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
            }
            $name = 'poster-' . Str::upper($random);

            $file                 =   $request->file('poster');
            $file_name            =   $file->getClientOriginalName();
            $file_extension       =   $file->getClientOriginalExtension();
            $nama_file            =   $name . '.' . $file_extension;
            $tujuan_upload        =   public_path('attach');
            $file->move($tujuan_upload, $nama_file);
            $foto                 =   $nama_file;
        }
        $poster->title            = $request->title;
        $poster->publishing_year  = $request->publishing_year;
        $poster->poster           = $foto;
        $poster->save();

        return response()->json([
            'success'       =>  201,
            'message'       =>  'Update data success',
        ]);
    }

    public function destroy($id)
    {
        $data = Poster::where('id',$id)->first();
        if ($data->poster != "") {
            $temp_path = public_path('attach') . "/" . $data->poster;
            File::delete($temp_path);
        }
        $data->delete();

        if ($data) {
            return response()->json([
                'success'       =>  201,
                'message'       =>  'Delete data success',
            ]);
        } else {
            return response()->json([
                'success'       =>  302,
                'message'       =>  'Delete data success',
            ]);
        }
    }
}
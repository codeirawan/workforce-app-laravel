<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laratrust, DataTables, Lang;
use App\Models\Master\FileType;

class FileTypeController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-file-type')) return abort(404);

        return view('master.file-type.index');
    }

    public function data()
    {
        if (!Laratrust::isAbleTo('view-file-type')) return abort(404);

        $fileTypes = FileType::select('id', 'name');

        return DataTables::of($fileTypes)
            ->addColumn('action', function($fileType) {
                $edit = '<a href="' . route('master.file-type.edit', $fileType->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Edit') . '"><i class="la la-edit"></i></a>';
                $delete = '<a href="#" data-href="' . route('master.file-type.destroy', $fileType->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Delete') . '" data-toggle="modal" data-target="#modal-delete" data-key="' . $fileType->name . '"><i class="la la-trash"></i></a>';

                return (Laratrust::isAbleTo('update-file-type') ? $edit : '') . (Laratrust::isAbleTo('delete-file-type') ? $delete : '');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        if (!Laratrust::isAbleTo('create-file-type')) return abort(404);

        return view('master.file-type.create');
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('create-file-type')) return abort(404);

        $this->validate($request, [
            'nama' => ['required', 'string', 'max:255'],
        ]);

        $fileType = new FileType;
        $fileType->name = $request->nama;
        $fileType->save();

        $message = Lang::get('File type') . ' \'' . $fileType->name . '\' ' . Lang::get('successfully created.');
        return redirect()->route('master.file-type.index')->with('status', $message);
    }

    public function edit($id)
    {
        if (!Laratrust::isAbleTo('update-file-type')) return abort(404);

        $fileType = FileType::select('id', 'name')->findOrFail($id);

        return view('master.file-type.edit', compact('fileType'));
    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('update-file-type')) return abort(404);

        $fileType = FileType::findOrFail($id);

        $this->validate($request, [
            'nama' => ['required', 'string', 'max:255'],
        ]);

        $fileType->name = $request->nama;
        $fileType->save();

        $message = Lang::get('File type') . ' \'' . $fileType->name . '\' ' . Lang::get('successfully updated.');
        return redirect()->route('master.file-type.index')->with('status', $message);
    }

    public function destroy($id)
    {
        if (!Laratrust::isAbleTo('delete-file-type')) return abort(404);

        $fileType = FileType::findOrFail($id);
        $name = $fileType->name;
        $fileType->delete();

        $message = Lang::get('File type') . ' \'' . $name . '\' ' . Lang::get('successfully deleted.');
        return redirect()->route('master.file-type.index')->with('status', $message);
    }
}

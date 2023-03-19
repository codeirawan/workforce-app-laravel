<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laratrust, DataTables, Lang;
use App\Models\Master\Position;

class PositionController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-position')) return abort(404);

        return view('master.position.index');
    }

    public function data()
    {
        if (!Laratrust::isAbleTo('view-position')) return abort(404);

        $positions = Position::select('id', 'name');

        return DataTables::of($positions)
            ->addColumn('action', function($position) {
                $edit = '<a href="' . route('master.position.edit', $position->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Edit') . '"><i class="la la-edit"></i></a>';
                $delete = '<a href="#" data-href="' . route('master.position.destroy', $position->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Delete') . '" data-toggle="modal" data-target="#modal-delete" data-key="' . $position->name . '"><i class="la la-trash"></i></a>';

                return (Laratrust::isAbleTo('update-position') ? $edit : '') . (Laratrust::isAbleTo('delete-position') ? $delete : '');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        if (!Laratrust::isAbleTo('create-position')) return abort(404);

        return view('master.position.create');
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('create-position')) return abort(404);

        $this->validate($request, [
            'nama' => ['required', 'string', 'max:255'],
        ]);

        $position = new Position;
        $position->name = $request->nama;
        $position->save();

        $message = Lang::get('Position') . ' \'' . $position->name . '\' ' . Lang::get('successfully created.');
        return redirect()->route('master.position.index')->with('status', $message);
    }

    public function edit($id)
    {
        if (!Laratrust::isAbleTo('update-position')) return abort(404);

        $position = Position::select('id', 'name')->findOrFail($id);

        return view('master.position.edit', compact('position'));
    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('update-position')) return abort(404);

        $position = Position::findOrFail($id);

        $this->validate($request, [
            'nama' => ['required', 'string', 'max:255'],
        ]);

        $position->name = $request->nama;
        $position->save();

        $message = Lang::get('Position') . ' \'' . $position->name . '\' ' . Lang::get('successfully updated.');
        return redirect()->route('master.position.index')->with('status', $message);
    }

    public function destroy($id)
    {
        if (!Laratrust::isAbleTo('delete-position')) return abort(404);

        $position = Position::findOrFail($id);
        $name = $position->name;
        $position->delete();

        $message = Lang::get('Position') . ' \'' . $name . '\' ' . Lang::get('successfully deleted.');
        return redirect()->route('master.position.index')->with('status', $message);
    }
}

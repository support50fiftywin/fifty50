<?php

namespace Webkul\Sweepstakes\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Sweepstakes\Models\Sweepstakes;

class SweepstakesController extends Controller
{
    public function index()
    {
        $sweepstakes = Sweepstakes::orderBy('id', 'DESC')->get();

        return view('sweepstakes::admin.index', compact('sweepstakes'));
    }


    public function create()
    {
        return view('sweepstakes::admin.create');
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required',
            'prize_title'  => 'required',
            'start_date'   => 'required',
            'end_date'     => 'required',
            'status'       => 'required',
            'image'        => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('sweepstakes', 'public');
        }

        Sweepstakes::create($data);

        session()->flash('success', 'Sweepstake created successfully!');
        return redirect()->route('admin.sweepstakes.index');
    }


    public function edit($id)
    {
        $item = Sweepstakes::findOrFail($id);

        return view('sweepstakes::admin.edit', compact('item'));
    }


    public function update(Request $request, $id)
    {
        $item = Sweepstakes::findOrFail($id);

        $data = $request->validate([
            'title'        => 'required',
            'prize_title'  => 'required',
            'start_date'   => 'required',
            'end_date'     => 'required',
            'status'       => 'required',
            'image'        => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('sweepstakes', 'public');
        }

        $item->update($data);

        session()->flash('success', 'Sweepstake updated!');
        return redirect()->route('admin.sweepstakes.index');
    }


    public function delete($id)
    {
        Sweepstakes::destroy($id);

        session()->flash('success', 'Sweepstake deleted!');
        return redirect()->route('admin.sweepstakes.index');
    }
}

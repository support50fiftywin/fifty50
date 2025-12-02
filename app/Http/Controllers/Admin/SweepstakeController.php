<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sweepstake;
use Illuminate\Http\Request;

class SweepstakeController extends Controller
{
    public function index()
    {
        $sweepstakes = Sweepstake::latest()->get();
        return view('admin.sweepstakes.index', compact('sweepstakes'));
    }

    public function create()
    {
        return view('admin.sweepstakes.create');
    }

    public function store(Request $request)
	{
		$request->validate([
			'title' => 'required',
			'prize_title' => 'required',
			'start_date' => 'required',
			'end_date' => 'required',
			'prize_image' => 'nullable|image'
		]);

		$data = $request->only([
			'title',
			'prize_title',
			'start_date',
			'end_date'
		]);

		if ($request->hasFile('prize_image')) {
			$file = $request->file('prize_image')->store('sweepstakes', 'public');
			$data['prize_image'] = $file;
		}

		$data['status'] = 'scheduled';

		Sweepstake::create($data);

		return redirect()->route('admin.sweepstakes.index')
			->with('success', 'Sweepstake created successfully!');
	}
	public function edit($id)
    {
        $item = Sweepstake::findOrFail($id);
        return view('admin.sweepstakes.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Sweepstake::findOrFail($id);

        $item->update($request->only('title', 'prize_title', 'start_date', 'end_date', 'status'));

        if ($request->hasFile('prize_image')) {
            $image = $request->file('prize_image')->store('prizes', 'public');
            $item->update(['prize_image' => $image]);
        }

        return redirect()->route('admin.sweepstakes.index')->with('success', 'Sweepstake Updated');
    }

    public function close($id)
    {
        $item = Sweepstake::findOrFail($id);
        $item->update(['status' => 'closed']);
        return back()->with('success', 'Sweepstake Closed');
    }
}

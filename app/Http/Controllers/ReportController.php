<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reports = auth()->user()->reports()->latest()->get();
        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
        ]);

        auth()->user()->reports()->create($validated);

        return redirect()->route('reports.index')->with('success', 'Your report has been submitted.');
    }

    public function show(Report $report)
    {
        abort_if($report->user_id !== auth()->id(), 403);

        return view('reports.show', compact('report'));
    }
}

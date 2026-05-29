<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CustomPageStatus;
use App\Models\CustomPage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomPageController extends Controller
{
      public function __construct()
    {
        $this->middleware('permission:custompage.view')->only('index');
        $this->middleware('permission:custompage.create')->only('create', 'store');
        $this->middleware('permission:custompage.update')->only('edit', 'update');
        $this->middleware('permission:custompage.delete')->only('destroy');
    }
    public function index()
    {
        $pages = CustomPage::orderBy('order')->paginate(10);
        return view('admin.custompages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.custompages.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_active' => $request->boolean('is_active'),
            'show_in_footer' => $request->boolean('show_in_footer'),
        ]);

        $data = $request->validate([
            'title' => 'required|string|max:30',
            'content' => 'required|string',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
            'show_in_footer' => 'boolean',
        ]);
        CustomPage::create($data);
        return redirect()->route('admin.custom-pages.index')->with('success', 'Custom page created successfully.');
    }

    public function edit(CustomPage $page)
    {
        return view('admin.custompages.update', compact('page'));
    }

    public function update(Request $request, CustomPage $page)
    {
        $request->merge([
            'is_active' => $request->boolean('is_active'),
            'show_in_footer' => $request->boolean('show_in_footer'),
        ]);

        $data = $request->validate([
            'title' => 'required|string|max:30',
            'content' => 'required|string',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
            'show_in_footer' => 'boolean',
        ]);
        $page->update($data);
        return redirect()->route('admin.custom-pages.index')->with('success', 'Custom page updated successfully.');
    }

    public function destroy(CustomPage $page)
    {
        $page->delete();
        return redirect()->route('admin.custom-pages.index')->with('success', 'Custom page deleted successfully.');
    }

    public function show(CustomPage $page)
    {
        abort_unless($page->is_active === CustomPageStatus::ACTIVE, 404);

        return view('custompage', compact('page'));
    }
}

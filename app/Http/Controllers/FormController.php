<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Http\Requests\FormRequest;

/**
 * Class FormController
 * @package App\Http\Controllers
 */
class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $forms = Form::paginate();

        return view('form.index', compact('forms'))
            ->with('i', (request()->input('page', 1) - 1) * $forms->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('form.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $form = Form::create([
            'name' => $request->input('name'),
            'structure' => json_encode($request->input('structure'))
        ]);

        return redirect()->route('forms.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $form = Form::findOrFail($id);

        return view('form.show', compact('form'));
    }

    public function assignForm($id)
    {
        $form = Form::findOrFail($id);
        return view('form.assign', compact('form'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $form = Form::find($id);

        return view('form.edit', compact('form'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(FormRequest $request, Form $form)
    {
        $form->update($request->validated());

        return redirect()->route('forms.index')
            ->with('success', 'Form updated successfully');
    }

    public function destroy($id)
    {
        Form::find($id)->delete();

        return redirect()->route('forms.index')
            ->with('success', 'Form deleted successfully');
    }
}

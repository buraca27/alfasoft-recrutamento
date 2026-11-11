<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth') ->except(['index', 'show']);
    }

    public function index()
    {
        $contacts = Contact::all();
        return view('contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:6',
            'contact' => 'required|digits:9|unique:contacts,contact',
            'email' => 'required|email|unique:contacts,email',
        ]);

        Contact::create($validated);

        return redirect()->route('contacts.index')->with('success', 'Contacto criado com sucesso!');
    }

    public function show(Contact $contact)
    {
        return view('contacts.show', compact('contact'));
    }
    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:6',
            'contact' => ['required', 'digits:9', Rule::unique('contacts')->ignore($contact->id)],
            'email' => ['required', 'email', Rule::unique('contacts')->ignore($contact->id)],
        ]);

        $contact->update($validated);

        return redirect()->route('contacts.index')->with('success', 'Contacto atualizado com sucesso!');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('contacts.index')->with('success', 'Contacto eliminado com sucesso!');
    }
}

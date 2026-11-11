<h1>Detalhes</h1>
<p>Nome: {{ $contact->name }}</p>
<p>Contacto: {{ $contact->contact }}</p>
<p>Email: {{ $contact->email }}</p>
<a href="{{ route('contacts.index') }}">Voltar</a>
@auth
    <a href="{{ route('contacts.edit', $contact->id) }}">Editar</a>
    <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" style="display:inline;">
        @csrf @method('DELETE') <button onclick="return confirm('Apagar?')">Eliminar</button>
    </form>
@endauth
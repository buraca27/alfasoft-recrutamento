<h1>Contactos</h1>
@auth
    <a href="{{ route('contacts.create') }}">Novo Contacto</a>
    <form action="{{ route('logout') }}" method="POST" style="display:inline;">@csrf <button>Logout</button></form>
@else
    <a href="{{ route('login') }}">Login Admin</a>
@endauth

@if(session('success')) <p style="color:green;">{{ session('success') }}</p> @endif

<table border="1">
    <tr><th>Nome</th><th>Contacto</th><th>Email</th><th>Ações</th></tr>
    @foreach($contacts as $c)
    <tr>
        <td><a href="{{ route('contacts.show', $c->id) }}">{{ $c->name }}</a></td>
        <td>{{ $c->contact }}</td>
        <td>{{ $c->email }}</td>
        <td>
            @auth
                <a href="{{ route('contacts.edit', $c->id) }}">Editar</a>
                <form action="{{ route('contacts.destroy', $c->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE') <button onclick="return confirm('Apagar?')">Eliminar</button>
                </form>
            @endauth
        </td>
    </tr>
    @endforeach
</table>
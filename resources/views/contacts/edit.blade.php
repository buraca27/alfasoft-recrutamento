<h1>Editar Contacto</h1>
@if($errors->any()) <ul style="color:red;">@foreach($errors->all() as $e)<li>{{$e}}</li>@endforeach</ul> @endif

<form action="{{ route('contacts.update', $contact->id) }}" method="POST">
    @csrf @method('PUT')
    Nome: <input type="text" name="name" value="{{ old('name', $contact->name) }}"><br>
    Contacto: <input type="text" name="contact" value="{{ old('contact', $contact->contact) }}"><br>
    Email: <input type="email" name="email" value="{{ old('email', $contact->email) }}"><br>
    <button>Atualizar</button>
</form>
<a href="{{ route('contacts.index') }}">Voltar</a>
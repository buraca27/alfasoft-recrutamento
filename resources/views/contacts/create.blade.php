<h1>Criar Contacto</h1>
@if($errors->any()) <ul style="color:red;">@foreach($errors->all() as $e)<li>{{$e}}</li>@endforeach</ul> @endif

<form action="{{ route('contacts.store') }}" method="POST">
    @csrf
    Nome: <input type="text" name="name" value="{{ old('name') }}"><br>
    Contacto: <input type="text" name="contact" value="{{ old('contact') }}"><br>
    Email: <input type="email" name="email" value="{{ old('email') }}"><br>
    <button>Guardar</button>
</form>
<a href="{{ route('contacts.index') }}">Voltar</a>
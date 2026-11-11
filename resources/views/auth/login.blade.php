<h2>Login Administrador</h2>

@if($errors->any())
    <ul style="color:red;">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
@endif

<form method="POST" action="{{ route('login.attempt') }}">
    @csrf
    Email: <input type="email" name="email" value="{{ old('email', 'admin@admin.com') }}" required><br><br>
    Password: <input type="password" name="password" value="123456" required><br><br>
    <button type="submit">Entrar</button>
</form>
<a href="{{ route('contacts.index') }}">Voltar</a>

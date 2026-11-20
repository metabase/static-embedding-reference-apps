@extends('layouts.app')

@section('content')
    <div class="auth">
        <h2>Sign in to view your analytics</h2>
        @error('email')
            <p class="error">{{ $message }}</p>
        @enderror
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input name="email" type="email" placeholder="Email" value="{{ old('email', 'alice@example.com') }}" autocomplete="username" />
            <input name="password" type="password" placeholder="Password" value="password" autocomplete="current-password" />
            <button class="primary" type="submit">Log in</button>
        </form>
        <p>
            Demo users: <code>alice@example.com</code> and <code>bob@example.com</code> (Acme Corp), and
            <code>carol@example.com</code> (Bluebird Labs). The password for each is <code>password</code>.
        </p>
    </div>
@endsection

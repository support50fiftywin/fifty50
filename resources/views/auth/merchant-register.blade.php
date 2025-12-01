@extends('adminlte::auth.auth-page', ['auth_type' => 'register'])

@section('auth_body')
<form action="{{ route('merchant.register.submit') }}" method="POST">
    @csrf

    <x-adminlte-input name="name" label="Full Name" required />
    <x-adminlte-input name="business_name" label="Business Name" required />
    <x-adminlte-input name="email" label="Email" type="email" required />
    <x-adminlte-input name="phone" label="Phone" required />
    <x-adminlte-input name="website" label="Website (optional)" />
    <x-adminlte-input name="password" label="Password" type="password" required />
    <x-adminlte-button type="submit" class="btn-block" theme="dark" label="Register as Merchant" />
</form>
@endsection

@section('auth_footer')
<a href="{{ route('login') }}">Already registered? Login</a>
@endsection

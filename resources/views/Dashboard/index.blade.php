

@extends('dashboard-layouts.layout')

@section('title', 'dashboard index')


@section('content')
        <h2>Main Content</h2>
        <p>This is the main content area. You can put your page content here.</p>

        <p>Login User Name: {{ $user->name }}</p>
        <p>Login User Email: {{ $user->email }}</p>

@endsection

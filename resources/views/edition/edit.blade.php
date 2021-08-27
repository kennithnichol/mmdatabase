@extends('layouts.app')

@section('title', 'Edit Edition')

@section('content')
    <h1>Edit Edition</h1>
    @livewire('edition-form', ['edition' => $edition])
@endsection

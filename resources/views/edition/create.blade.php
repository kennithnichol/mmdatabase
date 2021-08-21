@extends('layouts.app')

@section('title', 'Create a new Edition')

@section('content')
    <h1>Create a new Edition</h1>
    <form method='post' action='{{ route('edition.store') }}'>
        @csrf
        <div class='field-group'>
            <label for='piece'>Piece/Work</label>
            <a href="{{-- route('piece.create') --}}">Create a new Piece</a>
            <select id='piece' name='piece' class='form-control'>
                <option value=''>Piece 1</option>
                <option value=''>Piece 2</option>
                <option value=''>Piece 3</option>
                <option value=''>Piece 4</option>
            </select>
        </div>
        <div class='field-group'>
            <label for='editor'>Editor</label>
            <a href="{{-- route('editor.create') --}}">Create a new Editor</a>
            <select id='editor' name='editor' class='form-control inline-block'>
                <option value=''>Editor 1</option>
                <option value=''>Editor 2</option>
                <option value=''>Editor 3</option>
                <option value=''>Editor 4</option>
            </select>
            
        </div>
        <div class='field-group'>
            <label for='publisher'>Publisher</label>
            <a href="{{-- route('publisher.create') --}}">Create a new Publisher</a>
            <select id='publisher' name='publiser' class='form-control'>
                <option value=''>Publisher 1</option>
                <option value=''>Publisher 2</option>
                <option value=''>Publisher 3</option>
                <option value=''>Publisher 4</option>
            </select>
        </div>

        @livewire('section-create')
    </form>
@endsection
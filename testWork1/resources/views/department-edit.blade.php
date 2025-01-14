@extends('layout')

@section('content')
    <div id="app" class="font-sans antialiased">
        <department-edit :department="{{ $department }}"/>
    </div>
@endsection


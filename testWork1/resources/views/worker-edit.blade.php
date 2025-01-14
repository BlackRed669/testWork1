@extends('layout')

@section('content')
    <div id="app" class="font-sans antialiased">
        <worker-edit :worker="{{ $worker }}"/>
    </div>
@endsection


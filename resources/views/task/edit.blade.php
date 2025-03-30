@extends('layouts.app')

@section('content')
    @include('task.form', ['task' =>$task])
@endsection

@extends('layouts.app')

@section('content')

    <google-plus-posting-panels clientsjson="{{ json_encode($clients) }}"></google-plus-posting-panels>

@endsection
@extends('layouts.admin')

@section('title', 'Edit Role')

@section('content')
    @livewire('user.role-edit', ['roleId' => $roleId])
@endsection
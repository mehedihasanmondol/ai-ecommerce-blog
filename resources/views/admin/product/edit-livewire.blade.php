@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
    @livewire('admin.product.product-form', ['product' => $product])
@endsection

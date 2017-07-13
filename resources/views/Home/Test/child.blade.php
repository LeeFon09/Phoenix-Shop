@extends('layouts.home')

@section('title','商品列表')

@section('content')
	@parent
	子页面文字<br>
	{{$id}}
@stop
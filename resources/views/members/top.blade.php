@extends('layout.master')

@section('title')
トップページ | 社員管理システム
@endsection

@section('header.h1')
社員管理システム
@endsection

@section('content.h2')
トップページ
@endsection

@section('content')

@if (count($users))
	@include('members.common.member_paginate_top')
	@include('members.common.member_list')
	@include('members.common.member_paginate_bottom')
@endif

@endsection
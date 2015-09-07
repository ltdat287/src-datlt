@extends('layout.master')

@section('title')
エラー | 社員管理システム
@endsection

@section('header.h1')
社員管理システム
@endsection

@section('content.h2')
エラー
@endsection

@section('content')

<section class="error-box">
	<h3>System error</h3>
	<ul>
	@foreach ($errors as $error)
    	<?php 
        // Write error to log.
        Log::error($error);
        ?>
		<li>{{ trans('必須入力項目') }}{{ $error }}</li>
	@endforeach
	</ul>
</section>

@endsection

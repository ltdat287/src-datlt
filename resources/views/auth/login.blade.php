@extends('layout.master')

@section('title')
ログイン | 社員管理システム
@endsection

@section('header.h1')
社員管理システム
@endsection

@section('content.h2')
ログイン
@endsection

@section('content')
	<section>

	@include('members.common.member_error')
	
		<form class="pure-form" action="{{ url('/login') }}" method="POST">
			{!! csrf_field() !!}
			<fieldset class="pure-group">
				<input type="text" name="email" class="pure-input-1-4 required" placeholder="メールアドレス">
					@if ($errors->has('email'))
	        			@foreach ($errors->get('email') as $error ) 
	        			<section class="error-message">{{ $error }}</section>
	        			@endforeach
	        		@endif
				<input type="password" name="password" class="pure-input-1-4 required" placeholder="パスワード">
					@if ($errors->has('password'))
            			@foreach ($errors->get('password') as $error ) 
            			<section class="error-message">{{ $error }}</section>
            			@endforeach
            		@endif
			</fieldset>
			<button type="submit" class="pure-button pure-input-1-4 pure-button-primary">ログイン</button>
		</form>
	</section>
@endsection
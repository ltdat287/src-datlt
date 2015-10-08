@extends('layout.master')

@section('title')
{{ $label }} | 社員管理システム
@endsection

@section('header.h1')
社員管理システム
@endsection

@section('content.h2')
{{ $label }}
@endsection

@section('content')
	<section>
		@if (MemberHelper::getCurrentUserRole() == 'employee')
			<p>
				登録情報を更新しました。
			</p>
			<div>
				<a class="pure-button pure-button-primary" href="{{ url('/') }}">戻る</a>
			</div>
		@else
			<p>
				{!! $message !!}
			</p>
			<div>
				<a class="pure-button pure-button-primary" href="{{ url('/search') }}">検索画面へ</a>
			</div>
		@endif
	</section>
@endsection

@extends('layout.master')

@section('title')
検索 | 社員管理システム
@endsection

@section('header.h1')
社員管理システム
@endsection

@section('content.h2')
検索
@endsection

@section('content')
<section>
    @include('members.common.member_error', ['errors' => $errors])
    
	<form name="search" class="pure-form" method="get" action="{{ url('/search') }}">
	<table class="pure-table pure-table-bordered">
		<tbody>
			<tr>
				<td>名前</td>
				<td><input type="text" name="name" value="{{ Input::old('name') }}"></td>
				<td>メールアドレス</td>
				<td><input type="text" name="email" value="{{ Input::old('email') }}"></td>
			</tr>
			<tr>
				<td>名前（カナ）</td>
				<td><input type="text" name="kana" value="{{ Input::old('kana') }}"></td>
				<td>電話番号</td>
				<td><input type="text" name="telephone_no" value="{{ Input::old('telephone_no') }}"></td>
			</tr>
			<tr>
				<td>生年月日</td>
				<td colspan="3">
					<input type="text" name="start_date" value="{{ Input::old('start_date') }}" placeholder="開始日">
					&nbsp;～&nbsp;
					<input type="text" name="end_date" value="{{ Input::old('end_date') }}" placeholder="終了日">
				</td>
			</tr>
			@if (MemberHelper::getCurrentUserRole() == 'admin')
			<tr>
				<td>権限</td>
				<td colspan="3" align="center">
					<ul class="pure-menu-list pure-menu-horizontal">
                        @foreach ($roles as $key => $value)
                        <li class="pure-menu-item pure-u-1-6">
                            <label for="{{ $value }}"><input {{ (Input::has($key) && Input::has($key) == '1') ? 'checked=checked' : '' }} type="checkbox" id="{{ $key }}" name="{{ $key }}" value="1">{{ $value }}</label>
                        </li>
                        @endforeach
					</ul>
				</td>
			</tr>
			@endif
			
			<tr>
				<td colspan="4" align="right">
					@if (MemberHelper::getCurrentUserRole() == 'admin' OR MemberHelper::getCurrentUserRole() == 'boss')
					<button class="pure-button pure-button-primary" type="submit">検索</button>
					@endif
				</td>
			</tr>
		</tbody>
	</table>

		@if (count($users))
		
	    	@include('members.common.member_paginate_top', ['users' => $users])
	    	
	    	@include('members.common.member_list', ['users' => $users])
	    
	    	@include('members.common.member_paginate_bottom', ['users' => $users])
		
		@endif

	</form>
</section>

<section>
    @if (! count($users))
        <p class="error-box">{{ trans('検索結果にマッチしませんでした。') }}</p>
    @endif
</section>

@endsection

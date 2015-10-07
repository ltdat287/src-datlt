@extends('layout.master')

@section('title')
追加 | 社員管理システム
@endsection

@section('header.h1')
社員管理システム
@endsection

@section('content.h2')
追加
@endsection

@section('content')
	<section>
        @include('members.common.member_error', ['errors' => $errors])

		<form name="addMember" class="pure-form pure-u-3-4" method="post" action="{{ url('/add/conf') }}">
		{!! csrf_field() !!}
		<table class="pure-table pure-table-bordered" width="100%">
			<tbody>

                @include('members.common.member_form', ['errors' => $errors])

				<tr>
					<td colspan="2" align="right">
						<a class="pure-button pure-button-primary" href="{{ url('/search') }}">検索画面へ</a>
						<button class="pure-button button-error" name="submit" type="submit">確認</button>
					</td>
				</tr>
			</tbody>
		</table>
		</form>
	</section>
@endsection

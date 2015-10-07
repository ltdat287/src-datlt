@extends('layout.master')

@section('title')
編集 | 社員管理システム
@endsection

@section('header.h1')
社員管理システム
@endsection

@section('content.h2')
編集
@endsection

@section('content')

	<section>
		<form name="editConfirmMemeber" method="post" action="{{ url('member/' . $id . '/edit/comp') }}">
		{!! csrf_field() !!}
		<table class="pure-table pure-table-bordered" width="100%">
			<tbody>
			    <tr>
					<th>{{ trans('ID') }}</th>
					<td>{{{ $id }}}</td>
				</tr>

				@include('members.common.member_infor', ['user' => $user])

				<tr>
					<td colspan="2" align="right">
						<button class="pure-button pure-button-primary" name="back" type="submit">戻る</button>
						<button class="pure-button button-error" name="submit" type="submit">{{ trans('確認') }}</button>
					</td>
				</tr>
			</tbody>
		</table>
		</form>
	</section>

@endsection

@extends('layout.master')

@section('title')
追加（確認） | 社員管理システム
@endsection

@section('header.h1')
社員管理システム
@endsection

@section('content.h2')
追加（確認）
@endsection

@section('content')
	<section>
		<form name="addMemberConfirm" method="post" action="{{ url('add/comp') }}">
		{!! csrf_field() !!}
		<table class="pure-table pure-table-bordered" width="100%">
			<tbody>
			
				@include('members.common.member_infor', ['user' => $user, 'role' => $role, 'boss' => $boss])
				
				<tr>
					<td colspan="2" align="right">
						<button class="pure-button pure-button-primary" name="back" type="submit">戻る</button>
						<button class="pure-button button-error" name="submit" type="submit">登録</button>
					</td>
				</tr>
			</tbody>
		</table>
		</form>
	</section>
@endsection
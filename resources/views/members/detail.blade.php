@extends('layout.master')

@section('title')
{{ $user->name }}({{ $user->kana }})の詳細 | 社員管理システム
@endsection

@section('header.h1')
社員管理システム
@endsection

@section('content.h2')
編集
@endsection

@section('content')

	<section>
		<table name="memberDetail" class="pure-table pure-table-bordered" width="100%">
			<tbody>
				<tr>
					<th>{{ trans('ID') }}</th>
					<td>{{ $id }}</td>
				</tr>

				@include('members.common.member_infor', ['user' => $user, 'role' => $role, 'boss' => $boss])

				<tr>
					<td colspan="2" align="right">
					    @if (MemberHelper::getCurrentUserRole() != 'employee')
						<a class="pure-button pure-button-primary" href="{{ url('/search') }}">{{ trans('検索画面へ') }}</a>
						@endif
						@if (MemberHelper::showEditButton($id))
						<a class="pure-button button-secondary" href="{{ url('/member/' . $user->id . '/edit') }}">{{ trans('編集') }}</a>
						@endif
						@if (MemberHelper::getCurrentUserRole() != 'employee')
						<a class="pure-button button-error" href="{{ url('/member/' . $user->id . '/delete/conf') }}">{{ trans('削除') }}</a>
						@endif
					</td>
				</tr>
			</tbody>
		</table>
	</section>

@endsection

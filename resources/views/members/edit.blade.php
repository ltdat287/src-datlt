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
        @include('members.common.member_error', ['errors' => $errors])

        <form name="editMember" class="pure-form pure-u-3-4" method="post" action="{{ url('/member/' . $id . '/edit/conf') }}">
        {!! csrf_field() !!}
		<table class="pure-table pure-table-bordered" width="100%">
			<tbody>
				<tr>
					<th>{{ trans('ID') }}</th>
					<td>{{ $id }}</td>
				</tr>

				@include('members.common.member_form', ['errors' => $errors])

				<tr>
					<td colspan="2" align="right">
						<a class="pure-button pure-button-primary" href="{{ url('member/' . $id . '/detail') }}">{{ trans('戻る') }}</a>
						<button class="pure-button button-error" name="submit" type="submit">{{ trans('確認') }}</button>
					</td>
				</tr>
			</tbody>
		</table>
		</form>
    </section>

@endsection

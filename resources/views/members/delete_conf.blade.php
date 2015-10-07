@extends('layout.master')

@section('title')
削除（確認） | 社員管理システム
@endsection

@section('header.h1')
社員管理システム
@endsection

@section('content.h2')
削除（確認）
@endsection

@section('content')
	<section>
	   @if (! $errors)
	       <p>{{ trans('次のデータを削除します。') }}</p>
	   @else
	       <section class="error-box">
				<h3>!!ERROR!!</h3>
				<ul>
					@foreach ($errors as $error)
						<li>{{{ $error }}}</li>
					@endforeach
				</ul>
			</section>
	   @endif

	   	<form method="post" action="{{ url('member/' . $id . '/delete/comp') }}" >
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
						<a class="pure-button pure-button-primary" name="back" type="button" href="{{ url('member/' . $id . '/detail') }}">{{ trans('戻る') }}</a>
						@if (! count($errors))
						<button class="pure-button button-error" name="submit" type="submit">{{ trans('実行') }}</button>
						@endif
					</td>
				</tr>
			</tbody>
		</table>
		</form>
	</section>

@endsection

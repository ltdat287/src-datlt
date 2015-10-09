<section>
	<table class="pure-table pure-table-bordered">
		<thead>
			<tr>
				<th>ID</th>
				<th>名前</th>
				<th>メールアドレス</th>
				<th>電話番号</th>
				<th>生年月日</th>
				<th>更新日時</th>
				<th>権限</th>
			</tr>
		</thead>
		<tbody>
			@if ($users)
				@foreach ($users as $i => $user)
	            <tr {{ (($i % 2) == 0) ? '' : 'class=pure-table-odd' }}>
					<td>{{ $user->id }}</td>
					<td><a href="{{ url('/member/' . $user->id . '/detail') }}">{{ $user->name }}({{ $user->kana }})</a></td>
					<td>{{ $user->email }}</td>
					<td>{{ $user->telephone_no }}</td>
					<td>{{ $user->birthday }}</td>
					<td>{{ $user->updated_at->format('Y/m/d H:i:s') }}</td>
					<td>{{ MemberHelper::getNameRole($user->role) }}</td>
				</tr>
	            @endforeach
			@endif
		</tbody>
	</table>
</section>
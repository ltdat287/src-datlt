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
			<tr class="pure-table-odd">
				@if ($users)
					@foreach ($users as $i => $user)
		            <tr {{ (($i % 2) == 0) ? '' : 'class=pure-table-odd' }}>
						<td>{{{ $user->id }}}</td>
						<td><a href="{{ url('/member/' . $user->id . '/detail') }}">{{{ $user->name }}}({{{ $user->kana }}})</a></td>
						<td>{{{ $user->email }}}</td>
						<td>{{{ $user->telephone_no }}}</td>
						<?php 
						   $birthday = new \Carbon($user->birthday);
						?>
						<td>{{ $birthday->format('Y/m/d') }}</td>
						<td>{{{ with($user->updated_at)->format('Y/m/d H:i:s') }}}</td> 
						
						@if ($user->role === 'admin')
							<td>{{{ ADMIN }}}</td>
						@elseif ($user->role === 'employee')
							<td>{{{ EMPLOYEE }}}</td>
						@elseif ($user->role === 'boss')
							<td>{{{ BOSS }}}</td>
						@else
							<td>{{{ $user->role }}}</td>
						@endif
					</tr>
		            @endforeach
				@endif
			</tr>
		</tbody>
	</table>
</section>
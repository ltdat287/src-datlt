<tr>
	<th>名前</th>
	<td>{{ $user->name }}</td>
</tr>
<tr>
	<th>名前（カナ）</th>
	<td>{{ $user->kana }}</td>
</tr>
@if (MemberHelper::getCurrentUserRole() != 'employee')
<tr>
	<th>メールアドレス</th>
	<td>{{ $user->email }}</td>
</tr>
@endif
<tr>
	<th>電話番号</th>
	<td>{{ $user->telephone_no }}</td>
</tr>
<tr>
	<th>生年月日</th>
	<?php
	   $birthday = new \Carbon($user->birthday);
	?>
	<td>{{ $birthday->format('Y/m/d') }}</td>
</tr>
@if (MemberHelper::getCurrentUserRole() != 'employee')
    <tr>
    	<th>ノート</th>
    	<td><?php echo nl2br($user->note)?></td>
    </tr>
    @if (MemberHelper::getCurrentUserRole() == 'admin' )
        <tr>
            <th>権限</th>
            @if ($user->role === 'admin')
                <td>{{ ADMIN }}</td>
            @elseif ($user->role === 'employee')
                <td>{{ EMPLOYEE }}</td>
            @elseif ($user->role === 'boss')
                <td>{{ BOSS }}</td>
            @else
                <td>{{ $user->role }}</td>
            @endif
        </tr>
        <tr>
        	<th>BOSS</th>
        	@if ($boss)
        	<td>{{ $boss->name }}</td>
        	@else
        	<td>--</td>
            @endif
        </tr>
    @endif

    @if (MemberHelper::getCurrentUserRole() != 'employee' && isset($user->updated_at))
        <tr>
            <th>{{ trans('更新日時') }}</th>
            <td>{{ $user->updated_at->format('Y/m/d H:i:s') }}</td>
        </tr>
    @endif
@endif
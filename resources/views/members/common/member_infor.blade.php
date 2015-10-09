<tr>
	<th>名前</th>
	<td>{{ $user->name }}</td>
</tr>
<tr>
	<th>名前（カナ）</th>
	<td>{{ $user->kana }}</td>
</tr>
@if (MemberHelper::getCurrentUserRole() != EMPLOYEE)
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
	<td>{{ $user->birthday }}</td>
</tr>
@if (MemberHelper::getCurrentUserRole() != EMPLOYEE)
    <tr>
    	<th>ノート</th>
    	<td><?php echo nl2br($user->note)?></td>
    </tr>
    @if (MemberHelper::getCurrentUserRole() == ADMIN )
        <tr>
            <th>権限</th>
            <td>{{ MemberHelper::getNameRole($user->role) }}</td>
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

    @if (MemberHelper::getCurrentUserRole() != EMPLOYEE && isset($user->updated_at))
        <tr>
            <th>{{ trans('更新日時') }}</th>
            <td>{{ $user->updated_at->format('Y/m/d H:i:s') }}</td>
        </tr>
    @endif
@endif
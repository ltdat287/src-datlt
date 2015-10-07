<tr>
	<th {{ $errors->has('name') ? "class=error-cell" : '' }} >名前</th>
	<td>
		<input type="text" name="name" value="{{ MemberHelper::getOld('name') }}" class="pure-input-1">
		@if ($errors->has('name'))
			@foreach ($errors->get('name') as $error )
			<section class="error-message">{{ $error }}</section>
			@endforeach
		@endif
	</td>
</tr>
<tr>
	<th {{ $errors->has('kana') ? "class=error-cell" : '' }} >名前（カナ）</th>
	<td>
		<input type="text" name="kana" value="{{ MemberHelper::getOld('kana') }}" class="pure-input-1">
		@if ($errors->has('kana'))
			@foreach ($errors->get('kana') as $error )
			<section class="error-message">{{ $error }}</section>
			@endforeach
		@endif
	</td>
</tr>
@if (MemberHelper::getCurrentUserRole() != 'employee')
<tr>
	<th {{ $errors->has('email') ? "class=error-cell" : '' }} >メールアドレス</th>
	<td>
	   <input type="text" name="email" value="{{ MemberHelper::getOld('email') }}" class="pure-input-1">
		@if ($errors->has('email'))
			@foreach ($errors->get('email') as $error )
			<section class="error-message">{{ $error }}</section>
			@endforeach
		@endif
	</td>
</tr>
<tr>
	<th {{ $errors->has('email_confirmation') ? "class=error-cell" : '' }} >メールアドレス（確認）</th>
	<td>
	   <input type="text" name="email_confirmation" value="{{ MemberHelper::getOld('email_confirmation') }}" class="pure-input-1">
		@if ($errors->has('email_confirmation'))
			@foreach ($errors->get('email_confirmation') as $error )
			<section class="error-message">{{ $error }}</section>
			@endforeach
		@endif
	</td>
</tr>
@endif
<tr>
	<th {{ $errors->has('telephone_no') ? "class=error-cell" : '' }} >電話番号</th>
	<td>
		<input type="text" name="telephone_no" value="{{ MemberHelper::getOld('telephone_no') }}" class="pure-input-1">
		@if ($errors->has('telephone_no'))
			@foreach ($errors->get('telephone_no') as $error )
			<section class="error-message">{{ $error }}</section>
			@endforeach
		@endif
	</td>
</tr>
<tr>
	<th {{ $errors->has('birthday') ? "class=error-cell" : '' }} >生年月日</th>
	<td>
		<input type="text" name="birthday" value="{{ MemberHelper::getOld('birthday') }}" class="pure-input-1">
		@if ($errors->has('birthday'))
			@foreach ($errors->get('birthday') as $error )
			<section class="error-message">{{ $error }}</section>
			@endforeach
		@endif
	</td>
</tr>
@if (MemberHelper::getCurrentUserRole() != 'employee')
<tr>
	<th {{ $errors->has('note') ? "class=error-cell" : '' }} >ノート</th>
	<td>
		<textarea name="note" class="pure-input-1">{{ MemberHelper::getOld('note') }}</textarea>
		@if ($errors->has('note'))
			@foreach ($errors->get('note') as $error )
			<section class="error-message">{{ $error }}</section>
			@endforeach
		@endif
	</td>
</tr>
@endif
<tr>
	<th {{ $errors->has('password') ? "class=error-cell" : '' }} >パスワード</th>
	<td>
		<input type="password" name="password" class="pure-input-1">
		@if ($errors->has('password'))
			@foreach ($errors->get('password') as $error )
			<section class="error-message">{{ $error }}</section>
			@endforeach
		@endif
	</td>
</tr>
@if (MemberHelper::getCurrentUserRole() == 'admin')
<tr>
	<th {{ $errors->has('use_role') ? "class=error-cell" : '' }}>権限</th>
	<td>
		<select autocomplete="off" name="use_role" class="pure-input-1">
            @foreach ($roles as $key => $value)
                @if ($user != null)
                <option value="{{ $key }}" {{ ($user->role && $user->role == $key) ? "selected=selected" : '' }} >{{{ $value }}}</option>
                @else
                <option value="{{ $key }}" {{ (MemberHelper::getOld('use_role') == $key) ? "selected=selected" : '' }}>{{{ $value }}}</option>
                @endif
            @endforeach
		</select>
		@if ($errors->has('use_role'))
			@foreach ($errors->get('use_role') as $error )
			<section class="error-message">{{ $error }}</section>
			@endforeach
		@endif
	</td>
</tr>
<tr>
	<th {{ $errors->has('boss_id') ? "class=error-cell" : '' }} >BOSS</th>
	<td>
		<select autocomplete="off" name="boss_id" class="pure-input-1">
			<option value="">--</option>
			@foreach($bosses as $boss)
			<option value="{{{ $boss->id }}}" {{ (MemberHelper::getOld('boss_id') == $boss->id) ? "selected" : '' }}>{{{ $boss->name }}}</option>
			@endforeach
		</select>
		@if ($errors->has('boss_id'))
			@foreach ($errors->get('boss_id') as $error )
			<section class="error-message">{{ $error }}</section>
			@endforeach
		@endif
	</td>
</tr>
@endif
<?php
$user      = MemberHelper::checkLogin();
$arrLinks  = array();
$name      = null;
$routeName = Route::currentRouteName();

if (! $user)
{
    $arrLinks = array(
        url('/login') => trans('ログイン')
    );
}
else
{
    $role = $user->role;
    $role = ($role) ? $role : '';
    switch ($role)
    {
        case 'admin':
            $name     = $user->name . '(' . trans('飯塚 浩二（管理者）') . ')';
            $arrLinks = array(
                url('/search') => trans('検索'),
                url('/add')    => trans('追加'),
                url('/logout') => trans('ログアウト')
            );
            break;
        case 'boss':
            $name     = $user->name;
            $arrLinks = array(
                url('/search') => trans('検索'),
                url('/add')    => trans('追加'),
                url('/logout') => trans('ログアウト')
            );
            break;
        case 'employee':
            $name     = $user->name;
            $arrLinks = array(
                url('/logout') => trans('ログアウト')
            );
            break;
    }
}
?>
@if (count($arrLinks) && $routeName != 'login')
<ul class="pure-menu-list force-right">
    @if ($name)
    <li class="pure-menu-item"><span class="pure-menu-link">{{ $name }}</span></li>
    @endif

    @foreach ($arrLinks as $link => $label)
	<li class="pure-menu-item"><a href="{{ $link }}" class="pure-menu-link">{{ $label }}</a></li>
	@endforeach
</ul>
@endif
<?php if ($app->environment('local')) {
	$css_pure_min = url('css/pure-min.css');
	$css_custom = url('css/custom.css');
} else {
	$css_pure_min = 'http://edu.localhost/css/pure-min.css';
	$css_custom = 'http://edu.localhost/css/custom.css';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta content="社員管理システム ログインページです。" name="description">
	<title>@yield('title')</title>
	<link href="/{{ (Route::getCurrentRoute()->getPath() == '/') ? '' : (Route::getCurrentRoute()->getPath()) }}" rel="canonical">
	<link rel="stylesheet" href="{{ $css_pure_min }}">
    <link rel="stylesheet" href="{{ $css_custom }}">
</head>
<body>

<header>
	<nav class="home-menu pure-menu pure-menu-horizontal relative">
		<h1 class="pure-menu-heading"><a href="{{ url('/') }}">@yield('header.h1')</a></h1>
		@include('layout.common.navigation')
	</nav>
</header>

<section class="contents">
	<h2>@yield('content.h2')</h2>

	@yield('content')

</section>

@include('layout.common.footer')

</body>
</html>
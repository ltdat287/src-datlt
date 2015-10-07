<!DOCTYPE html>
<html lang="ja">
<head>
	<meta content="社員管理システム ログインページです。" name="description">
	<title>@yield('title')</title>
	<link href="/" rel="canonical">
	<link rel="stylesheet" href="{{{ asset('/css/pure-min.css') }}}">
    <link rel="stylesheet" href="{{{ asset('/css/custom.css') }}}">
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
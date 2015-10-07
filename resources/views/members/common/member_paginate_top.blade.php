<?php
    $isFirst = (($users->currentPage() - 1) == 0) ? 'pure-button-disabled' : '';
    $isLast = ($users->currentPage() == $users->lastPage()) ? 'pure-button-disabled' : '';
?>
<nav class="pure-menu pure-menu-horizontal">
	<ul class="pure-menu-list">
		<li class="pure-menu-item"><a href="{{ $users->url(1) }}" class="pure-menu-link pure-button {{ $isFirst }}">{{ trans('first') }}</a></li>
		<li class="pure-menu-item"><a href="{{ $users->url($users->currentPage() - 1) }}" class="pure-menu-link pure-button {{ $isFirst }}">{{ trans('back') }}</a></li>
		@if (($users->currentPage() - 1) > 0)
		<li class="pure-menu-item">...</li>
		<li class="pure-menu-item"><a href="{{ $users->url($users->currentPage() - 1) }}" class="pure-menu-link pure-button">{{ $users->currentPage() - 1 }}</a></li>
		@endif
		<li class="pure-menu-item"><button class="pure-button pure-button-disabled">{{ $users->currentPage() }}</button></li>
		@if ($users->currentPage() < $users->lastPage())
		<li class="pure-menu-item"><a href="{{ $users->nextPageUrl() }}" class="pure-menu-link pure-button">{{ $users->currentPage() + 1 }}</a></li>
		<li class="pure-menu-item">...</li>
		@endif
		<li class="pure-menu-item"><a href="{{ $users->nextPageUrl() }}" class="pure-menu-link pure-button {{ $isLast }}">{{ trans('next') }}</a></li>
		<li class="pure-menu-item"><a href="{{ $users->url($users->lastPage()) }}" class="pure-menu-link pure-button {{ $isLast }}">{{ trans('last') }}</a></li>
	</ul>
</nav>
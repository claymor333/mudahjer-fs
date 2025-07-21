<!-- Navbar -->
<div class="navbar bg-[var(--bg-card)]/80 backdrop-blur-lg sticky top-0 z-50 border-[var(--border-color)]">
	<div class="navbar-start">
		<div class="dropdown">
			<div tabindex="0" role="button" class="btn btn-ghost lg:hidden hover:bg-[#afd9e0]/20">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
				</svg>
			</div>
			<ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow-lg bg-[var(--bg-card)] rounded-box w-52 backdrop-blur-xl border border-[var(--border-color)]">
				<li><a class="hover:text-[var(--accent)]">Learn</a></li>
				<li><a class="hover:text-[var(--accent)]">Practice</a></li>
				<li><a class="hover:text-[var(--accent)]">Dictionary</a></li>
				<li><a class="hover:text-[var(--accent)]">Community</a></li>
			</ul>
		</div>
		<a href="{{ url('/') }}" class="btn btn-ghost text-xl hover:bg-[#afd9e0]/20 font-bold flex items-center gap-2">
			<img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8">
			<span class="gradient-text">MudahJer</span>
		</a>
	</div>
	<div class="navbar-center hidden lg:flex">
		<ul class="menu menu-horizontal px-1 gap-2">
			<li><a class="hover:text-[var(--accent)] transition-colors">Learn</a></li>
			<li><a class="hover:text-[var(--accent)] transition-colors">Practice</a></li>
			<li><a class="hover:text-[var(--accent)] transition-colors">Dictionary</a></li>
			<li><a class="hover:text-[var(--accent)] transition-colors">Community</a></li>
		</ul>
	</div>
	<div class="navbar-end gap-2">
		<label class="swap swap-rotate">
            <input type="checkbox" class="theme-controller hidden" />
            <svg class="swap-on h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z"/></svg>
            <svg class="swap-off h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"/></svg>
        </label>
		@if (Route::has('login'))
			@auth
				<div class="flex items-center">
					<form method="POST" action="{{ route('logout') }}" class="m-0">
						@csrf
						<button type="submit" class="btn btn-ghost hover:bg-red-500/10 hover:text-red-500">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
							</svg>
						</button>
					</form>
					<a href="{{ url('/dashboard') }}" class="btn bg-[#23577a] text-[#fffcf3] hover:bg-[#63c196] border-none">Dashboard</a>
				</div>
			@else
				<a href="{{ route('login') }}" class="btn btn-ghost hover:bg-[#afd9e0]/20">Log in</a>
				@if (Route::has('register'))
					<a href="{{ route('register') }}" class="btn bg-[#23577a] text-[#fffcf3] hover:bg-[#63c196] border-none">Start Learning</a>
				@endif
			@endauth
		@endif
	</div>
</div>

@push('scripts')
	<script>
		
	</script>
@endpush
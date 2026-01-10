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
				{{-- <li><a class="hover:text-[var(--accent)]">Practice</a></li> --}}
				<li><a class="hover:text-[var(--accent)]">Dictionary</a></li>
				{{-- <li><a class="hover:text-[var(--accent)]">Community</a></li> --}}
			</ul>
		</div>
		<a href="{{ url('/') }}" class="btn btn-ghost text-xl hover:bg-[#afd9e0]/20 font-bold flex items-center gap-2">
			<img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8">
			<span class="gradient-text">MudahJer</span>
		</a>
	</div>
	<div class="navbar-center hidden lg:flex">
		<ul class="menu menu-horizontal px-1 gap-2">
			@hasrole('admin')
				<li><a href="{{ route('admin.dashboard') }}" class="hover:text-[var(--accent)] transition-colors">Admin</a></li>
			@endhasrole
			<li><a href="{{ route('player.quizzes.index') }}" class="hover:text-[var(--accent)] transition-colors">Learn</a></li>
			{{-- <li><a class="hover:text-[var(--accent)] transition-colors">Practice</a></li> --}}
			<li><a href="{{ route('player.notes.index') }}" class="hover:text-[var(--accent)] transition-colors">Dictionary</a></li>
			{{-- <li><a class="hover:text-[var(--accent)] transition-colors">Community</a></li> --}}
		</ul>
	</div>
	<div class="navbar-end gap-2">
		<button class="btn btn-ghost">

			<label class="swap swap-rotate">
				<input type="checkbox" class="theme-controller hidden" />
				<svg class="swap-on h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z"/></svg>
				<svg class="swap-off h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"/></svg>
			</label>
		</button>
			@if (Route::has('login'))
			@auth
				<!-- Dropdown -->
				<details class="dropdown dropdown-end">
					<summary class="btn btn-soft btn-accent m-0 gap-3">
						<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-6 h-6 stroke-8" viewBox="0 0 16 16">
							<path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
						</svg>
						<p>
							{{ Auth::user()->name }}
						</p>
					</summary>
					<ul class="menu dropdown-content bg-base-100 dark:bg-base-200 border border-base-300 rounded-box z-1 w-52 p-2 shadow-sm">
						<li>
							<a href="{{ route('profile.edit') }}" class="btn btn-ghost hover:bg-[#afd9e0]/20 justify-start gap-8">
								<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-6 h-6 stroke-8" viewBox="0 0 16 16">
									<path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
									<path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
								</svg>
								<p>
									Edit Profile
								</p>
							</a>
						</li>
						<li>
							<button type="button" onclick="document.getElementById('logout-form').submit();" class="btn btn-ghost hover:bg-red-500/10 hover:text-red-500 justify-start gap-8">
								<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-6 h-6 stroke-8" viewBox="0 0 16 16">
									<path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
									<path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
								</svg>
								<p>
									Log Out
								</p>
							</button>
						</li>
					</ul>
				</details>

				<!-- External Logout Form -->
				<form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
					@csrf
				</form>
				<a href="{{ url('/dashboard') }}" class="btn bg-[#23577a] text-[#fffcf3] hover:bg-[#63c196] border-none">Dashboard</a>
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
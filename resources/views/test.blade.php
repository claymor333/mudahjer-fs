<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body> <!-- Use DaisyUI's theme color -->
	<button id="testButton" class="btn">
		Click me
	</button>

	<div class="card w-96 bg-base-100 shadow-xl">
		<div class="card-body">
			<h2 class="card-title">Hello, DaisyUI!</h2>
			<p>This is a card component from DaisyUI.</p>
			<div class="card-actions justify-end">
				<button class="btn btn-primary rounded-full px-16">
					<a href="/welcome">
						Get Started
					</a>
				</button>
			</div>
		</div>
	</div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
	$(document).ready(function() {
		$('#testButton').click(function() {
			alert('jQuery is working!');
		});
	});
</script>

</html>
<?php require_once 'ui/data.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>ESC-Compiler UI</title>
		<link rel="stylesheet" href="vendor/bootstrap/dist/css/bootstrap.min.css">
		<script type="text/javascript" src="vendor/jquery/dist/jquery.min.js"></script>
		<script type="text/javascript" src="vendor/bootstrap/dist/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="ui/main.js"></script>
	</head>
	
	<body>
		<div class="container">
			<h1>ESC Compiler</h1>
			<hr>
			<div class="col-md-4">
				<ul class="nav nav-pills nav-stacked">
				<?php
					$classes = getErrorClasses();
					foreach ($classes as $class) {
					?>
					<li><a href class="category" data-hash="<?php echo $class['hash']; ?>"><?php echo $class['error']; ?></a></li>
					<?php
					} 
				?>
				</ul>
			</div>
			<div class="col-md-8">
				<table class="table table-default" id="instance-list"></table>
			</div>
		</div>
	</body>
</html>
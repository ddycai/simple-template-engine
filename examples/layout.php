<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $this['title']?></title>
		<link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
		<style>
			#content {
				margin: auto;
				width: 400px;
				font-family: "Lato";
			}
		</style>
	</head>
	<body>
		<div id="content"><?php echo $this['content']; ?></div>
	</body>
</html>

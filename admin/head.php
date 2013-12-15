<!DOCTYPE html>
<html lang="es">
<head>
	<title><?= $Titulo; ?> Hack PaginasAmarillas</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Envio de emailing">
	<meta name="author" content="Fco.Javier Martinez">
	<!-- Iconos para diferentes dispositivos -->
	<link rel="shortcut icon" href="../img/favicon.png">
	<link rel="apple-touch-icon" href="../apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="57x57" href="../img/icon_iphone_57.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="../img/icon_72.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="../img/icon_ipad_114.png" />
	<link rel="apple-touch-icon" sizes="144x144" href="../img/icon_144.png" />
	<meta name="msapplication-TileImage" content="../img/icon_144.png" />
	<meta name="msapplication-TileColor" content="#FFFFFF" />
	<link rel="fluid-icon" href="../img/icon_500.png" title="Hack PaginasAmarillas" />
	
	<!-- Fin de iconos -->

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Bootstrap -->
	<link rel="stylesheet" href="../css/bootstrap.min.css" media="screen">
	<link rel="stylesheet" href="../lib/tree/bootstrap-combined.min.css">
	<link rel="stylesheet" href="../css/tabla2.css" />
	<link rel="stylesheet" href="../css/screen.css" />

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<script src="../js/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="../index.php">Hack PaginasAmarillas</a>
			</div>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li
<?php if ($Pagina=='index') echo 'class="active"';
echo '><a href="index.php">Lista descargados</a></li>';
?>
					<li
<?php if ($Pagina=='administrar') echo 'class="active"';
echo '><a href="administrar.php">Administrar</a></li>';
?>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</div>
	<div class="container">

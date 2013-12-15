Paginas Amarillas
===================

REQUESITOS
  Apache + PHP5+ MySQL + PHP-CLI

INSTALACION
* Crear una BBDD en MySQL ejemplo hack_paginasamarillas
* Montar en ella la BBDD que esta en la carpeta backupSQL/Original.sql
* La carpeta CRON puede estar instalada en cualquier lugar independiente o maquina independiente (Posiblemente en mas de una si se desea).
* Personalizar el fichero config.php y cron/config.php indicando donde se encuentra la BBDD de MySQL.

MARCAR LOS SECTORES URGENTES
* Acceder a la web
	Ir a Administrar -> Administrar
	Hay puede marcar las que desea descargar mas urgentes

EJECUTAR SCRIPT
* Desde la consola ir a la carpeta donde este el script "cron" y escribir
		php5 index_cron.php

RECOMENDACIONES
* Se recomienda en la carpeta admin crear un .htaccess para bloquear el acceso no autorizado
	AuthName "Acceso Restringido a la administración de Hack PaginasAmarillas"
	AuthType Basic
	AuthUserFile <Ruta fuera del acceso www>/hack_paginasamarillas.htp
	AuthGroupFile /dev/null
	require valid-user

* Creamos un fichero <Ruta fuera del acceso www>/hack_paginasamarillas.htp
Con la clave del tipo .htpasswd

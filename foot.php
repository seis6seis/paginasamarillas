	</div>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="lib/bootstrap.min.js"></script>
	<script src="lib/jquery.cookie.js" type="text/javascript"></script>
	<script src="lib/tree/bootstrap-tree.js"></script>

<?php
if($Pagina=="administrar"){
	echo '	<script type="text/javascript">'."\n";
/*	echo '		$( document ).ready(function() {'."\n";
	echo '			$("#tree").treeview({'."\n";
	echo '				collapsed: true,'."\n";
	echo '				animated: "medium",'."\n";
	echo '				control:"#sidetreecontrol",'."\n";
	echo '				persist: "location"'."\n";
	echo '			});'."\n";
	echo '		});'."\n";*/

	echo '		$(".urgente").click(function(){'."\n";
	echo '			if ($(this).attr("status")=="0")'."\n";
	echo '				$(this).attr("status","1");'."\n";
	echo '			else'."\n";
	echo '				$(this).attr("status","0");'."\n";

	echo '			var ID=$(this);'."\n";
	echo '			$.ajax({'."\n";
	echo '				url : "administrar_ajax.php",'."\n";
	echo '				type: "POST",'."\n";
	echo '				data : {"ID": $(this).attr("id"), "Status": $(this).attr("status")}, '."\n";
	echo '				success:function(data){'."\n";
	echo "					if (data==''){\n";
	echo '						if ($(ID).attr("status")=="1"){'."\n";
	echo '							$(ID).html("Activado");'."\n";
	echo '							$(ID).removeClass("btn-danger");'."\n";
	echo '							$(ID).addClass("btn-success");'."\n";
	echo '						}else{'."\n";
	echo '							$(ID).html("Desactivado");'."\n";
	echo '							$(ID).removeClass("btn-success");'."\n";
	echo '							$(ID).addClass("btn-danger");'."\n";
	echo '						}'."\n";
	echo '					}else{'."\n";
	echo '						alert("Error al actualizar");'."\n";
	echo '					}'."\n";
	echo '				},'."\n";
	echo '				error: function(data){'."\n";
	echo '					alert(data);'."\n";
	echo '				}'."\n";
	echo '			});'."\n";
	echo '		});'."\n";
	echo '	</script>'."\n";
}
?>
</body>
</html>

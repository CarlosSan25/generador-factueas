<?php

include 'head.php';

?>

<body>
	<div class="row w-100">
		<div class="col-11 mx-auto my-3 alert alert-danger alert-dismissible fade show" id="alert-error" role="alert" style="display:none;">
			<span></span>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	</div>
	<div class="table-responsive m-md-2 mt-md-3" id="table-container"></div>
</body>
<script>
	$(document).ready(function(){
		function loadTable(){

			$.post( "ajax.php", {token:"D1ktghOZ5MHRD1tff1N3", action:"loadHTMLFacturas"}, function(datos){
				var respuesta = JSON.parse(datos);

				if(respuesta['status'] == 'success'){
					$("#table-container").empty();
					$("#table-container").html(respuesta['data']);
					loadListeners();
				} else{
					$("#alert-error>span").text(respuesta['data']);
					$("#alert-error").css("display", "block");
				}

			});
		}

		function loadListeners(){
			$(".descargar").click(function(){
				var id = $(this).attr("data-id");
				var nombre = $(this).attr("data-nombre");
				var path = "/facturas/"+id+".pdf";
				var anchor = document.createElement('a');
				anchor.href = path;
				anchor.target = '_blank';
				anchor.download = nombre;
				anchor.click();
				anchor.remove();
			});
	
			$(".eliminar").click(function(){
				var id = $(this).attr("data-id");
	
				if(confirm("¿Seguro que quieres eliminar esta factura?")){
					$.post( "ajax.php", {token:"D1ktghOZ5MHRD1tff1N3", action:"borrarArchivo", id:id}, function(datos){
						var respuesta = JSON.parse(datos);
						if(respuesta['status'] == "success"){
							loadTable();
						} else{
							$("#alert-error>span").text(respuesta['data']);
							$("#alert-error").css("display", "block");
						}
					});
				}
	
			})
		};

		loadTable();

	});
</script>
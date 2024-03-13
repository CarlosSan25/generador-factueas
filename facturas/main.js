$(document).ready(function(){
    $("#add-fila").on("click", function(){
        $("#filas").append('<div class="row mt-4"><div class="col-6 col-sm-7"><div class="mb-3"><input type="text" class="form-control" id="conceptoFactura" name="conceptoFactura[]"></div></div><div class="col-4"><div class="mb-3"><input type="number" required step=".01" class="form-control" id="importeFactura" name="importeFactura[]"></div></div><div class="col-1"><button type="button" class="btn btn-danger del-fila ms-1"><i class="fa-solid fa-minus"></i></button></div></div>');

        $(".del-fila").on("click", function(){
            $(this).parent().parent().remove();
        });
    });

    $("input:radio[name='tipoCliente']").click(function(){
        var type = $(this).val();
        
        if(type=="comunidad"){
            $("#ivaFactura").val("10");
        } else{
            $("#ivaFactura").val("21");
        }

        getLastNumeracion(type);

    });

    function getLastNumeracion(tipoCliente){
        $.post( "ajax.php", {token:"D1ktghOZ5MHRD1tff1N3", action:"getLastNumeracion", tipoCliente:tipoCliente}, function(datos){
            var respuesta = JSON.parse(datos);
            if(respuesta['status'] == "success"){
                var numeracion = respuesta["data"].replace(/['"]+/g, '');
                $("#numeroFactura").val(parseInt(numeracion)+1);
            }
        });
    }

    getLastNumeracion("general");

})
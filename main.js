$(document).ready(function(){
    $("#add-fila").on("click", function(){
        $("#filas").append('<div class="row mt-4"><div class="col-8"><div class="mb-3"><input type="text" class="form-control" id="conceptoFactura" name="conceptoFactura[]"></div></div><div class="col-3"><div class="mb-3"><input type="number" required step=".01" class="form-control" id="importeFactura" name="importeFactura[]"></div></div><div class="col-1"><label class="form-label">&nbsp;</label><button type="button" class="btn btn-danger del-fila"><strong>-</strong></button></div></div>');

        $(".del-fila").on("click", function(){
            $(this).parent().parent().remove();
        });
    });
})
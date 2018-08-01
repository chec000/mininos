	function eliminarProducto(index) {
		
		$.ajax({
			url: 'modelo.php',
			type: 'POST',
			data: {operacion:'delate',index:index}

		})
		.done(function( data ) {			
			$("#producto-"+index).remove();		
		}).fail(function() {
			alert( "error" );
		});
	}



function Agregar(index,cantidad,operacion){
		$.ajax({
			url: 'modelo.php',
			type: 'POST',
			data: {operacion:operacion,cantidad:cantidad,index:index}

		})
		.done(function( data ) {

			if (data=="false") {
			$div='<div class="alert alert-danger alert-dismissible fade in">    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Alerta!</strong>Es posible que ya no existe producto en stock.</div>';
			$("#errors").empty();
			$("#errors").append($div);

			}else{
			location.reload();
			}

		}).fail(function() {
			alert( "error" );
		});

		
	}

	function getCliente(){
		var documento=$("#documento").val();
		$.ajax({
			url: 'modelo.php',
			type: 'POST',
			data: {operacion:'buscarcliente',documento:documento}

		})
		.done(function( data ) {

			if (data=="false") {
			$div='<div class="alert alert-danger alert-dismissible fade in">    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Alerta!</strong>El cliente no existe</div>';
			$("#errors_cliente").empty();
			$("#errors_cliente").append($div);

			}else{
			location.reload();
			}

		}).fail(function() {
			alert( "error" );
		});

		
	}

		$(document).ready(function(){
					$('#btnImprimir').click(function(){
						$.ajax({
							url: 'ticket.php',
							type: 'POST',
							success: function(response){
								if(response==1){
									alert('Imprimiendo....');
								}else{
									alert('Error');
								}
							}
						}); 
					});
				});
		
 function pago(){
 	var tipo_pago=$("#tipo_pago").val();
 	var descuento=$("#descuento").val();
 	var total=$("#pago").val();
 	total=total-descuento;
 	$.ajax({
			url: 'modelo.php',
			type: 'POST',
			data: {operacion:'facturacion',tipo_pago:tipo_pago,descuento:descuento,total:total}

		})
		.done(function( data ) {
		location.reload();
		}).fail(function() {
			alert( "error" );
		});
 }


	function updateCuantity(index,operacion,idProducto) {
		var precio=$("#total-price-"+idProducto).val();
	$.ajax({
			url: 'modelo.php',
			type: 'POST',
			data: {operacion:operacion,precio:precio,index:index}

		})
		.done(function( data ) {
			location.reload();
		}).fail(function() {
			alert( "error" );
		});
	}

function addProducto(){
		var	codigo=$("#codigo").val();
		var cantidad=$("#cantidad").val();
		var precio=$("#precio").val();
	if (codigo!=""&&cantidad!="") {
		$.ajax({
			url: 'modelo.php',
			type: 'POST',
			data: {operacion:'buscararticulo',cantidad:cantidad,codigo:codigo,precio:precio}

		})
		.done(function( data ) {
			if (data=="false") {
			$div='<div class="alert alert-danger alert-dismissible fade in">    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Alerta!</strong>Es posible que ya no existe producto en stock.</div>';
			$("#errors").empty();
			$("#errors").append($div);

			}else{
			location.reload();
			}
		}).fail(function() {
			$div='<div class="alert alert-danger alert-dismissible fade in">    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Alerta!</strong>Existió un error, contacte a soporte</div>';
			$("#errors").empty();
			$("#errors").append($div);
		});

	}
		
	}
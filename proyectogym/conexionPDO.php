<?php
try {
	$bd=new PDO('mysql:host=localhost;dbname=gymdb','root',''); 

} catch (Exception $e) {
	echo "ERROR".$e->getMessage();
}

	//date_default_timezone_set('America/Santo_Domingo');
//servidor, usuario de base de datos, contraseña del usuario, nombre de base de datosecho


/*
 echo "<pre>";
print_r($_SESSION);
echo "</pre>";





SELECT venta.numero_venta, venta.cliente, clietes.nombre, clietes.telefono, detalle_venta.num_detalle, detalle_venta.articulo, articulo.descripcion, articulo.precio, detalle_venta.cantidad
FROM venta, clietes, detalle_venta, articulo
WHERE venta.cliente= clietes.documento
AND venta.numero_venta=detalle_venta.venta
AND detalle_venta.articulo = articulo.codigo






*/


?>
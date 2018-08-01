<?php 
session_start();

$operacion = $_REQUEST['operacion'];

switch ($operacion) {
	case 'buscarcliente':
	buscarcliente();
	break;
	case 'buscararticulo':
	buscararticulo();
	break;
	case 'cancelar':
	cancelar();
	break;	
	case 'facturacion':
	facturacion();
	break;
	case 'devolver':
	devolver();
	break;	
	case 'delate':
	eliminar($_REQUEST['index']);
	break;
	case "agregar":
     addMore($_REQUEST['index'],$_REQUEST['cantidad']);
	break;

	case "quitar":
     quitar($_REQUEST['index'],$_REQUEST['cantidad']);

	break;

	case "sustituir":

	break;
	case "updatePrice":
     updatePrice();
	break;


}

function updatePrice(){


	$_SESSION['articulos'][$_REQUEST['index']]->precio=$_REQUEST['precio'];
	$_SESSION['articulos'][$_REQUEST['index']]->subtotal=$_SESSION['articulos'][$_REQUEST['index']]->cantidad*$_REQUEST['precio'];
}


function addMore($key,$cantidad){

	$id=$_SESSION['articulos'][$key]->id_p;
	include("conexionPDO.php");
	$articulo = $bd->query("SELECT * FROM producto WHERE id_p =".$id)->fetch(PDO::FETCH_OBJ);

	if ($articulo!=null) {
	if ($articulo->control_stock>=$cantidad) {
			$_SESSION['articulos'][$key]->cantidad=$_SESSION['articulos'][$key]->cantidad+$cantidad;;
	$_SESSION['articulos'][$key]->subtotal=$_SESSION['articulos'][$key]->cantidad*$_SESSION['articulos'][$key]->precio;
	
	}else{
	 echo "false";
	}
	}



}

function quitar($key,$cantidad){
	$_SESSION['articulos'][$key]->cantidad=$_SESSION['articulos'][$key]->cantidad-$cantidad;
	$_SESSION['articulos'][$key]->subtotal=$_SESSION['articulos'][$key]->cantidad*$_SESSION['articulos'][$key]->precio;
	
}

function eliminar($index){
	$articulo=$_SESSION["articulos"][$index];
	include("conexionPDO.php");	
	 ;

 if (updateCantidaArticulo($articulo->id_p,$articulo->cantidad,$bd,"update")) {
 		$articulos=$_SESSION["articulos"];
		unset($_SESSION["articulos"][$index]);
echo "true";
 }else{
echo "false";
 }

}



function buscarcliente()
{
	include("conexionPDO.php");
	$documento = $_REQUEST['documento'];

	$verificacliente = $bd->query("SELECT * from cliente WHERE codigo=".$documento)->fetch(PDO::FETCH_OBJ);

	if(!$verificacliente)
	{
	 echo "false";
	}
	else
	{
		$cliente = $bd->query("SELECT * FROM cliente WHERE codigo = $documento")->fetch(PDO::FETCH_OBJ);
		$_SESSION['cliente']=$cliente;
		
	}
	
}





function updateCantidaArticulo($id_p,$cantidad,$bd,$operacion){
	$articulo = $bd->query("SELECT * FROM producto WHERE id_p =".$id_p)->fetch(PDO::FETCH_OBJ);

	if ($articulo!=null) {
		if ($operacion=="agregar") {
			$cantidadInicial=$articulo->control_stock-$cantidad;
			$sql="UPDATE producto set control_stock=$cantidadInicial WHERE id_p=$id_p";
			$stmt = $bd->prepare($sql); 
			return	$stmt->execute(); 
		}else{
			$cantidadInicial=$articulo->control_stock+$cantidad;
			$sql="UPDATE producto set control_stock=$cantidadInicial WHERE id_p=$id_p";
			$stmt=$bd->prepare($sql); 
			return 	$stmt->execute(); 
		}


	}

}

function verificarArticuloEnSession($articulo,$cantidad,$bd){
 	$articulos=$_SESSION['articulos'];

	if (count($articulos)>0) {	
   $bandera=exitElementoInArray($articulos,$articulo);	
 
  if ($bandera!="") {
  	
   if ($bandera[0]) {   
   	if (updateCantidaArticulo($articulo->id_p,$cantidad,$bd,"agregar")) {
			   	//	$_SESSION['articulos'][$bandera[1]]->precio=$_REQUEST['precio'];
					$_SESSION['articulos'][$bandera[1]]->cantidad=$bandera[2]->cantidad+$cantidad;
					$_SESSION['articulos'][$bandera[1]]->subtotal=$bandera[2]->subtotal+($articulo->precio*$cantidad);
		}
	}else{
	
		if (updateCantidaArticulo($articulo->id_p,$cantidad,$bd,"agregar")) {
			$articulo->cantidad= $cantidad;
			//$articulo->precio=$_REQUEST['precio'];		
			$articulo->subtotal= $articulo->precio*$cantidad;
			$_SESSION['articulos'][]=$articulo;
		}
	}
   }else{

		if (updateCantidaArticulo($articulo->id_p,$cantidad,$bd,"agregar")) {
			$articulo->cantidad= $cantidad;
			//$articulo->precio=$_REQUEST['precio'];
			$articulo->subtotal= $articulo->precio*$cantidad;
			$_SESSION['articulos'][]=$articulo;
		}

  }


   }else{
   	if (updateCantidaArticulo($articulo->id_p,$cantidad,$bd,"agregar")) {
			$articulo->cantidad= $cantidad;
			//$articulo->precio=$_REQUEST['precio'];
			$articulo->subtotal= $articulo->precio*$cantidad;
			$_SESSION['articulos'][]=$articulo;
		}
   }

}


 function exitElementoInArray($listProductos,$producto){

foreach ($listProductos as $key => $p) {
 if ($p->id_p==$producto->id_p) {
 return array(true,$key,$p);

 }
}


 }




function buscararticulo()
{
	
	include("conexionPDO.php");
	$cantidad = $_REQUEST['cantidad'];
	$codigo = $_REQUEST['codigo'];

	if ($codigo!=null||$codigo!='') {
		
		$verificaArticulo = $bd->query("SELECT * FROM producto WHERE codigo =".$codigo)->fetch(PDO::FETCH_OBJ);	
	

		if(!$verificaArticulo)
		{
			echo "false";
			}		
		else
		{
			if ($verificaArticulo->control_stock>=$cantidad) {

				verificarArticuloEnSession($verificaArticulo,$cantidad,$bd);
			  
			}else{
				 echo "false";
			}

		}
		
	}else{
	
	 echo "false";
	}


}




//devolver todos
function devolver()
{
	
	$x = $_REQUEST['id'];
	$articulos = $_SESSION['articulos'];

	foreach ($articulos as $a) {
			updateCantidaArticulo($a->id_p,$a->cantidad,$bd,"update");			
	}
		unset($_SESSION["articulos"]);
		header('location:formulario.php');				
		
}




function cancelar()
{
	unset( $_SESSION["cliente"]); 
	unset( $_SESSION["articulos"]); 
	header('location:formulario.php');
}



function facturacion()
{

	include("conexionPDO.php");
	date_default_timezone_set('America/Santo_Domingo');
	$fecha_de_hoy=date("Y-m-d H:i:s");


	if (isset($_SESSION['cliente']))
	{
		$cliente = $_SESSION['cliente'];
		$articulos = $_SESSION['articulos'];
		$total = $_REQUEST['total'];
		$tipo_venta=$_REQUEST['tipo_pago'];
		$id_e=$_SESSION['id'];
		$id_c=$cliente->id_c;
	
	   $bd->query("INSERT INTO `venta` (`id_v`, `total`, `fecha`, `estado`, `id_e`, `id_c`,`tipo_venta`) VALUES (NULL,'$total', '$fecha_de_hoy', 'finalizado', '$id_e', '$id_c','$tipo_venta')");

		$venta = $bd->lastInsertId();	
		foreach ($articulos as $a) {

				$bd->query("INSERT INTO `detalle` (`id_d`, `cantidad`, `sub_total`, `id_v`, `id_p`) VALUES (NULL, '$a->cantidad', '$a->subtotal', '$venta', '$a->codigo')");
		}


		unset($_SESSION["articulos"]);
		echo "true";

	}else{
 		echo "false";
	}

	
	
}
























function imprimir()
{
	include("conexionPDO.php");
	include('fpdf.php');


	$cliente = $_SESSION['cliente'];
	$articulos = $_SESSION['articulos'];
	$total = $_REQUEST['total'];
//

	$imprimir=$bd->query("SELECT venta.id_v,venta.total, venta.id_c, cliente.nombres, cliente.email,detalle.cantidad, detalle.id_d, detalle.id_p, producto.descripcion, producto.precio
		FROM
		venta INNER JOIN cliente ON cliente.id_c=venta.id_c INNER JOIN detalle on detalle.id_v=venta.id_v INNER JOIN producto on detalle.id_p = producto.id_p")->fetchAll(PDO::FETCH_OBJ);

	$pdf=new FPDF();
	$pdf->AddPage();


		    // Inserta un logo en la esquina superior izquierda a 300 ppp
			//$pdf->Image('img/logo_001.jpg',25,5,-550);
	$pdf->Image("img/logo_001.jpg" , 25 ,3, 30 , 30 , "JPG" ,"formulario.php");
			// Inserta una imagen dinámica a través de una URL
			//$pdf->Image('http://chart.googleapis.com/chart?cht=p3&chd=t:60,40&chs=250x100&chl=Hello|World',60,30,90,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->Cell(30);

	$pdf->Cell(120,10, 'MOUNTAIN FITNESS CENTER',0,0,'C');
	$pdf->SetFont('Arial','B',10);
	$pdf->Ln(5);
	$pdf->Cell(180,10, 'Facturacion',0,1,'C');
			//esto es para imprimir la fecha actual del servidor
	$pdf->SetXY(170,3);
	$pdf->Cell(40,10,"Fecha : ".date('d/m/Y'),0,1,'L');
			//fin fecha
	$pdf->Ln(22);

//Datos del clientes
	$pdf->Cell(40,10,'Documento', 1,0,'C');
	$pdf->Cell(90,10,'Nombre', 1,0,'C');
	$pdf->Cell(50,10,'Telefono', 1,1,'C');
	$pdf->Cell(40,10,$cliente->codigo, 1,0,'C');
	$pdf->Cell(90,10,$cliente->nombres, 1,0,'C');
	$pdf->Cell(50,10,$cliente->email, 1,1,'C');

//Salto de linea
	$pdf->Ln();

//Cabezera de la factura
	$pdf->Cell(30,10,'Codigo', 1,0,'C');
	$pdf->Cell(30,10,'Descripcion', 1,0,'C');
	$pdf->Cell(50,10,'Cantidad', 1,0,'C');
	$pdf->Cell(50,10,'Precio', 1,0,'C');
	$pdf->Cell(20,10,'Subtotal', 1,1,'C');

	foreach ($articulos as $a) {
		$pdf->Cell(30,10,$a->codigo, 1,0,'C');
		$pdf->Cell(30,10,$a->descripcion, 1,0,'C');
		$pdf->Cell(50,10,$a->cantidad, 1,0,'C');
		$pdf->Cell(50,10,$a->precio, 1,0,'C');
		$pdf->Cell(20,10,$a->cantidad * $a->precio,1,1,'C');
	}

//Salto de linea
	$pdf->Ln();

//Para el Total
	$pdf->Cell(30,10,'Total', 1,0,'C');
	$pdf->Cell(50,10,$total, 1,1,'C');







//esto es para la firma del coordinador
	$pdf->SetFillColor(232,232,232);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY(10,260);
	$pdf->Cell(180,10, 'Esta factura es valida por un mes',0,1,'C');
	$pdf->SetXY(10,264);
	$pdf->Cell(180,10, 'Depachado por Empleado1',0,1,'C');
	$pdf->SetFont('Arial','', 10);
	$pdf->SetXY(20, 271);
	$pdf->MultiCell(160, 5, utf8_decode('Recuerde guardar el recibo para cualquier reclamacion Gracias por preferirnos '), 0, 'C');

			//fin firma

			//esto es para colocar # de pagina 
	$pdf->SetY(-15);
	$pdf->SetFont('Arial','I', 8);
	$pdf->Cell(0,10, 'Pagina '.$pdf->PageNo().'/{n}',0,0,'R' );
			//fin # de pag

	$pdf->Output();


}
?>

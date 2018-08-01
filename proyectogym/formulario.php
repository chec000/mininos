<?php
session_start();

include("conexion.php");
if(!$_SESSION['verificar'])
{
	header("Location: index.html");
}
$documento="";
$nombre="";
$telefono="";

$cantidad=0;
$codigo=0;
$descripcion="";
$precio=0;

$total=0;


if (isset($_POST['pago'])) {
	$pago=$_POST['pago'];
}
else
{
	$_POST['pago']="";
}

if (isset($_SESSION['cliente'])) 
{
	$documento= $_SESSION['cliente']->codigo;
	$nombre= $_SESSION['cliente']->nombres;
	$telefono= $_SESSION['cliente']->email;
}
else
{
	$cliente = array();
}
if (isset($_SESSION['articulos']))
{
	
	$articulos= $_SESSION['articulos'];

}
else
{
	$articulos = array();
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<title>Mountain Fitness Center - Venta </title>
</head>

<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="homeAdmin.php">Inicio</a>
		</div>

		<ul class="nav navbar-nav navbar-right">
			<li><a href="#"><span class="glyphicon glyphicon-user"></span><?php
			echo  $_SESSION['user'];
			?>	</a></li>
		</ul>
	</div>
</nav>

<body>
	<div class="container">
		<div class="panel panel-info">
			<div class="panel-heading">Datos del Cliente</div>
			<div class="panel-body">	
			<div id="errors_cliente"></div>			
					<div class="form-group">
						<table class="table table-bordered table-hover">
							<tr>
								<td><label></label></td>
								<td><input placeholder="Ingrese el codigo del cliente" type="number" class="form-control" name="documento" id="documento" required="required"></td>
								<td>
									<button  onclick="getCliente()" class="btn btn-primary btn-xs" name="operacion" value="buscarcliente">
										<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
									</button>
								</td>
							</tr>

							<tr>
								<th>Código Cliente</th>
								<th>Nombre</th>
								<th>Telefono</th>
							</tr>

							<tr>
								<td><?php echo $documento;  ?></td>
								<td><?php echo $nombre;  ?></td>
								<td><?php echo $telefono;  ?></td>
							</tr>
						</table>
					</div>				
			</div>
		</div>



		<div class="panel panel-info">
			<div class="panel-heading">Ventas</div>
			<div class="panel-body">
				<div id="errors"></div>
				<div class="form-group">

					<table class="table table-bordered table-hover">
						<tr>
							<td><label>Código</label></td>
							<td><label>Cantidad</label></td>
							<td style="display: none;"><label>Precio</label></td>

						</tr>

						<tr>
							<td><input placeholder="Ingrese en codigo del producto o servicio" id="codigo" type="number" class="form-control" name="codigo" required="required"></td>
							<td><input id="cantidad" placeholder="Ingrese la cantidad" name="codigo" type="number" class="form-control" name="cantidad" required="required"></td>
							<td style="display: none;"><input id="precio" name="precio" type="number" class="form-control" name="cantidad" ></td>
							<td>
								<button class="btn btn-primary btn-xs" onclick="addProducto()" name="operacion" value="buscararticulo">
									<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
								</button>
							</td>
						</tr>

						<tr>
							<th>Código</th>
							<th>Cantidad</th>
							<th>Descripcion</th>
							<th>Precio</th>
							<th>Subtotal</th>
							<th >Acciones</th>
						</tr>

						<?php 
						$i = 0;
						?>
						<?php foreach ($articulos  as $key => $a):?>
							<tr id="producto-<?php echo $key; ?>">
								<td >
									<span >
										<?php  echo $a->codigo; ?>
									</span>   

								</td>
								<td >
									<span id="valor-<?php $a->id_p;  ?>">
										<?php  echo $a->cantidad; ?>

									</span>
									<button type="button" class="btn btn-primary btn-xs" onclick="Agregar(<?php echo $key;?>,1,'agregar')">
										<span >+</span>

									</button>
									<button type="button" class="btn btn-primary btn-xs" onclick="Agregar(<?php echo $key;?>,1,'quitar')">
										<span >-</span>

									</button>


								</td>
								<td ><?php  echo $a->descripcion ?></td>
								<td>

									<input placeholder="Precio" type="number" value="<?php  echo $a->precio; ?>" class="form-control" name="" id="total-price-<?php echo $a->id_p;  ?>">

								</td>
								<td><?php  echo $a->subtotal=$a->precio * $a->cantidad; ?></td> 
								<td>
									<input type="hidden" name="total" value="<?php echo $total; ?>">
									<button  class="btn btn-primary btn-xs" name="operacion" value="devolver"
									onclick="eliminarProducto(<?php echo $key; ?>)">
									Eliminar
								</button>
								<button  class="btn btn-primary btn-xs"  name="operacion" value="devolver"
								onclick="updateCuantity(<?php echo $key;?>,'updatePrice',<?php echo $a->id_p;  ?>)">
								Actualizar
							</button>
						</td>

					</tr>
					<?php  $total += ($a->precio * $a->cantidad); ?>
					<?php 
					$i++;
					?>
				<?php endforeach ?>			       	       

				<!--Esto es para enviarle el total al modelo para la BD-->			       	             
				<input type="hidden" name="total" value="<?php echo $total; ?>">
				<!--Fin-->


			</table>
		</div>

	</div>

</div>

<div class="panel panel-info">
	<div class="panel-heading">Pagar</div>
	<div class="panel-body">
	
			<form action="modelo.php" method="post" style="display: inline-block;">
				<button disabled="disabled" class="btn btn-primary btn-xs" name="operacion" value="facturacion">
					Imprimir
				<span class="glyphicon glyphicon-print" aria-hidden="true"></span>			
			</button>
			</form>
			<form action="modelo.php" method="post" style="display: inline-block;">
				<button  name="operacion"  class="btn btn-primary btn-xs" value="cancelar">
					devolver
					<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
					</button>
		    </form>

			
		<div class="form-group">

			<div class="row">

				<div class="col-md-4">
					<h5>Total :</h5>
						<?php echo $total; ?>
					</div>
					<div class="col-md-4">
						<h5>Descuento :</h5>
							<input  placeholder="Ingrese el monto de descuento" class="form-control" type="number" name="descuento" id="descuento">
						</div>
						<div class="col-md-4">
							<h5>Seleccione el tipo de pago</h5>
								<select id="tipo_pago" class="form-control">
									<option value="credito">Credito</option>
									<option value="efectivo">Efectivo</option>

								</select>

							</div>
						

						</div>

						<div class="row">
								<button class="btn btn-success" onclick="pago()">Pagar <img height="50px;" width="50px" 
									src="https://i.pinimg.com/originals/81/7d/d0/817dd04746826f12d17ba59e0efee23d.png" />
								</button> 
							</div>


					</div>

				</div>
			</div>
</div>

			</body>


			<script src="jquery-3.1.1.min.js"></script>
			<script src="script.js"></script>

			</html>








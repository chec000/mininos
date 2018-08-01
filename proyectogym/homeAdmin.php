<?php
include("conexion.php");
session_start();
if(!$_SESSION['verificar'])
{
	header("Location: index.html");
}


//////////////////////////////////////////////////$catidad_clientes//////////////cantidad de clientes activo
$resultado_cantidad_clientes = $mysqli->query("SELECT COUNT(cliente.id_c) AS 'cantidad_clientes' FROM `cliente` WHERE cliente.estado='activo'");

$resultado_cantidad_clientes->data_seek(0);
while ($row = $resultado_cantidad_clientes->fetch_assoc())
 {
   $cantidad_clientes = $row['cantidad_clientes'];
 }
////////////////------------------------------------------------------------------------------------------------

//////////////////////////////////////////////////$cantidad_clientes_hoy///////////////cantidad_clientes_hoy
$resultado_cantidad_clientes_hoy= $mysqli->query("SELECT COUNT(cliente.id_c) AS 'cantidad_clientes_hoy' FROM cliente WHERE cliente.fecha_inscripcion = '2018-07-17'");

$resultado_cantidad_clientes_hoy->data_seek(0);
while ($row = $resultado_cantidad_clientes_hoy->fetch_assoc())
 {
   $cantidad_clientes_hoy = $row['cantidad_clientes_hoy'];
 }
////////////////------------------------------------------------------------------------------------------------



//////////////////////////////////////////////////$cantidad_clientes_hoy///////////////cantidad_clientes_hoy
 $c_atrasados = 0;
$resultado_cantidad_atrasados= $mysqli->query("SELECT   datediff('2018-07-17',
max(menbre.fecha_pago)) As 'cantidad_atrasados'
FROM menbre INNER JOIN cliente on cliente.codigo=menbre.codigo_c
WHERE cliente.estado='activo'

GROUP BY menbre.codigo_c
ORDER by cantidad_atrasados DESC");

$resultado_cantidad_atrasados->data_seek(0);
while ($row = $resultado_cantidad_atrasados->fetch_assoc())
 {
  $cantidad_atrasados = $row['cantidad_atrasados'];
      if($cantidad_atrasados>30)
      {
        $c_atrasados = $c_atrasados + 1;
      }
   
 }
 //si dias es mayor a 30 dias pues cuenta :p


////////////////------------------------------------------------------------------------------------------------



//////////////////////////////////////////////////$catidad_bloqueado///////////////cantidad de clientes bloqueado
$resultado_cantidad_clientes_inactivo = $mysqli->query("SELECT COUNT(cliente.id_c) AS 'cantidad_bloqueado' FROM `cliente` WHERE cliente.estado='inactivo'");

$resultado_cantidad_clientes_inactivo->data_seek(0);
while ($row = $resultado_cantidad_clientes_inactivo->fetch_assoc())
 {
   $cantidad_bloqueado = $row['cantidad_bloqueado'];
 }
////////////////------------------------------------------------------------------------------------------------

//////////////////////////////////////////////////$total_membrecia///////////////membrecia
$resultado_total_membrecia= $mysqli->query("SELECT SUM(menbre.precio) As 'total_membrecia' FROM menbre");

$resultado_total_membrecia->data_seek(0);
while ($row = $resultado_total_membrecia->fetch_assoc())
 {
   $total_membrecia = $row['total_membrecia'];
 }
////////////////------------------------------------------------------------------------------------------------

//////////////////////////////////////////////////$total_membrecia_hoy///////////////membrecia hoy
$resultado_total_membrecia_hoy= $mysqli->query("SELECT SUM(menbre.precio) As 'total_membrecia_hoy' FROM menbre where menbre.fecha_pago = '2018-07-17'");

$resultado_total_membrecia_hoy->data_seek(0);
while ($row = $resultado_total_membrecia_hoy->fetch_assoc())
 {
   $total_membrecia_hoy = $row['total_membrecia_hoy'];
 }
////////////////------------------------------------------------------------------------------------------------



echo "

<table border='1'>
<tr>
<td>Total cliente Activo </td>
<td>Cliente Nuevo de Hoy</td>
<td>Cliente Con Pago atrasados</td>
<td>Total cliente bloqueado </td>
<td>Total Membrecia </td>
<td>Total Membrecia de Hoy </td>
</tr>

<tr>
<td>$cantidad_clientes</td>
<td>$cantidad_clientes_hoy</td>
<td>$c_atrasados</td>
<td>$cantidad_bloqueado</td>
<td>$total_membrecia</td>
<td>$total_membrecia_hoy</td>
</tr>



</table>

";





?>



<style>


#menu{ 
background-color:#999;
width:220px;
height:550px;
border:double #000000;
top:14px;
}





.botn{
   position:fixed;
   display:scroll;
   padding:8px 50px;
   font-family:'psychotik';
   font-size:1.2em;
   font-weight:normal;
   left:20px;
  
   color:#000;
   text-shadow:none;
   border-radius:16px;
   background:#36F;
   box-shadow:inset 3px 3px 2px #007f8b;
}
.botn:hover{
   background:#FF9C00;
   box-shadow:inset 3px 3px 2px #995f02;
}

.botn_imag{
   
   display:scroll;
   padding:8px 50px;
   font-family:'psychotik';
   font-size:1.2em;
   font-weight:normal;
   right:1045px;
  
   color:#000;
   text-shadow:none;
   border-radius:16px;
   background:#960;
   box-shadow:inset 3px 3px 2px #007f8b;
}
.botn_imag:hover{
   background:#F00;
   box-shadow:inset 3px 3px 2px #995f02;
}
</style> 






<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">

<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<title>Mountain Fitness Center</title>
</head>

<style>
body{
	background:url(img/logo_001.jpg)bottom fixed no-repeat;
	}
</style>



<body>



<div align="right" style="border:double #000000; height:80px; color:#000; background:#999">

<?php

echo "Adminitrador       <b>". $_SESSION['user'];




$resultado = $mysqli->query("SELECT foto FROM empleado where id_e='".$_SESSION['id']."'");

$resultado->data_seek(0);
while ($fila = $resultado->fetch_assoc())
 {
	 ?>
	 <img height="70px" width="70px" src="data:imagen/jmg;base64,<?php echo base64_encode($fila['foto']);?>"/>
     <?php
 }
?>	
<a href="cerrar_session.php">      Cerrar session      </a>

</div>










<div id="menu">

<a href="homeAdmin.php"><input style="top:110px"  class="botn" type="button" value="Inicio..........."  /></a>
<a href="cliente.php"><input style=" top:160px"   class="botn" type="button" value= "Clientes......."/></a>
<a href="producto.php"><input style=" top:210px"  class="botn" type="button" value="Productos....." /></a>
<a href="empleado.php"><input style=" top:310px"  class="botn" type="button" value="Empleado....." /></a>
<a href="reportes.php"><input style=" top:260px"  class="botn" type="button" value="Reportes......." /></a>
<a href="membrecia.php"><input style=" top:360px" class="botn" type="button" value="Membrecia..." /></a>
<a href="formulario.php"><input style=" top:410px"     class="botn" type="button" value="Venta............" /></a>



<a href="cerrar_session.php" > <img style=" position:fixed; top:520px; width:10px; max-height:10px; left:40px;"  class="botn_imag" src="img/apagar.png" /> </a>

<div style=" position:fixed; top:445px; left:80px; "><h3>User :</h3></div>
<div class="avatar" style="position:fixed; background-image: url(../img/ico_buscar.ico)"></div>
<div style="position:fixed; top:482px; color:#036; left:80px; font-size:20px" ><?php echo $_SESSION['user']; ?> </div>































<?php 

if(($_SESSION['user']=="Admin")||($_SESSION['id']=="1"))
{
  
 ?>


<!--inicio del div Modallllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllll-->
<br />
            
<!--esta es la parte donde va cambiar contraseña-->
<a style=" position:fixed; top:580px; left:20px; " class="botn"  href = "javascript:void(0)" onclick = "document.getElementById('light').style.display='block';document.getElementById('fade').style.display='block';">Contraseña</a>




<div id="fade" class="overlay" onclick = "document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'">
</div>

<div id="light" class="modal">
   <div align="right">  
<a style="color:#000" href = "javascript:void(0)" onclick = "document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'">cerrar</a>
</div>
 
 <style>
 
 #for{
   padding:2px;
   cursor:pointer;
   display:block;
   width:100px;
   margin: 0 auto;
   text-align:left;
}
 </style>
       <form id="for"  action="cambiar_pass.php" method="post">
<h3 title="Configuration" style=" color:#000"> <u>Configuracion de Cuenta</u></h3>
<input type="password" id="pass_anterior" name="pass_anterior" placeholder="Contrasena Anterior" />
<br /><br />
<input type="password" id="pass_nueva" name="pass_nueva" placeholder="Contrasena Nueva" />
<br /><br />
<input type="password" id="pass_confirmar" name="pass_confirmar" placeholder="Confirmar Contrasena" />
<br /><br />
<input name="entrar" type="submit" value="Configurar Cuenta"  />
</form>
        
</div>

</div>
</div>
    

<style type="text/css">

 /* base semi-transparente */
    .overlay{
        display: none;
        position:fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #000;
        z-index:1001;
  opacity:.75;
        -moz-opacity: 0.75;
        filter: alpha(opacity=75);
    }
 
    /* estilo para la ventana modal */
    .modal {
        display: none;
        position: absolute;
        top: 30%;
        left: 30%;
        width: 50%;
        height: 50%;
        padding: 16px;
        background: #fff;
  color: #333;
        z-index:1002;
        overflow: auto;
    }
    </style>
<!--fin del div Modallllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllll-->


<?php 



}
else
{
}

 ?>















<style>
#estilo{
	position:absolute;
	width:200px;
	height:80px;
	padding: 0px;
	background-color:#000;
	background:#BDBDBD;
	border-style: dashed;
	border-width: 2px;
	margin: 5px;
	right:10px;
	top:550px;
	text-align:center;
}
#estilo1{
position:absolute;
  width:200px;
  height:80px;
  padding: 0px;
  background-color:#000;
  background:#BDBDBD;
  border-style: dashed;
  border-width: 2px;
  margin: 5px;
  right:10px;
  top:460px;
  text-align:center;
}
#estilo2{
	position:absolute;
	width:200px;
	height:40px;
	padding: 0px;
	
	margin: 5px;
	right:1px;
	top:200px;
	text-align:center;
}

</style> 

 
<!-- <div id="estilo2" >
 <a href="configuarar_fecha.php"><img height="70px" width="70px" src="img/confi.jpg"/></a>
 </div> -->
 
<div id="estilo1" >
 <a href="buscar_cliente.php"><img height="70px" width="70px" src="img/acuerdo.png"/></a>
 </div>
 
<div id="estilo" >
 <a href="menu_atrasados.php"> <img height="70px" width="70px" src="img/atrasado.png"/> </a>
 </div>

<style>
.footer {
    position: fixed;
    left: 0;
    bottom: 0;
    height: 4%;
    width: 100%;
    background-color: #A9D0F5;
    color: black;
    text-align: center;
}
</style>

</div><!--fin del div menu-->

<div class="footer">
<footer id="main-footer" >
		<p> Creado By:   <a href="#"> Ing. Ricky J. Galan Paulino</a> &copy; 2017 Version 2.0.1</p>
	</footer> <!-- / #main-footer -->
</div>



   
   
     
            
</body>
</html>



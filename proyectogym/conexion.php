<?php
	date_default_timezone_set('America/Santo_Domingo');
$mysqli=new mysqli("localhost","root","","gymdb"); 
//servidor, usuario de base de datos, contraseña del usuario, nombre de base de datos
	
	if(mysqli_connect_errno())
	{
     echo 'Error conexion Fallida : ', mysqli_connect_error();
	 exit();
	}
	/*else{
	echo 'Conectado satisfactoriamente   jjj';
     mysqli_close($mysqli);
	}*/
	
?>
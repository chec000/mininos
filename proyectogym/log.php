<style>


#menu{ 
background-color:#696;
width:300px;
height:1000px;
position:relative;
border:double #000000;
right:1024px;
top:14px; 
}

div.avatar {
    /* cambia estos dos valores para definir el tamaño de tu círculo */
    height: 100px;
    width: 100px;
  top:380px;
  position:fixed;
  right:1140px;
    /* los siguientes valores son independientes del tamaño del círculo */
    background-repeat: no-repeat;
    background-position: 50%;
    border-radius: 50%;
    background-size: 100% auto;
}



.botn{
   position:fixed;
   display:scroll;
   padding:8px 50px;
   font-family:'psychotik';
   font-size:1.2em;
   font-weight:normal;
   right:1045px;
  
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
   position:fixed;
   display:scroll;
   padding:8px 50px;
   font-family:'psychotik';
   font-size:1.2em;
   font-weight:normal;
   right:1045px;
  
   color:#000;
   text-shadow:none;
   border-radius:16px;
   background:#36F;
   box-shadow:inset 3px 3px 2px #007f8b;
}
.botn_imag:hover{
   background:#F00;
   box-shadow:inset 3px 3px 2px #995f02;
}
</style> 


<?php
include ("conexion.php");
$usuario=$_POST['usuario'];
$password=$_POST['pass'];


$query = "SELECT * FROM empleado where empleado.usu='$usuario' and empleado.pass='$password'";
$result = $mysqli->query($query);



if(mysqli_num_rows($result) > 0)
{
  $fila=$result->fetch_array();
  
     session_start();

  $_SESSION['id']= $fila['id_e'];
  $_SESSION['user']=$fila['usu'];
  $_SESSION['nombre']=$fila['nombres'].' '.$fila['apellidos']; 
  $_SESSION['verificar']=true;
  
 echo"<script type=\"text/javascript\">alert('Bienvenido $usuario'); window.location='homeAdmin.php';</script>"; 
   
}
else
{

echo"<script type=\"text/javascript\">alert('Usuario o Contraseña Incorrecta'); window.location='index.html';</script>"; 

}


?>
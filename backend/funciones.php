<?php
    
   function verificar_token($token)
   {
        
        require("conexion.php");
        $conexion = mysqli_connect ($server_db, $usuario_db,$password_db)
            or die ("No se puede conectar con el servidor");
        mysqli_select_db ($conexion,$base_db)
            or die ("No se puede seleccionar la base de datos");
        $token=mysqli_real_escape_string($conexion,$token);
        $instruccion = "select * from usuarios where token = '$token'";
        $consulta = mysqli_query ($conexion,$instruccion)
        or die ("Fallo en la consulta");
        $nfilas = mysqli_num_rows ($consulta);
        $rta=false;
        if($nfilas>0)
        {
           $rta=true; 
        }
         mysqli_close($conexion);
        return($rta);
       
    }
    
    
    
   
?>
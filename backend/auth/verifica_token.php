<?php
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Content-Type: application/json');
    require("conexion.php");
    extract($_REQUEST);
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
        $datasuccess=[
        'status'=>200,
        'tpken_presente'=>true
        
    ];
        
    }
    else
    {
        $datasuccess=[
        'status'=>500,
        'tpken_presente'=>false
        
    ];
    }
        mysqli_close($conexion);
    echo json_encode($datasuccess);
        
?>
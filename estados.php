<?php

// SISTEMA DE ESTADOS DEL USUARIO PARA SABER QUE SE VA A HACER CON SU PRÓXIMO MENSAJE.

include 'config/conexion.php';
$message = $update["message"]["text"];

$usuario=mysqli_real_escape_string($conexion,$userId);
$consulta="SELECT * FROM jugadores WHERE idUsuario='$usuario';";
$datos=mysqli_query($conexion,$consulta);

if(mysqli_num_rows($datos)>0){
  $fila=mysqli_fetch_array($datos,MYSQLI_ASSOC);

  if($fila['estado']==6){

  if($message == 'No' || $message == 'no'){
    $response = "✅ $firstname de acuerdo, dejaré la raza que tienes actualmente. Recuerda que siempre y cuando tengas el dinero necesario, podrás realizar el cambio de raza.";
    sendMessage($userId, $response, FALSE);

    $consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
    mysqli_query($conexion, $consulta);

    mysqli_close($conexion);
    exit;
  }else{

    $razaJ1 = $fila['raza'];
    $dineroJ1 = $fila['dinero'];

    $dineroInsertar = $dineroJ1 - 10000;

    if($message == 'Informático' || $message == 'Informatico' || $message == 'informático' || $message == 'informatico'){

      if($razaJ1 == 'informático'){
        $response = "⛔ $firstname actualmente ya eres esa raza. Si quieres hacer el cambio deberá ser a otra raza a la que no pertenezcas.";
        sendMessage($userId, $response, FALSE);
        $consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
        mysqli_query($conexion, $consulta);
        mysqli_close($conexion);
        exit;
      }

      $consulta="UPDATE jugadores SET raza='Informático', dinero='$dineroInsertar', estado='0' WHERE idUsuario='$userId';";
      mysqli_query($conexion, $consulta);

      $response = "🖥 ¡Enhorabuena, ahora eres Informático! Te queda $dineroInsertar de dinero.";
      sendMessage($userId, $response, FALSE);

    }else if($message == 'Teleco' || $message == 'teleco'){

      if($razaJ1 == 'teleco'){
        $response = "⛔ $firstname actualmente ya eres esa raza. Si quieres hacer el cambio deberá ser a otra raza a la que no pertenezcas.";
        sendMessage($userId, $response, FALSE);
        $consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
        mysqli_query($conexion, $consulta);
        mysqli_close($conexion);
        exit;
      }

      $consulta="UPDATE jugadores SET raza='teleco', dinero='$dineroInsertar', estado='0' WHERE idUsuario='$userId';";
      mysqli_query($conexion, $consulta);

      $response = "📡 ¡Enhorabuena, ahora eres Teleco! Te queda $dineroInsertar de dinero.";
      sendMessage($userId, $response, FALSE);

    }else if($message == 'Intruso' || $message == 'intruso'){

      if($razaJ1 == 'intruso'){
        $response = "⛔ $firstname actualmente ya eres esa raza. Si quieres hacer el cambio deberá ser a otra raza a la que no pertenezcas.";
        sendMessage($userId, $response, FALSE);
        $consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
        mysqli_query($conexion, $consulta);
        mysqli_close($conexion);
        exit;
      }

      $consulta="UPDATE jugadores SET raza='intruso', dinero='$dineroInsertar', estado='0' WHERE idUsuario='$userId';";
      mysqli_query($conexion, $consulta);

      $response = "🕵️‍♀️ ¡Enhorabuena, ahora eres Intruso! Te queda $dineroInsertar de dinero.";
      sendMessage($userId, $response, FALSE);

    }else{

      $response = "⁉ Lo siento, pero no entiendo lo que quieres decir, inténtalo de nuevo.";
      sendMessage($userId, $response, FALSE);

    }

  }

    mysqli_close($conexion);
    exit;

}else if($fila['estado']==7){

  $consulta = "SELECT * FROM jugadores;";
  $datos=mysqli_query($conexion,$consulta);

  while($fila=mysqli_fetch_array($datos,MYSQLI_ASSOC)){

    $idUsuario = $fila['idUsuario'];
    $nombreUsuario = $fila['nombre'];

    $response = "$message";
    sendMessage($idUsuario, $response, FALSE);

  }

  $consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
  mysqli_query($conexion, $consulta);

  $response = "✅ ¡Listo, ya se ha enviado el mensaje a todos los usuarios!";
  sendMessage($userId, $response, FALSE);

  mysqli_close($conexion);
  exit;

}  // FINAL ESTADO 7


}

?>

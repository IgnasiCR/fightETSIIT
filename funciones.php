<?php

function confirmacion($userId, $peleasPosibles, $estado, $firstname){

  if(($peleasPosibles == 10 || $peleasPosibles == 20) && $estado == 0){

    $numeroUno = rand(1,20);
    $numeroDos = rand(1,20);
    $suma = $numeroUno + $numeroDos;
    $aleatorio = rand(0,1);

    if($aleatorio == 1){
      $numeroUno = numToText($numeroUno);
      $numeroDos = numToText($numeroDos);
    }

    include 'config/conexion.php';
    $usuario=mysqli_real_escape_string($conexion,$userId);
    $consulta="UPDATE jugadores SET verificacion='$suma', estado=8 WHERE idUsuario='$usuario';";
    mysqli_query($conexion,$consulta);
    mysqli_close($conexion);

    include 'config/conexion2.php';
    $usuario2=mysqli_real_escape_string($conexion2,$userId);
    $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$usuario2';";
    mysqli_query($conexion2,$consulta2);
    mysqli_close($conexion2);

    $response = "❓ ¿$firstname serás capaz de pasar la prueba anti-bot? ¿Cuánto es $numeroUno + $numeroDos? Escribe la respuesta a continuación en números.";
    sendMessage($userId, $response, FALSE);

    exit;
  }else if($estado == 8){
    include 'config/conexion.php';
    $usuario=mysqli_real_escape_string($conexion,$userId);
    $consulta="UPDATE jugadores SET estado='0', estado_pelea='1', peleas_posibles='0', verificacion=0 WHERE idUsuario='$usuario';";
    mysqli_query($conexion,$consulta);

    include 'config/conexion2.php';
    $usuario2=mysqli_real_escape_string($conexion2,$userId);
    $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$usuario2';";
    mysqli_query($conexion2,$consulta2);
    mysqli_close($conexion2);

    $response = "⛔ ¡Lo siento, $firstname! No has conseguido pasar la prueba anti-bot. ¡Te quedas sin luchas hasta la siguiente renovación.";
    sendMessage($userId, $response, FALSE);
    exit;
  }else if($estado == 9){
    include 'config/conexion.php';
    $usuario=mysqli_real_escape_string($conexion,$userId);
    $consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$usuario';";
    mysqli_query($conexion,$consulta);
  }

}

function numToText($numero){

  switch($numero){
    case '1': $texto='uno'; break;
    case '2': $texto='dos'; break;
    case '3': $texto='tres'; break;
    case '4': $texto='cuatro'; break;
    case '5': $texto='cinco'; break;
    case '6': $texto='seis'; break;
    case '7': $texto='siete'; break;
    case '8': $texto='ocho'; break;
    case '9': $texto='nueve'; break;
    case '10': $texto='diez'; break;
    case '11': $texto='once'; break;
    case '12': $texto='doce'; break;
    case '13': $texto='trece'; break;
    case '14': $texto='catorce'; break;
    case '15': $texto='quince'; break;
    case '16': $texto='dieciseis'; break;
    case '17': $texto='diecisiete'; break;
    case '18': $texto='dieciocho'; break;
    case '19': $texto='diecinueve'; break;
    case '20': $texto='veinte'; break;
  }

  return $texto;

}

function sendDeleteMessage($chatId, $messageId, $response, $links){
  sendMessage($chatId, $response, $links);
  deleteMessage($chatId, $messageId);
}

function sendMessage($chatId, $response, $links){
    if($links){
        $url = $GLOBALS[website].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response).'&disable_notification=true&disable_web_page_preview=true';
    }else{
        $url = $GLOBALS[website].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response).'&disable_notification=true';
    }
    file_get_contents($url);
}

function deleteMessage($chatId, $messageId){
   $url = $GLOBALS[website].'/deleteMessage?chat_id='.$chatId.'&message_id='.$messageId;
   file_get_contents($url);
}

function sendPhoto($chatId,$urlphoto,$response){
  if($response == ""){
    $url = $GLOBALS[website].'/sendPhoto?chat_id='.$chatId.'&photo='.$urlphoto.'&disable_notification=true';
  }else{
    $url = $GLOBALS[website].'/sendPhoto?chat_id='.$chatId.'&photo='.$urlphoto.'&caption='.$response.'&disable_notification=true';
  }
  file_get_contents($url);
}

function sendSticker($chatId, $urlsticker){
  $url = $GLOBALS[website].'/sendSticker?chat_id='.$chatId.'&sticker='.$urlsticker.'&disable_notification=true';
  file_get_contents($url);
}

 ?>

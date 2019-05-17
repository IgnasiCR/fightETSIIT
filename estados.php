<?php

// SISTEMA DE ESTADOS DEL USUARIO PARA SABER QUE SE VA A HACER CON SU PRÓXIMO MENSAJE.

include 'config/conexion.php';
$message = $update["message"]["text"];

$usuario=mysqli_real_escape_string($conexion,$userId);
$consulta="SELECT * FROM jugadores WHERE idUsuario='$usuario';";
$datos=mysqli_query($conexion,$consulta);

if(mysqli_num_rows($datos)>0){
  $fila=mysqli_fetch_array($datos,MYSQLI_ASSOC);

  if($fila['estado']==1){ // PARA MODIFICAR EL NOMBRE O NO DEL PERSONAJE REGISTRADO.

    if($message == 'Si' || $message == 'si'){
      $response = "✅ $firstname de acuerdo, dejaré tu nombre de jugador tal como está. Ahora me tendrás que indicar que raza serás: 'Informático', 'Teleco' o 'Intruso'.";
      sendMessage($userId, $response, FALSE);

      $consulta="UPDATE jugadores SET estado='2' WHERE idUsuario='$userId';";
      mysqli_query($conexion, $consulta);

      mysqli_close($conexion);
      exit;
    }else{

      $consulta="SELECT * FROM jugadores WHERE nombre='$message';";
      $datos=mysqli_query($conexion,$consulta);

      if(mysqli_num_rows($datos)>0){

        $response = "⛔ $firstname el nombre que has querido utilizar ya está en uso, tendrás que elegir otro. Aún estás a tiempo de indicar el que tenías diciendo 'Si'.";
        sendMessage($userId, $response, FALSE);

      }else{

        $consulta="UPDATE jugadores SET nombre='$message' WHERE idUsuario='$userId';";
        mysqli_query($conexion, $consulta);

        $response = "✅ $firstname de acuerdo, tu nombre de usuario ha sido cambiado a $message. Ahora me tendrás que indicar que raza serás: 'Informático', 'Teleco' o 'Intruso'. Recuerda que esto no lo podrás modificar en ningún momento.";
        sendMessage($userId, $response, FALSE);

        $consulta="UPDATE jugadores SET estado='2' WHERE idUsuario='$userId';";
        mysqli_query($conexion, $consulta);

      }

      mysqli_close($conexion);
      exit;
    }

  }else if($fila['estado']==2){ // PARA AÑADIR RAZA AL PERSONAJE REGISTRADO.

    if($message == 'Informático' || $message == 'Informatico' || $message == 'informático' || $message == 'informatico'){

      $ataqueSumar = $fila['ataque']+5;

      $consulta="UPDATE jugadores SET raza='$message', ataque='$ataqueSumar', estado='0' WHERE idUsuario='$userId';";
      mysqli_query($conexion, $consulta);

      $response = "🖥 ¡Enhorabuena, ahora eres Informático! Tu ataque ha aumentado en 5 puntos.";
      sendMessage($userId, $response, FALSE);

    }else if($message == 'Teleco' || $message == 'teleco'){

      $vidaSumar = $fila['vida']+5;

      $consulta="UPDATE jugadores SET raza='$message', vida='$vidaSumar', estado='0' WHERE idUsuario='$userId';";
      mysqli_query($conexion, $consulta);

      $response = "📡 ¡Enhorabuena, ahora eres Teleco! Tu vida ha aumentado en 5 puntos.";
      sendMessage($userId, $response, FALSE);

    }else if($message == 'Intruso' || $message == 'intruso'){

      $defensaSumar = $fila['defensa']+5;

      $consulta="UPDATE jugadores SET raza='$message', defensa='$defensaSumar', estado='0' WHERE idUsuario='$userId';";
      mysqli_query($conexion, $consulta);

      $response = "🕵️‍♀️ ¡Enhorabuena, ahora eres Intruso! Tu defensa ha aumentado en 5 puntos.";
      sendMessage($userId, $response, FALSE);

    }else{

      $response = "⁉ Lo siento, pero no entiendo lo que quieres decir, inténtalo de nuevo.";
      sendMessage($userId, $response, FALSE);

    }

    mysqli_close($conexion);
    exit;

  /*}else if($fila['estado']==3){ // PARA COMPRAR OBJETOS DE LA TIENDA.

    if(!(is_numeric($message))){
      $response = "⛔ $firstname ¿qué te pensabas que somos tontos aquí o qué? Eso no es ningún identificador.";
      sendDeleteMessage($userId, $messageId, $response, FALSE);
      $consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
      mysqli_query($conexion, $consulta);
      mysqli_close($conexion);
      exit;
    }

    $razaJugador = $fila['raza'];
    $dineroActual = $fila['dinero'];
    $vidaActual = $fila['vida'];
    $ataqueActual = $fila['ataque'];
    $defensaActual = $fila['defensa'];

    $consulta = "SELECT * FROM objetos WHERE raza='$razaJugador' ORDER BY idObjeto DESC LIMIT 1;";
    $datos=mysqli_query($conexion,$consulta);

    $consulta2 = "SELECT * FROM objetos WHERE raza='$razaJugador' ORDER BY idObjeto ASC LIMIT 1;";
    $datos2=mysqli_query($conexion,$consulta2);

    if(mysqli_num_rows($datos)>0){
      $fila=mysqli_fetch_array($datos,MYSQLI_ASSOC);
      $fila2=mysqli_fetch_array($datoS2,MYSQLI_ASSOC);

      if($message >= $fila2['idObjeto'] && $message <= $fila['idObjeto']){

        $consulta = "SELECT * FROM objetos WHERE idObjeto='$message' AND raza='$razaJugador';";
        $datos=mysqli_query($conexion,$consulta);

        if(mysqli_num_rows($datos)<=0){
          $response = "⛔ ¿Estás intentando comprar un objeto de la tienda que no te pertenece? No intentes pasarte de listo o te prohibiré la entrada. ¡FUERA!";
          sendDeleteMessage($userId, $messageId, $response, FALSE);
          $consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
          mysqli_query($conexion, $consulta);
          mysqli_close($conexion);
          exit;
        }

        $fila=mysqli_fetch_array($datos,MYSQLI_ASSOC);

        $dinero = $fila['dinero'];

        if($dineroActual >= $dinero){

        $ataque = $fila['ataque'];
        $defensa = $fila['defensa'];
        $vida = $fila['vida'];

        if($ataque > 0){
          $ataqueSumar = $ataque + $ataqueActual;
        }else{
          $ataqueSumar = $ataqueActual;
        }

        if($defensa > 0){
          $defensaSumar = $defensa + $defensaActual;
        }else{
          $defensaSumar = $defensaActual;
        }

        if($vida > 0){
          $vidaSumar = $vida + $vidaActual;
        }else{
          $vidaSumar = $vidaActual;
        }

        $dineroRestar = $dineroActual - $dinero;

        $consulta="UPDATE jugadores SET ataque=$ataqueSumar, vida='$vidaSumar', defensa='$defensaSumar', dinero='$dineroRestar', estado='0' WHERE idUsuario='$userId';";
        mysqli_query($conexion, $consulta);

        $consulta2="INSERT INTO compras (idUsuario, idObjeto, fecha) VALUES('$userId','$message',NOW());";
        mysqli_query($conexion, $consulta2);

        $response = "💳 Has comprado el objeto $fila[nombre]. Se te han descontado $dinero por la compra, ahora tienes $dineroRestar.";
        sendMessage($userId, $response, FALSE);

      }else{

        $response = "⛔ No tienes suficiente dinero para hacer la compra del objeto. Inténtalo de nuevo en cuánto consigas el dinero necesario.";
        sendMessage($userId, $response, FALSE);

        $consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
        mysqli_query($conexion, $consulta);

      }

      }else{

        $response = "⛔ El identificador que has seleccionado es incorrecto, si quieres volver a comprar algo de verdad utiliza de nuevo /comprar.";
        sendMessage($userId, $response, FALSE);

        $consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
        mysqli_query($conexion, $consulta);

      }

    }else{

      $response = "⛔ Lo siento pero no hay ningún objeto a la venta en la tienda, intentálo en otro momento.";
      sendMessage($userId, $response, FALSE);

      $consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
      mysqli_query($conexion, $consulta);

    }

    mysqli_close($conexion);
    exit;

  }else if($fila['estado']==4){ // SISTEMA PARA PELEAS ENTRE DOS USUARIOS.

    $idJ1 = $fila['idUsuario'];
    $nombreJ1 = $fila['nombre'];
    $razaJ1 = $fila['raza'];
    $nivelJ1 = $fila['nivel'];
    $vidaJ1 = $fila['vida'];
    $vidaGJ1 = $vidaJ1;
    $defensaJ1 = $fila['defensa'];
    $ataqueJ1 = $fila['ataque'];
    $premiumJ1 = $fila['premium'];

    if($nombreJ1 != $message){

    $consultaJ2 = "SELECT * FROM jugadores WHERE nombre='$message';";
    $datosJ2 = mysqli_query($conexion,$consultaJ2);

    if(mysqli_num_rows($datosJ2)>0){
      $filaJ2=mysqli_fetch_array($datosJ2,MYSQLI_ASSOC);

      $idJ2 = $filaJ2['idUsuario'];
      $nombreJ2 = $filaJ2['nombre'];
      $razaJ2 = $filaJ2['raza'];
      $nivelJ2 = $filaJ2['nivel'];
      $vidaJ2 = $filaJ2['vida'];
      $vidaGJ2 = $vidaJ2;
      $defensaJ2 = $filaJ2['defensa'];
      $ataqueJ2 = $filaJ2['ataque'];

      if($nivelJ2<($nivelJ1-3) || $nivelJ2>($nivelJ1+3)){
        $response = "⛔ El jugador con el que quieres luchar le sacas o te saca 3 niveles, si quieres luchar contra él puedes hacerlo con /lucharamistoso.";
        sendMessage($userId, $response, FALSE);

        $consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
        mysqli_query($conexion, $consulta);

        include 'config/conexion2.php';
        $usuario2=mysqli_real_escape_string($conexion,$userId);
        $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
        mysqli_query($conexion2,$consulta2);
        mysqli_close($conexion2);
      }else{

        $response = "⏳ ¡Empieza el combate contra $nombreJ2! Que gane el mejor jugador.";
        sendMessage($userId, $response, FALSE);

        while($vidaJ1 > 0 && $vidaJ2 > 0){

          $porcentajeAlJ1 = rand(0,100);
          $porcentajeAlJ2 = rand(0,100);

          if($porcentajeAlJ1 >= 0 && $porcentajeAlJ1 <= 20){
            if($razaJ1 == 'informático'){
              $ataqueSumarJ1 = $ataqueJ1/2;
              $response = "🔰🔵 ¡Has obtenido tu poder oculto y durante este turno obtendrás un 50% más de ataque!";
              sendMessage($userId, $response, FALSE);
            }else if($razaJ1 == 'teleco'){
              $vidaSumarJ1 = $vidaGJ1/2;
              $vidaJ1 = $vidaJ1 + $vidaSumarJ1;
              $response = "🔰🔵 ¡Has obtenido tu poder oculto y te ha aumentado la vida! Ahora mismo tienes $vidaJ1.";
              sendMessage($userId, $response, FALSE);
            }else if($razaJ1 == 'intruso'){
              $defensaSumarJ1 = $defensaJ1/2;
              $response = "🔰🔵 ¡Has obtenido tu poder oculto y durante este turno obtendrás un 50% más de defensa!";
              sendMessage($userId, $response, FALSE);
            }
          }else{
            $ataqueSumarJ1 = 0;
            $vidaSumarJ1 = 0;
            $defensaSumarJ1 = 0;
          }

          if($porcentajeAlJ2 >= 0 && $porcentajeAlJ2 <= 20){
            if($razaJ2 == 'informático'){
              $ataqueSumarJ2 = $ataqueJ2/2;
              $response = "🔰🔴 ¡Tu enemigo ha obtenido su poder oculto y durante este turno obtendrá un 50% más de ataque!";
              sendMessage($userId, $response, FALSE);
            }else if($razaJ2 == 'teleco'){
              $vidaSumarJ2 = $vidaGJ2/2;
              $vidaJ2 = $vidaJ2 + $vidaSumarJ2;
              $response = "🔰🔴 ¡Tu enemigo ha obtenido su poder oculto y se le ha aumentado la vida! Ahora mismo tiene $vidaJ2.";
              sendMessage($userId, $response, FALSE);
            }else if($razaJ2 == 'intruso'){
              $defensaSumarJ2 = $defensaJ2/2;
              $response = "🔰🔴 ¡Tu enemigo ha obtenido su poder oculto y durante este turno obtendrá un 50% más de defensa!";
              sendMessage($userId, $response, FALSE);
            }
          }else{
            $ataqueSumarJ2 = 0;
            $vidaSumarJ2 = 0;
            $defensaSumarJ2 = 0;
          }

          $maxAtaque = $ataqueJ1 - (($defensaJ2+$defensaSumarJ2)/2);
          if($maxAtaque <= 1){
            $ataqueRJ1 = rand($ataqueJ1/($defensaJ2+$defensaSumarJ2), $ataqueJ1/2) + $ataqueSumarJ1;
            $vidaJ2 = $vidaJ2 - $ataqueRJ1;
          }else{
            $ataqueRJ1 = rand($ataqueJ1/($defensaJ2+$defensaSumarJ2), $maxAtaque) + $ataqueSumarJ1;
            $vidaJ2 = $vidaJ2 - $ataqueRJ1;
          }

          $response = "⚔🔵 ¡Has atacado a $message! Le has hecho $ataqueRJ1 de daño. ¡Le queda $vidaJ2 de vida!";
          sendMessage($userId, $response, FALSE);

          if($vidaJ2 > 0){

            $maxAtaque =  $ataqueJ2 - (($defensaJ1+$defensaSumarJ1)/2);
            if($maxAtaque <= 1){
            $ataqueRJ2 = rand($ataqueJ2/($defensaJ1+$defensaSumarJ1), $ataqueJ2/2) + $ataqueSumarJ2;
            $vidaJ1 = $vidaJ1 - $ataqueRJ2;
          }else{
            $ataqueRJ2 = rand($ataqueJ2/($defensaJ1+$defensaSumarJ1), $maxAtaque) + $ataqueSumarJ2;
            $vidaJ1 = $vidaJ1 - $ataqueRJ2;
          }

            $response = "⚔🔴 ¡Te ha atacado $message! Te ha quitado $ataqueRJ2 de vida. ¡Te queda $vidaJ1 de vida!";
            sendMessage($userId, $response, FALSE);

          }

        }

        if(!$premiumJ1){
        $partidasJugadas = $fila['peleas_posibles'];
        $partidasJugadas = $partidasJugadas - 1;
        }else{
          $partidasJugadas = $fila['peleas_posibles'];
        }

        if($partidasJugadas == 0){
          $consulta5="UPDATE jugadores SET estado_pelea='1' WHERE idUsuario=$idJ1;";
          mysqli_query($conexion,$consulta5);
        }

        if($vidaJ1 <= 0){

          $expInsertar = rand(5,10);
          $dineroInsertar = rand(5,10);

          $response = "💀 ¡Has salido derrotado contra $nombreJ2! Inténtalo más tarde. Has conseguido $expInsertar de experiencia y $dineroInsertar de dinero.";
          sendDeleteMessage($userId, $messageId, $response, FALSE);

          $expInsertar = $fila['exp'] + $expInsertar;
          $dineroInsertar = $fila['dinero'] + $dineroInsertar;

          if($expInsertar >= (100*$nivelJ1)){
            $nivelInsertar = $nivelJ1 + 1;
            $expInsertar = $expInsertar - (100*$nivelJ1);

            $response = "🆙 ¡Subes de nivel! Ahora eres nivel $nivelInsertar.";
            sendMessage($userId, $response, FALSE);

            $ataqueInsertar = $fila['ataque'] + rand(2,6);
            $defensaInsertar = $fila['defensa'] + rand(2,6);
            $vidaInsertar = $fila['vida'] + rand(2,6);

            $consulta3="UPDATE jugadores SET peleas_posibles=$partidasJugadas, nivel=$nivelInsertar, dinero=$dineroInsertar, `exp`=$expInsertar, ataque=$ataqueInsertar, defensa=$defensaInsertar, vida=$vidaInsertar WHERE idUsuario=$idJ1;";
            mysqli_query($conexion,$consulta3);

          }else{
            $consulta3 = "UPDATE jugadores SET peleas_posibles=$partidasJugadas, dinero=$dineroInsertar, `exp`=$expInsertar WHERE idUsuario=$idJ1;";
            mysqli_query($conexion,$consulta3);
          }
          $consulta4 = "INSERT INTO luchas (jugadorUno, jugadorDos, fecha, victoria, tipo) VALUES('$idJ1','$idJ2',NOW(),'0','competitivo');";
          mysqli_query($conexion,$consulta4);

        }else if($vidaJ2 <= 0){

          $dineroInsertar = rand(10,15);
          $expInsertar = rand(10,15);

          $response = "🏆 ¡Has ganado contra $nombreJ2! Enhorabuena. Has conseguido $expInsertar de experiencia y $dineroInsertar de dinero.";
          sendDeleteMessage($userId, $messageId, $response, FALSE);

          $dineroInsertar = $fila['dinero'] + $dineroInsertar;
          $expInsertar = $fila['exp'] + $expInsertar;

          $muertesInsertar = $fila['muertes'] + 1;

          if($expInsertar >= (100*$nivelJ1)){
            $nivelInsertar = $nivelJ1 + 1;
            $expInsertar = $expInsertar - (100*$nivelJ1);

            $response = "🆙 ¡Subes de nivel! Ahora eres nivel $nivelInsertar.";
            sendMessage($userId, $response, FALSE);

            $ataqueInsertar = $fila['ataque'] + rand(2,6);
            $defensaInsertar = $fila['defensa'] + rand(2,6);
            $vidaInsertar = $fila['vida'] + rand(2,6);

            $consulta3="UPDATE jugadores SET estado='0', muertes=$muertesInsertar, peleas_posibles=$partidasJugadas, nivel=$nivelInsertar, dinero=$dineroInsertar, `exp`=$expInsertar, ataque=$ataqueInsertar, defensa=$defensaInsertar, vida=$vidaInsertar WHERE idUsuario=$idJ1;";
            mysqli_query($conexion,$consulta3);

          }else{
            $consulta3="UPDATE jugadores SET estado='0', muertes=$muertesInsertar, peleas_posibles=$partidasJugadas, dinero=$dineroInsertar, `exp`=$expInsertar WHERE idUsuario=$idJ1;";
            mysqli_query($conexion,$consulta3);
          }

          $consulta4 = "INSERT INTO luchas (jugadorUno, jugadorDos, fecha, victoria, tipo) VALUES('$idJ1','$idJ2',NOW(),'1','competitivo');";
          mysqli_query($conexion,$consulta4);

        }

      }

    }else{
      $response = "⛔ El nombre de jugador que has proporcionado no existe, inténtalo de nuevo cuando lo sepas o utiliza /lucharaleatorio para luchar contra alguien de forma aleatoria.";
      sendMessage($userId, $response, FALSE);

      include 'config/conexion2.php';
      $usuario2=mysqli_real_escape_string($conexion,$userId);
      $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
      mysqli_query($conexion2,$consulta2);
      mysqli_close($conexion2);

      $consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
      mysqli_query($conexion, $consulta);
    }

  }else{
    $response = "⛔ $firstname, ¿te crees que puedes luchar contra ti? No estoy a favor del suicidio.";
    sendMessage($userId, $response, FALSE);

    include 'config/conexion2.php';
    $usuario2=mysqli_real_escape_string($conexion,$userId);
    $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
    mysqli_query($conexion2,$consulta2);
    mysqli_close($conexion2);

    $consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
    mysqli_query($conexion, $consulta);
  }

  include 'config/conexion2.php';
  $usuario2=mysqli_real_escape_string($conexion,$userId);
  $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
  mysqli_query($conexion2,$consulta2);
  mysqli_close($conexion2);

  mysqli_close($conexion);
  exit;

}else if($fila['estado']==5){ // SISTEMA DE LUCHAS AMISTOSAS

  $idJ1 = $fila['idUsuario'];
  $nombreJ1 = $fila['nombre'];
  $razaJ1 = $fila['raza'];
  $nivelJ1 = $fila['nivel'];
  $vidaJ1 = $fila['vida'];
  $vidaGJ1 = $vidaJ1;
  $defensaJ1 = $fila['defensa'];
  $ataqueJ1 = $fila['ataque'];

  if($nombreJ1 != $message){

  $consultaJ2 = "SELECT * FROM jugadores WHERE nombre='$message';";
  $datosJ2 = mysqli_query($conexion,$consultaJ2);

  if(mysqli_num_rows($datosJ2)>0){
    $filaJ2=mysqli_fetch_array($datosJ2,MYSQLI_ASSOC);

    $idJ2 = $filaJ2['idUsuario'];
    $nombreJ2 = $filaJ2['nombre'];
    $razaJ2 = $filaJ2['raza'];
    $nivelJ2 = $filaJ2['nivel'];
    $vidaJ2 = $filaJ2['vida'];
    $vidaGJ2 = $vidaJ2;
    $defensaJ2 = $filaJ2['defensa'];
    $ataqueJ2 = $filaJ2['ataque'];

    $response = "⏳ ¡Empieza el combate contra $nombreJ2! Que gane el mejor jugador.";
    sendMessage($userId, $response, FALSE);

      while($vidaJ1 > 0 && $vidaJ2 > 0){

        $porcentajeAlJ1 = rand(0,100);
        $porcentajeAlJ2 = rand(0,100);

        if($porcentajeAlJ1 >= 0 && $porcentajeAlJ1 <= 20){
          if($razaJ1 == 'informático'){
            $ataqueSumarJ1 = $ataqueJ1/2;
            $response = "🔰🔵 ¡Has obtenido tu poder oculto y durante este turno obtendrás un 50% más de ataque!";
            sendMessage($userId, $response, FALSE);
          }else if($razaJ1 == 'teleco'){
            $vidaSumarJ1 = $vidaGJ1/2;
            $vidaJ1 = $vidaJ1 + $vidaSumarJ1;
            $response = "🔰🔵 ¡Has obtenido tu poder oculto y te ha aumentado la vida! Ahora mismo tienes $vidaJ1.";
            sendMessage($userId, $response, FALSE);
          }else if($razaJ1 == 'intruso'){
            $defensaSumarJ1 = $defensaJ1/2;
            $response = "🔰🔵 ¡Has obtenido tu poder oculto y durante este turno obtendrás un 50% más de defensa!";
            sendMessage($userId, $response, FALSE);
          }
        }else{
          $ataqueSumarJ1 = 0;
          $vidaSumarJ1 = 0;
          $defensaSumarJ1 = 0;
        }

        if($porcentajeAlJ2 >= 0 && $porcentajeAlJ2 <= 20){
          if($razaJ2 == 'informático'){
            $ataqueSumarJ2 = $ataqueJ2/2;
            $response = "🔰🔴 ¡Tu enemigo ha obtenido su poder oculto y durante este turno obtendrá un 50% más de ataque!";
            sendMessage($userId, $response, FALSE);
          }else if($razaJ2 == 'teleco'){
            $vidaSumarJ2 = $vidaGJ2/2;
            $vidaJ2 = $vidaJ2 + $vidaSumarJ2;
            $response = "🔰🔴 ¡Tu enemigo ha obtenido su poder oculto y se le ha aumentado la vida! Ahora mismo tiene $vidaJ2.";
            sendMessage($userId, $response, FALSE);
          }else if($razaJ2 == 'intruso'){
            $defensaSumarJ2 = $defensaJ2/2;
            $response = "🔰🔴 ¡Tu enemigo ha obtenido su poder oculto y durante este turno obtendrá un 50% más de defensa!";
            sendMessage($userId, $response, FALSE);
          }
        }else{
          $ataqueSumarJ2 = 0;
          $vidaSumarJ2 = 0;
          $defensaSumarJ2 = 0;
        }

        $maxAtaque = $ataqueJ1 - (($defensaJ2+$defensaSumarJ2)/2);
        if($maxAtaque <= 1){
          $ataqueRJ1 = rand($ataqueJ1/($defensaJ2+$defensaSumarJ2), $ataqueJ1/2) + $ataqueSumarJ1;
          $vidaJ2 = $vidaJ2 - $ataqueRJ1;
        }else{
          $ataqueRJ1 = rand($ataqueJ1/($defensaJ2+$defensaSumarJ2), $maxAtaque) + $ataqueSumarJ1;
          $vidaJ2 = $vidaJ2 - $ataqueRJ1;
        }

        $response = "⚔🔵 ¡Has atacado a $message! Le has hecho $ataqueRJ1 de daño. ¡Le queda $vidaJ2 de vida!";
        sendMessage($userId, $response, FALSE);

        if($vidaJ2 > 0){

          $maxAtaque =  $ataqueJ2 - (($defensaJ1+$defensaSumarJ1)/2);
          if($maxAtaque <= 1){
          $ataqueRJ2 = rand($ataqueJ2/($defensaJ1+$defensaSumarJ1), $ataqueJ2/2) + $ataqueSumarJ2;
          $vidaJ1 = $vidaJ1 - $ataqueRJ2;
        }else{
          $ataqueRJ2 = rand($ataqueJ2/($defensaJ1+$defensaSumarJ1), $maxAtaque) + $ataqueSumarJ2;
          $vidaJ1 = $vidaJ1 - $ataqueRJ2;
        }

          $response = "⚔🔴 ¡Te ha atacado $message! Te ha quitado $ataqueRJ2 de vida. ¡Te queda $vidaJ1 de vida!";
          sendMessage($userId, $response, FALSE);

        }

      }

      if($vidaJ1 <= 0){

        $response = "💀 ¡Has salido derrotado contra $message! Inténtalo más tarde.";
        sendMessage($userId, $response, FALSE);

        $consulta = "UPDATE jugadores SET estado=0 WHERE idUsuario=$idJ1;";
        mysqli_query($conexion,$consulta);

        $consulta4 = "INSERT INTO luchas (jugadorUno, jugadorDos, fecha, victoria, tipo) VALUES('$idJ1','$idJ2',NOW(),'0','amistoso');";
        mysqli_query($conexion,$consulta4);

      }else if($vidaJ2 <= 0){

        $response = "🏆 ¡Has ganado contra $message! Enhorabuena.";
        sendMessage($userId, $response, FALSE);

        $consulta3="UPDATE jugadores SET estado='0' WHERE idUsuario=$idJ1;";
        mysqli_query($conexion,$consulta3);

        $consulta4 = "INSERT INTO luchas (jugadorUno, jugadorDos, fecha, victoria, tipo) VALUES('$idJ1','$idJ2',NOW(),'1','amistoso');";
        mysqli_query($conexion,$consulta4);

      }

  }else{
    $response = "⛔ El nombre de jugador que has proporcionado no existe, inténtalo de nuevo cuando lo sepas o utiliza /lucharaleatorio para luchar contra alguien de forma aleatoria.";
    sendMessage($userId, $response, FALSE);

    include 'config/conexion2.php';
    $usuario2=mysqli_real_escape_string($conexion,$userId);
    $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
    mysqli_query($conexion2,$consulta2);
    mysqli_close($conexion2);

    $consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
    mysqli_query($conexion, $consulta);
  }

  }else{
  $response = "⛔ $firstname, ¿te crees que puedes luchar contra ti? No estoy a favor del suicidio.";
  sendMessage($userId, $response, FALSE);

  include 'config/conexion2.php';
  $usuario2=mysqli_real_escape_string($conexion,$userId);
  $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
  mysqli_query($conexion2,$consulta2);
  mysqli_close($conexion2);

  $consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
  mysqli_query($conexion, $consulta);
  }

  include 'config/conexion2.php';
  $usuario2=mysqli_real_escape_string($conexion,$userId);
  $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
  mysqli_query($conexion2,$consulta2);
  mysqli_close($conexion2);

  mysqli_close($conexion);
  exit;
*/
}else if($fila['estado']==6){

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

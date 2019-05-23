<?php

include_once 'funciones.php';
include_once 'config/variables.php';

// CON EL EXPLODE TOMAMOS EL PRIMER VALOR DEL MENSAJE ASÍ VEMOS SI ESTÁ USANDO EL COMANDO O NO.
$arr = explode(' ',trim($message));
$command = $arr[0];

$message = substr(strstr($message," "), 1);

if(is_numeric($command)){
  include 'config/conexion.php';

  $usuario=mysqli_real_escape_string($conexion,$userId);
  $consulta="SELECT * FROM jugadores WHERE idUsuario='$usuario';";
  $datos=mysqli_query($conexion,$consulta);

  $fila=mysqli_fetch_array($datos,MYSQLI_ASSOC);
  if(mysqli_num_rows($datos) > 0){
    $verificacion = $fila['verificacion'];
    $peleasPosibles = $fila['peleas_posibles'];
    $estado = $fila['estado'];

    if(($peleasPosibles == 15) && $estado == 8){
      if($command == $verificacion){
        $usuario=mysqli_real_escape_string($conexion,$userId);
        $consulta="UPDATE jugadores SET estado='9', verificacion=0 WHERE idUsuario='$usuario';";
        mysqli_query($conexion,$consulta);

        $response = "✅ ¡De acuerdo, $firstname! Te creeré, deberás utilizar de nuevo /luchar nombreJugador. Disculpa las molestias.";
        sendMessage($userId, $response, FALSE);
      }else{
        $usuario=mysqli_real_escape_string($conexion,$userId);
        $consulta="UPDATE jugadores SET estado='0', estado_pelea='1', peleas_posibles='0', verificacion=0 WHERE idUsuario='$usuario';";
        mysqli_query($conexion,$consulta);

        $response = "⛔ ¡Lo siento, $firstname! No has conseguido pasar la prueba anti-bot. ¡Te quedas sin luchas hasta la siguiente renovación.";
        sendMessage($userId, $response, FALSE);
      }
    }
  }
  mysqli_close($conexion);
  exit;
}

  include 'codigo.php';

switch($command){

  // SISTEMA DE REPORTES A USUARIOS.

  case '/reportar': case '/reportar@FightETSIIT_Bot':

    $jugadorReportado = $arr[1];

    if(!(empty($jugadorReportado))){
      include 'config/conexion.php';
      $jugadorReportado=mysqli_real_escape_string($conexion,$arr[1]);
      $consulta="SELECT * FROM jugadores WHERE nombre='$jugadorReportado';";
      $datos=mysqli_query($conexion,$consulta);

      if(mysqli_num_rows($datos)>0){
        $fila=mysqli_fetch_array($datos,MYSQLI_ASSOC);

        $idReportado = $fila['idUsuario'];
        $mensajeReporte = substr(strstr($message," "), 1);

        if($userId != $idReportado){

          if(!(empty($mensajeReporte))){

            $consulta2="INSERT INTO reportes (jugadorReportante, jugadorReportado, mensaje, fecha) VALUES('$userId','$idReportado','$mensajeReporte',NOW());";
            mysqli_query($conexion, $consulta2);

            $response = "✅ $firstname el reporte ha sido enviado con éxito, muchísimas gracias por colaborar.";
            sendDeleteMessage($userId, $messageId, $response, FALSE);

          }else{
            revisarComandos($firstname, $userId, $messageId);
          }

        }else{
          $response = "⛔ El nombre de jugador es el tuyo, ¿cómo te vas a reportar a ti mismo?";
          sendDeleteMessage($userId, $messageId, $response, FALSE);
          exit;
        }
      }else{
        $response = "⛔ El nombre de jugador que has proporcionado no existe, inténtalo de nuevo cuando lo sepas.";
        sendDeleteMessage($userId, $messageId, $response, FALSE);
        exit;
      }
    }else{
      revisarComandos($firstname, $userId, $messageId);
    }

    mysqli_close($conexion);
    exit;
  break;

  // SISTEM PARA RENOVAR LUCHAS.
  case '/renovar': case '/renovar@FightETSIIT_Bot':
  include 'config/conexion.php';
  if($userId == '444137662'){

    $consulta="UPDATE jugadores SET estado_pelea=0, peleas_posibles=30;";
    mysqli_query($conexion, $consulta);

    $response = "✅ ¡Listo, ya se ha renovado las luchas a todos los usuarios!";
    sendMessage($userId, $response, FALSE);

  }else{
    $response = "⛔ ¿A dónde ibas $firstname? ¿Te crees que puedes hacer eso?";
    sendDeleteMessage($userId, $messageId, $response, FALSE);
  }

    mysqli_close($conexion);
    exit;

  break;

  // SISTEMA PARA ENVIAR MENSAJES A TODOS LOS USUARIOS DE FIGHT ETSIIT.
  case '/enviarmensaje': case '/enviarmensaje@FightETSIIT_Bot':
  include 'config/conexion.php';
    if($userId == '444137662'){

    $usuario=mysqli_real_escape_string($conexion,$userId);
    $consulta="SELECT * FROM `jugadores` WHERE idUsuario='$usuario';";
    $datos=mysqli_query($conexion,$consulta);

    if(mysqli_num_rows($datos) > 0){

      $consulta="UPDATE jugadores SET estado='7' WHERE idUsuario='$userId';";
      mysqli_query($conexion, $consulta);

      $response = "📨 Ahora envíame el mensaje que desees enviar a todos los usuarios de Fight ETSIIT.";
      sendDeleteMessage($userId, $messageId, $response, FALSE);

    }

  }else{
    $response = "⛔ ¿A dónde ibas $firstname? ¿Te crees que puedes hacer eso?";
    sendDeleteMessage($userId, $messageId, $response, FALSE);
  }

    mysqli_close($conexion);
    exit;

  break;

  // COMANDO PARA CUANDO UN USUARIO EMPIECE DE NUEVO EN EL BOT Y TENGA UN POCO MÁS DE INFORMACIÓN.
  case '/start': case '/start@FightETSIIT_Bot':

    $response = "🎉 Bienvenido a Fight ETSIIT, un juego creado por @IgnasiCR y @ManuJNR.\n\nPara poder crear tu jugador debes usar el comando /registrar [nombreUsuario] raza, podrás elegir entre ser Informático, Teleco o Intruso.\n\nSi colocas una '/' en el chat te saldrán todas las opciones posibles para el juego. Si tienes más dudas puedes utilizar el comando /ayuda o /comandos.";
    sendDeleteMessage($userId, $messageId, $response, FALSE);

    exit;
  break;

  // COMANDO PARA QUE EL USUARIO PUEDA OBTENER MÁS INFORMACIÓN SOBRE EL JUEGO.
  case '/ayuda': case '/ayuda@FightETSIIT_Bot':

    $response .= "❓ <b>¿Cómo funciona el sistema de luchas?</b>\nCada jugador tiene unas características (defensa, ataque y vida), y de forma aleatoria se calcula el ataque final que hará un jugador contra el otro teniendo en cuenta la defensa del contrincante. El sistema de lucha es totalmente automática por lo que el jugador atacará solo. Al final de la lucha se indicará que jugador ha sido el ganador, en caso de perder tan solo obtendrás experiencia, pero en caso de ganar también conseguirá oro.\n";
    $response .="\n❓ <b>¿Qué tipo de luchas existen?</b>\nExisten dos tipos de luchas, las competitivas y las amistosas. En las competitivas ganes o pierdas obtendrás experiencia y oro aunque pero en esta última será menor cantidad. En cambio en las amistosas no ganarás nada, ni experiencia ni dinero. Además hay que destacar que en las competitivas solo podrás luchar con gente -+3 niveles que tu, en las amistosas con quién quieras.\n";
    $response .="\n❓ <b>¿Cuántas veces puedo jugar?</b>\nComo máximo se puede jugar 30 luchas cada media hora. Tanto a las en punto como a las y media se regeneran todas las luchas posibles pero las que te quedaron anteriormente no se suman a estas. Aprovecha las 30 de cada media hora para poder conseguir estar en el ranking.\n";
    $response .="\n❓ <b>¿Cómo funciona el sistema de ranking?</b>\nEl ranking está ordenado por los asesinatos. Los jugadores con mayor asesinatos se encontrarán en este ranking, por lo tanto... ¡ponte a luchar para ser el primero!\n";
    $response .="\n❓ <b>¿Cuántos objetos puedo comprar en la tienda?</b>\nNo hay limite de objetos que puedas comprar en la tienda. Siempre y cuando tengas el dinero para hacerte con alguno de ellos podrás hacerlo.\n";
    $response .="\n❓ <b>¿Cuántas razas existen en Fight ETSIIT?</b>\nExisten tres tipos de razas: informático, teleco e intruso. Cada una de ellas tiene una ventaja importante en el combate.\n\n<b>Informático</b>: Tienen un 20% de conseguir un 50% más de ataque en cada turno.\n<b>Teleco</b>: Tienen un 20% de poder aumentar/curarse la vida en un 50% respecto a su base en cada turno.\n<b>Intruso</b>: Tienen un 20% de conseguir un 50% más de defensa en cada turno.\n";
    $response .="\n❓ <b>¿Puedo cambiarme de raza después de haber elegido?</b>\nSí, siempre y cuándo tengas 10.000 de dinero podrás cambiarte de raza utilizando el comando /cambiarseraza. A partir de ese momento en las luchas podrás obtener el poder oculto de la raza a las que te has cambiado y no se te borrarán los objetos comprados anteriormente de la raza a la que pertenecías.\n";

    sendDeleteMessage($userId, $messageId, $response, FALSE);

    exit;
  break;

  case '/comandos': case '/comandos@FightETSIIT_Bot':

    $response .="<b>Comandos con parámetros</b>\n\n";
    $response .="▪ /registrar [nombreUsuario] raza -> En caso de no elegir nombre de usuario se intentará crear la cuenta con el @ que tengas en tu cuenta de Telegram, pero también puedes elegir otro nombre (sin espacios) escribiéndolo antes de la raza. La raza siempre es obligatorio, puedes elegir entre: Informático, Teleco o Intruso. Por ejemplo: /registrar informático o /registrar ignasi_cr teleco.\n";
    $response .="\n▪ /personaje [nombreJugador] -> En caso de no poner un nombre de un jugador verás tus propias estadísticas, en caso contrario verás las del jugador del nombre escrito.\n";
    $response .="\n▪ /comprar identificador -> El identificador del objeto a comprar lo puedes ver en /tienda\n";
    $response .="\n▪ /ranking [nombreJugador/numero] -> En caso de no poner un nombre de un jugador verás tu posición en el ranking general, en caso contrario verás la posición del jugador del nombre escrito o quién se encuentra en la posición indicada.\n";
    $response .="\n▪ /rankingraza raza -> Podrás ver el TOP10 de jugadores con la raza indicada. Las razas son: informático, teleco o intruso.\n";
    $response .="\n▪ /luchar nombreJugador -> El nombre del jugador será contra el que quieres luchar de forma competitiva.\n";
    $response .="\n▪ /lucharamistoso nombreJugador -> El nombre del jugador será contra el que quieres luchar de forma amistosa.\n";
    $response .="\n▪ /reportar nombreJugador mensajeReporte -> Todo reporte debe llevar un nombre de un jugador al que deseas reportar y un mensaje. Todo falso reporte que se realice obtendrá una sanción.\n";

    sendDeleteMessage($userId, $messageId, $response, FALSE);

    exit;
  break;

  case '/creditos': case '/creditos@FightETSIIT_Bot':

    $response .= "📢 <b>Personas que trabajan en el proyecto:</b>\n";
    $response .="\n🖥 <b>Programadores</b>\n";
    $response .="@IgnasiCR\n@ManuJNR\n";
    $response .="\n🎮 <b>Testers/Colaboradores</b>\n";
    $response .="@DarkAsdfgh\n@laurator\n@Sheisenn\n@Nekire\n";
    $response .="\n🤺 Versión 1.1 - Fight ETSIIT\n";

    sendDeleteMessage($userId, $messageId, $response, FALSE);

    exit;
  break;

  case '/donaciones': case '/donaciones@FightETSIIT_Bot':

    $response .="\n💵 Si te ha gustado el juego y quieres aportar a que mejore y/o pasemos el formato a aplicación móvil en un futuro, puedes dejar tu granito de arena en la siguiente cuenta:\n";
    $response .="\n<a href='paypal.me/IgnasiCR17'>PayPal - IgnasiCR17</a>";

    sendDeleteMessage($userId, $messageId, $response, TRUE);

    exit;
  break;

    // SISTEM PARA REGISTRAR A UN USUARIO EN EL SISTEMA.
    case '/registrar': case '/registrar@FightETSIIT_Bot':
    include 'config/conexion.php';

    $usuario=mysqli_real_escape_string($conexion,$userId);
    $consulta="SELECT * FROM `jugadores` WHERE idUsuario='$usuario';";
    $datos=mysqli_query($conexion,$consulta);

    if(mysqli_num_rows($datos)==0){

      if(empty($arr[1])){
        revisarComandos($firstname, $userId, $messageId);
      }else if(empty($arr[2])){
        if(comprobarRaza($arr[1])){

          $consulta="SELECT * FROM jugadores WHERE nombre='$firstname';";
          $datos=mysqli_query($conexion,$consulta);

          if(mysqli_num_rows($datos)>0){

            $response = "⛔ $firstname el nombre que has querido utilizar ya está en uso, tendrás que elegir otro, puedes utilizar /registrar nombreJugador raza.";
            sendMessage($userId, $response, FALSE);
            exit;
          }else{
            include 'config/conexion2.php';

            $consulta="INSERT INTO `jugadores` (idUsuario, nombre, raza) VALUES ('$userId', '$firstname','$arr[1]');";
            mysqli_query($conexion, $consulta);
            $consulta2="INSERT INTO `jugadores` (idUsuario, nombre) VALUES ('$userId', '$firstname');";
            mysqli_query($conexion2, $consulta2);

            $response = "🆕 $firstname hemos registrado tu cuenta.\n\nTu nombre de jugador será el siguiente: $firstname. Ya puedes empezar a jugar, si tienes dudas puedes utilizar /ayuda o /comandos.";
            sendMessage($userId, $response, FALSE);
            mysqli_close($conexion);
            mysqli_close($conexion2);
          }

        }else{
          revisarComandos($firstname, $userId, $messageId);
        }
      }else{
        if(comprobarRaza($arr[2])){

          $consulta="SELECT * FROM jugadores WHERE nombre='$arr[1]';";
          $datos=mysqli_query($conexion,$consulta);

          if(mysqli_num_rows($datos)>0){

            $response = "⛔ $firstname el nombre que has querido utilizar ya está en uso, tendrás que elegir otro, puedes utilizar /registrar nombreJugador raza.";
            sendMessage($userId, $response, FALSE);
            exit;
          }else{
            include 'config/conexion2.php';

            $consulta="INSERT INTO `jugadores` (idUsuario, nombre, raza) VALUES ('$userId', '$arr[1]','$arr[2]');";
            mysqli_query($conexion, $consulta);
            $consulta2="INSERT INTO `jugadores` (idUsuario, nombre) VALUES ('$userId', '$firstname');";
            mysqli_query($conexion2, $consulta2);

            $response = "🆕 $firstname hemos registrado tu cuenta.\n\nTu nombre de jugador será el siguiente: $arr[1]. Ya puedes empezar a jugar, si tienes dudas puedes utilizar /ayuda o /comandos.";
            sendMessage($userId, $response, FALSE);
            mysqli_close($conexion);
            mysqli_close($conexion2);
          }

        }else{
          revisarComandos($firstname, $userId, $messageId);
        }
      }

    }else{
      $response = "⛔ ¡$firstname tu ya tienes un personaje registrado a tu cuenta de Telegram! Puedes utilizar el comando /personaje para más información.";
      sendDeleteMessage($userId, $messageId, $response, FALSE);
      exit;
    }

      mysqli_close($conexion);
      exit;
    break;

  // COMANDO PARA QUE EL USUARIO PUEDA CONOCER LAS ESTADÍSTICAS DE TU/UN JUGADOR.
  case '/personaje': case '/personaje@FightETSIIT_Bot':

  include 'config/conexion.php';

  if(empty($message)){
    $usuario=mysqli_real_escape_string($conexion,$userId);
    $consulta="SELECT * FROM `jugadores` WHERE idUsuario='$usuario';";
    $mi = TRUE;
  }else{
    $usuario=mysqli_real_escape_string($conexion,$message);
    $consulta="SELECT * FROM `jugadores` WHERE nombre='$usuario';";
    $mi = FALSE;
  }

    $datos=mysqli_query($conexion,$consulta);

    if(mysqli_num_rows($datos) > 0){
      $fila=mysqli_fetch_array($datos,MYSQLI_ASSOC);

      $nombre = $fila['nombre'];
      $raza = $fila['raza'];
      $nivel = $fila['nivel'];
      $exp = $fila['exp'];
      $dinero = $fila['dinero'];
      $muertes = $fila['muertes'];
      $ataque = $fila['ataque'];
      $defensa = $fila['defensa'];
      $vida = $fila['vida'];

      $expN = $nivel*100;

      switch($raza){
        case 'informático': $icono = 🖥; break;
        case 'teleco': $icono = 📡; break;
        case 'intruso': $icono = 🛸👽; break;
      }

      if($mi){
        $response = "📊 <b>Estadísticas Personaje</b>\n\n👤 Nombre: $nombre\n$icono Raza: $raza\n🚩 Nivel: $nivel\n🎮 Experiencia: $exp/$expN\n\n💰 Dinero: $dinero\n💀 Asesinatos: $muertes\n\n⚔ Ataque: $ataque\n🛡 Defensa: $defensa\n❤ Vida: $vida";
        sendDeleteMessage($userId, $messageId, $response, FALSE);
      }else{
        $response = "📊 <b>Estadísticas Personaje</b>\n\n👤 Nombre: $nombre\n$icono Raza: $raza\n🚩 Nivel: $nivel\n\n💀 Asesinatos: $muertes\n\n⚔ Ataque: $ataque\n🛡 Defensa: $defensa\n❤ Vida: $vida";
        sendDeleteMessage($userId, $messageId, $response, FALSE);
      }

    }else{
      $response = "⛔ $firstname no existe ningún jugador con ese nombre, intentálo más tarde cuándo lo sepas.";
      sendDeleteMessage($userId, $messageId, $response, FALSE);
    }

    mysqli_close($conexion);
    exit;

  break;

  // COMANDO PARA QUE EL USUARIO PUEDA VER AL TIENDA OFICIAL DE OBJETOS.
  case '/tienda': case '/tienda@FightETSIIT_Bot':
  include 'config/conexion.php';
    $usuario=mysqli_real_escape_string($conexion,$userId);
    $consulta="SELECT * FROM `jugadores` WHERE idUsuario='$usuario';";
    $datos=mysqli_query($conexion,$consulta);

    if(mysqli_num_rows($datos) > 0){
      $fila=mysqli_fetch_array($datos,MYSQLI_ASSOC);
      $razaJugador = $fila['raza'];

      $consulta="SELECT * FROM `objetos` WHERE raza='$razaJugador' ORDER BY `idObjeto` ASC;";
      $datos=mysqli_query($conexion,$consulta);

      $response .="🏪 <b>Tienda Oficial de la ETSIIT</b>\n";

      while($fila=mysqli_fetch_array($datos,MYSQLI_ASSOC)){

        $idObjeto = $fila['idObjeto'];
        $nombreObjeto = $fila['nombre'];
        $dinero = $fila['dinero'];
        $descripcion = $fila['descripcion'];
        $ataque = $fila['ataque'];
        $defensa = $fila['defensa'];
        $vida = $fila['vida'];

        $response .= "\n🛒 <b>Identificador: $idObjeto</b>\nNombre: $nombreObjeto\nDinero: $dinero\nDescripcion: $descripcion\n";
        /*if($vida > 0){
          $response .="Vida: $vida\n";
        }
        if($defensa > 0){
          $response .="Defensa: $defensa\n";
        }
        if($ataque > 0){
          $response .="Ataque: $ataque\n";
        }*/
      }

      sendDeleteMessage($userId, $messageId, $response, FALSE);

    }else{
      $response = "⛔ $firstname no tienes un personaje registrado a tu cuenta por lo tanto no puedes hacer uso de la tienda, para ello utiliza /registrar [nombreJugador] raza.";
      sendDeleteMessage($userId, $messageId, $response, FALSE);
    }

    mysqli_close($conexion);
    exit;

  break;

  // SISTEMA PARA QUE EL USUARIO PUEDA REALIZAR COMPRAS DE OBJETOS.
  case '/comprar': case '/comprar@FightETSIIT_Bot':

    if(empty($message)){
      $response = "⛔ $firstname debes indicarme un identificador cualesquiera de un objeto de la tienda -> /comprar identificador";
      sendDeleteMessage($userId, $messageId, $response, FALSE);
      exit;
    }

    include 'config/conexion.php';

    $usuario=mysqli_real_escape_string($conexion,$userId);
    $consulta="SELECT * FROM `jugadores` WHERE idUsuario='$usuario';";
    $datos=mysqli_query($conexion,$consulta);

    if(mysqli_num_rows($datos) > 0){

      /*$consulta="UPDATE jugadores SET estado='3' WHERE idUsuario='$userId';";
      mysqli_query($conexion, $consulta);*/

      if(!(is_numeric($message))){
        $response = "⛔ $firstname ¿qué te pensabas que somos tontos aquí o qué? Eso no es ningún identificador.";
        sendDeleteMessage($userId, $messageId, $response, FALSE);
        /*$consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
        mysqli_query($conexion, $consulta);*/
        mysqli_close($conexion);
        exit;
      }
      $fila=mysqli_fetch_array($datos,MYSQLI_ASSOC);

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
            /*$consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
            mysqli_query($conexion, $consulta);*/
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

            $consulta="UPDATE jugadores SET ataque=$ataqueSumar, vida='$vidaSumar', defensa='$defensaSumar', dinero='$dineroRestar' WHERE idUsuario='$userId';";
            mysqli_query($conexion, $consulta);

            $consulta2="INSERT INTO compras (idUsuario, idObjeto, fecha) VALUES('$userId','$message',NOW());";
            mysqli_query($conexion, $consulta2);

            $response = "💳 Has comprado el objeto $fila[nombre]. Se te han descontado $dinero por la compra, ahora tienes $dineroRestar.";
            sendMessage($userId, $response, FALSE);

          }else{

            $response = "⛔ No tienes suficiente dinero para hacer la compra del objeto. Inténtalo de nuevo en cuánto consigas el dinero necesario.";
            sendMessage($userId, $response, FALSE);

            /*$consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
            mysqli_query($conexion, $consulta);*/

          }

        }else{

          $response = "⛔ El identificador que has seleccionado es incorrecto, si quieres volver a comprar algo de verdad utiliza de nuevo /comprar id.";
          sendMessage($userId, $response, FALSE);

          /*$consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
          mysqli_query($conexion, $consulta);*/

        }

      }else{

        $response = "⛔ Lo siento pero no hay ningún objeto a la venta en la tienda, intentálo en otro momento.";
        sendMessage($userId, $response, FALSE);

        /*$consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
        mysqli_query($conexion, $consulta);*/

      }

    }else{
      $response = "⛔ $firstname no tienes un personaje registrado a tu cuenta por lo tanto no puedes comprar ningún objeto de la tienda, para ello utiliza /registrar [nombreJugador] raza.";
      sendDeleteMessage($userId, $messageId, $response, FALSE);
    }

    mysqli_close($conexion);
    exit;

  break;

  // SISTEMA PARA QUE EL USUARIO PUEDA CAMBIARSE DE RAZA.

  case '/cambiarseraza': case '/cambiarseraza@FightETSIIT_Bot':
  include 'config/conexion.php';
    $usuario=mysqli_real_escape_string($conexion,$userId);
    $consulta="SELECT * FROM `jugadores` WHERE idUsuario='$usuario';";
    $datos=mysqli_query($conexion,$consulta);

    if(mysqli_num_rows($datos) > 0){

      $fila=mysqli_fetch_array($datos,MYSQLI_ASSOC);
      $dineroJ1 = $fila['dinero'];

      if($dineroJ1 < 10000){
        $response = "⛔ $firstname no dispones del dinero suficiente para realizar el cambio de raza. Intentálo de nuevo en cuánto lo consigas.";
        sendMessage($userId, $response, FALSE);

        mysqli_close($conexion);
        exit;
      }

      $consulta="UPDATE jugadores SET estado='6' WHERE idUsuario='$userId';";
      mysqli_query($conexion, $consulta);

      $response = "♻ ¡Recuerda que el cambiarse de raza cuesta 10.000 de dinero! Este proceso lo podrás hacer siempre que quieras, pero si no estás seguro de hacerlo di 'no'. En caso contrario indica a que raza quieres cambiarte: 'informático', 'teleco' o 'intruso'.";
      sendDeleteMessage($userId, $messageId, $response, FALSE);

    }else{
      $response = "⛔ $firstname no tienes un personaje registrado a tu cuenta por lo tanto no puedes comprar ningún objeto de la tienda, para ello utiliza /registrar [nombreJugador] raza.";
      sendDeleteMessage($userId, $messageId, $response, FALSE);
    }

    mysqli_close($conexion);
    exit;
  break;

  // COMANDO PARA MOSTRAR EL RANKING PROPIO DEL JUGADOR.
  case '/ranking': case '/miranking@FightETSIIT_Bot':
    include 'config/conexion.php';

    $consulta2 = "SELECT COUNT(*) as total FROM jugadores;";
    $datos2 = mysqli_query($conexion, $consulta2);
    $fila=mysqli_fetch_array($datos2,MYSQLI_ASSOC);
    $cantidadUsuarios = $fila['total'];

    if(empty($message)){
      existeUsuario($userId, $firstname, $userId, $messageId);
      $comprobar = $userId;
    }else if((is_numeric($message))){
      if(($message > 0 && $message <= $cantidadUsuarios)){
      $comprobar = $message;
      }else{
        $response = "⛔ $firstname debes indicar un número válido, inténtalo más tarde.";
        sendDeleteMessage($userId, $messageId, $response, FALSE);
        mysqli_close($conexion);
        exit;
      }
    }else{
      existeUsuario($message, $firstname, $userId, $messageId);
      $comprobar = $message;
    }

      $consulta = "SELECT * FROM jugadores ORDER BY muertes DESC;";
      $datos=mysqli_query($conexion,$consulta);
      $contador = 1;
      $salida = true;

      $response .="<b>Posición en el Ranking General</b>\n";

      while(($fila=mysqli_fetch_array($datos,MYSQLI_ASSOC)) && $salida){

        $idUsuario = $fila['idUsuario'];
        $nombreUsuario = $fila['nombre'];

        if(($idUsuario == $comprobar) || ($nombreUsuario == $comprobar) || ($contador == $comprobar)){

        $nombreUsuario = $fila['nombre'];
        $raza = $fila['raza'];
        $muertes = $fila['muertes'];
        $nivel = $fila['nivel'];

        switch($contador){
          case '1': $icono = 🥇; break;
          case '2': $icono = 🥈; break;
          case '3': $icono = 🥉; break;
          case '4': $icono = 🏅; break;
          case '5': $icono = 🏅; break;
          default: $icono = "🎗"; break;
        }

        switch($raza){
          case 'informático': $iconoR = 🖥; break;
          case 'teleco': $iconoR = 📡; break;
          case 'intruso': $iconoR = 🛸👽; break;
        }

        $response .= "\n$icono <b>Posicion $contador/$cantidadUsuarios:</b>\n\n👤 Nombre: $nombreUsuario\n$iconoR Raza: $raza\n🚩 Nivel: $nivel\n💀 Asesinatos: $muertes\n";
        sendDeleteMessage($userId, $messageId, $response, FALSE);
        $salida = false;

        }

        $contador++;
      }

    mysqli_close($conexion);
    exit;

  break;

  // COMANDO PARA MOSTRAR EL RANKING GENERAL.
  case '/rankinggeneral': case '/rankinggeneral@FightETSIIT_Bot':
  include 'config/conexion.php';
    $consulta = "SELECT * FROM jugadores ORDER BY muertes DESC LIMIT 10;";
    $datos=mysqli_query($conexion,$consulta);
    $contador = 1;

    $response .="📉 <b>Ranking General</b>\n";

    while($fila=mysqli_fetch_array($datos,MYSQLI_ASSOC)){

      $nombreUsuario = $fila['nombre'];
      $raza = $fila['raza'];
      $muertes = $fila['muertes'];
      $nivel = $fila['nivel'];

      switch($contador){
        case '1': $icono = 🥇; break;
        case '2': $icono = 🥈; break;
        case '3': $icono = 🥉; break;
        case '4': $icono = 🏅; break;
        case '5': $icono = 🏅; break;
        default: $icono = "🎗"; break;
      }

      switch($raza){
        case 'informático': $iconoR = 🖥; break;
        case 'teleco': $iconoR = 📡; break;
        case 'intruso': $iconoR = 🛸👽; break;
      }

      $response .= "\n$icono <b>Posicion $contador:</b>\n\n👤 Nombre: $nombreUsuario\n$iconoR Raza: $raza\n🚩 Nivel: $nivel\n💀 Asesinatos: $muertes\n";
      $contador++;
    }

    sendDeleteMessage($userId, $messageId, $response, FALSE);
    mysqli_close($conexion);
    exit;

  break;

  // COMANDO PARA MOSTRAR EL RANKING DE ALGUNA RAZA EN CONCRETO.
  case '/rankingraza': case '/rankingraza@FightETSIIT_Bot':

    if(empty($message)){
      $response = "⛔ $firstname debes indicarme una raza cualesquiera -> /rankingraza raza";
      sendDeleteMessage($userId, $messageId, $response, FALSE);
      exit;
    }else{
      if($message == 'Informático' || $message == 'Informatico' || $message == 'informático' || $message == 'informatico'){
        $raza = "informático";
      }else if($message == 'Teleco' || $message == 'teleco'){
        $raza = "teleco";
      }else if($message == 'Intruso' || $message == 'intruso'){
        $raza = "intruso";
      }else{
        $response = "⁉ Lo siento, pero no entiendo lo que quieres decir, inténtalo de nuevo más tarde.";
        sendMessage($userId, $response, FALSE);
        exit;
      }
    }

    include 'config/conexion.php';
    $consulta = "SELECT * FROM jugadores WHERE raza='$raza' ORDER BY muertes DESC LIMIT 10;";
    $datos=mysqli_query($conexion,$consulta);
    $contador = 1;

    if(mysqli_num_rows($datos) > 0){
      $response .="📉 <b>Ranking $raza</b>\n";
    while($fila=mysqli_fetch_array($datos,MYSQLI_ASSOC)){

      $nombreUsuario = $fila['nombre'];
      $muertes = $fila['muertes'];
      $nivel = $fila['nivel'];

      switch($contador){
        case '1': $icono = 🥇; break;
        case '2': $icono = 🥈; break;
        case '3': $icono = 🥉; break;
        case '4': $icono = 🏅; break;
        case '5': $icono = 🏅; break;
        default: $icono = "🎗"; break;
      }

      $response .= "\n$icono <b>Posicion $contador:</b>\n\n👤 Nombre: $nombreUsuario\n🚩 Nivel: $nivel\n💀 Asesinatos: $muertes\n";
      $contador++;
    }
    }else{
      $response = "⛔ No existe ningún informático actualmente en el sistema.";
    }

    sendDeleteMessage($userId, $messageId, $response, FALSE);
    mysqli_close($conexion);
    exit;

  break;

  // COMANDO PARA CONOCER LAS ÚLTIMAS 5 LUCHAS QUE HAS REALIZADO.
  case '/ultimasluchas': case '/ultimasluchas@FightETSIIT_Bot':
  include 'config/conexion.php';
    $consulta="SELECT * FROM luchas WHERE jugadorUno=$userId ORDER BY idLucha DESC LIMIT 5;";
    $datos=mysqli_query($conexion,$consulta);

    if(mysqli_num_rows($datos) > 0){
      $response .="⚔ <b>Últimas Luchas</b>\n";
    while($fila=mysqli_fetch_array($datos,MYSQLI_ASSOC)){

      $jugadorUno = $fila['jugadorUno'];
      $jugadorDos = $fila['jugadorDos'];
      $tipo = $fila['tipo'];
      $victoria = $fila['victoria'];

      $consulta2 = "SELECT * FROM jugadores WHERE idUsuario=$jugadorUno;";
      $datos2=mysqli_query($conexion,$consulta2);
      $fila2=mysqli_fetch_array($datos2,MYSQLI_ASSOC);

      $consulta3 = "SELECT * FROM jugadores WHERE idUsuario=$jugadorDos;";
      $datos3=mysqli_query($conexion,$consulta3);
      $fila3=mysqli_fetch_array($datos3,MYSQLI_ASSOC);

      $response .= "\n$fila2[nombre] Vs. $fila3[nombre] - $tipo ";
      if($victoria){
        $response .="🏆";
      }else{
        $response .="💀";
      }
    }
    sendDeleteMessage($userId, $messageId, $response, FALSE);
  }else{
    $response = "⛔ No has luchado ninguna vez, por lo tanto no tienes historial de luchas.";
    sendDeleteMessage($userId, $messageId, $response, FALSE);
  }


    mysqli_close($conexion);
    exit;
  break;

  // SISTEMA PARA LUCHAR DE FORMA COMPETITIVA CONTRA UN JUGADOR EN CONCRETO.
  case '/luchar': case '/luchar@FightETSIIT_Bot':

  if(empty($message)){
    $response = "⛔ $firstname debes indicarme un nombre cualesquiera de un jugador -> /luchar nombreJugador";
    sendDeleteMessage($userId, $messageId, $response, FALSE);
    exit;
  }

  include 'config/conexion2.php';

  $usuario=mysqli_real_escape_string($conexion2,$userId);
  $consulta2="SELECT * FROM jugadores WHERE idUsuario='$usuario';";
  $datos2=mysqli_query($conexion2,$consulta2);
  $fila2=mysqli_fetch_array($datos2,MYSQLI_ASSOC);

  if($fila2['estado']=='1'){
    $response = "⛔ $firstname debes esperar a terminar el combate que estás realizando actualmente.";
    sendMessage($userId, $response, FALSE);
    mysqli_close($conexion2);
    exit;
  }else{
    $consulta3="UPDATE jugadores SET estado='1' WHERE idUsuario='$usuario';";
    mysqli_query($conexion2, $consulta3);
    mysqli_close($conexion2);
  }

  include 'config/conexion.php';

  $usuario=mysqli_real_escape_string($conexion,$userId);
  $consulta="SELECT * FROM jugadores WHERE idUsuario='$usuario';";
  $datos=mysqli_query($conexion,$consulta);

  $fila=mysqli_fetch_array($datos,MYSQLI_ASSOC);

  if($fila['estado_pelea']==1){ // COMPROBAR SI EL USUARIO PUEDE PELEAR MÁS O NO.
    $response = "⛔ $firstname debes descansar un poco antes de enfrentarte a otros enemigos.";
    sendMessage($userId, $response, FALSE);

    include 'config/conexion2.php';
    $usuario2=mysqli_real_escape_string($conexion,$userId);
    $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$usuario2';";
    mysqli_query($conexion2,$consulta2);
    mysqli_close($conexion2);

    exit;
  }

  if(mysqli_num_rows($datos) > 0){

    $idJ1 = $fila['idUsuario'];
    $nombreJ1 = $fila['nombre'];
    $razaJ1 = $fila['raza'];
    $nivelJ1 = $fila['nivel'];
    $vidaJ1 = $fila['vida'];
    $vidaGJ1 = $vidaJ1;
    $defensaJ1 = $fila['defensa'];
    $ataqueJ1 = $fila['ataque'];
    $premiumJ1 = $fila['premium'];

    $peleasPosibles = $fila['peleas_posibles'];
    $estado = $fila['estado'];

    if($nombreJ1 != $message){

      confirmacion($userId, $peleasPosibles, $estado, $firstname);

    $usuarioJ2=mysqli_real_escape_string($conexion,$message);
    $consultaJ2 = "SELECT * FROM jugadores WHERE nombre='$usuarioJ2';";
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

        $response = "⛔ El jugador con el que quieres luchar le sacas o te saca 3 niveles, si quieres luchar contra él puedes hacerlo con /lucharamistoso nombre";
        sendMessage($idJ1, $response, FALSE);

        include 'config/conexion2.php';
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

          $response = "⚔🔵 ¡Has atacado a $nombreJ2! Le has hecho $ataqueRJ1 de daño. ¡Le queda $vidaJ2 de vida!";
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

            $response = "⚔🔴 ¡Te ha atacado $nombreJ2! Te ha quitado $ataqueRJ2 de vida. ¡Te queda $vidaJ1 de vida!";
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

            $consulta3="UPDATE jugadores SET muertes=$muertesInsertar, peleas_posibles=$partidasJugadas, nivel=$nivelInsertar, dinero=$dineroInsertar, `exp`=$expInsertar, ataque=$ataqueInsertar, defensa=$defensaInsertar, vida=$vidaInsertar WHERE idUsuario=$idJ1;";
            mysqli_query($conexion,$consulta3);

          }else{
            $consulta3="UPDATE jugadores SET muertes=$muertesInsertar, peleas_posibles=$partidasJugadas, dinero=$dineroInsertar, `exp`=$expInsertar WHERE idUsuario=$idJ1;";
            mysqli_query($conexion,$consulta3);
          }

          $consulta4 = "INSERT INTO luchas (jugadorUno, jugadorDos, fecha, victoria, tipo) VALUES('$idJ1','$idJ2',NOW(),'1','competitivo');";
          mysqli_query($conexion,$consulta4);

        }

      }

    }else{
      $response = "⛔ El nombre de jugador que has proporcionado no existe, inténtalo de nuevo cuando lo sepas o utiliza /lucharaleatorio, para luchar contra alguien de forma aleatoria.";
      sendMessage($userId, $response, FALSE);

      include 'config/conexion2.php';
      $usuario2=mysqli_real_escape_string($conexion,$userId);
      $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
      mysqli_query($conexion2,$consulta2);
      mysqli_close($conexion2);

      /*$consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
      mysqli_query($conexion, $consulta);*/
    }

  }else{
    $response = "⛔ $firstname, ¿te crees que puedes luchar contra ti? No estoy a favor del suicidio.";
    sendMessage($userId, $response, FALSE);

    include 'config/conexion2.php';
    $usuario2=mysqli_real_escape_string($conexion,$userId);
    $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
    mysqli_query($conexion2,$consulta2);
    mysqli_close($conexion2);

    /*$consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
    mysqli_query($conexion, $consulta);*/
  }

  include 'config/conexion2.php';
  $usuario2=mysqli_real_escape_string($conexion,$userId);
  $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
  mysqli_query($conexion2,$consulta2);
  mysqli_close($conexion2);

  }else{
    $response = "⛔ $firstname no tienes un personaje registrado a su cuenta de Telegram, por lo tanto no puedes luchar contra nadie. Para registrar tu personaje utiliza /registrar [nombreJugador] raza.";
    sendDeleteMessage($userId, $messageId, $response, FALSE);
  }

  mysqli_close($conexion);
  exit;

  break;

  // SISTEMA PARA LUCHAR DE FORMA AMISTOSA CONTRA UN JUGADOR EN CONCRETO
  case '/lucharamistoso': case '/lucharamistoso@FightETSIIT_Bot':

  if(empty($message)){
    $response = "⛔ $firstname debes indicarme un nombre cualesquiera de un jugador -> /lucharamistoso nombreJugador";
    sendDeleteMessage($userId, $messageId, $response, FALSE);
    exit;
  }

  include 'config/conexion2.php';

  $consulta2="SELECT * FROM jugadores WHERE idUsuario='$userId';";
  $datos2=mysqli_query($conexion2,$consulta2);
  $fila2=mysqli_fetch_array($datos2,MYSQLI_ASSOC);

  if($fila2['estado']==1){
    $response = "⛔ $firstname debes esperar a terminar el combate que estás realizando actualmente.";
    sendMessage($userId, $response, FALSE);
    mysqli_close($conexion2);
    exit;
  }else{
    $consulta3="UPDATE jugadores SET estado='1' WHERE idUsuario='$userId';";
    mysqli_query($conexion2, $consulta3);
    mysqli_close($conexion2);
  }

  include 'config/conexion.php';

    $usuario=mysqli_real_escape_string($conexion,$userId);
    $consulta="SELECT * FROM `jugadores` WHERE idUsuario='$usuario';";
    $datos=mysqli_query($conexion,$consulta);

    if(mysqli_num_rows($datos) > 0){
      $fila=mysqli_fetch_array($datos,MYSQLI_ASSOC);

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

            $response = "⚔🔵 ¡Has atacado a $nombreJ2! Le has hecho $ataqueRJ1 de daño. ¡Le queda $vidaJ2 de vida!";
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

              $response = "⚔🔴 ¡Te ha atacado $nombreJ2! Te ha quitado $ataqueRJ2 de vida. ¡Te queda $vidaJ1 de vida!";
              sendMessage($userId, $response, FALSE);

            }

          }

          if($vidaJ1 <= 0){

            $response = "💀 ¡Has salido derrotado contra $nombreJ2! Inténtalo más tarde.";
            sendMessage($userId, $response, FALSE);

            /*$consulta = "UPDATE jugadores SET estado=0 WHERE idUsuario=$idJ1;";
            mysqli_query($conexion,$consulta);*/

            $consulta4 = "INSERT INTO luchas (jugadorUno, jugadorDos, fecha, victoria, tipo) VALUES('$idJ1','$idJ2',NOW(),'0','amistoso');";
            mysqli_query($conexion,$consulta4);

          }else if($vidaJ2 <= 0){

            $response = "🏆 ¡Has ganado contra $nombreJ2! Enhorabuena.";
            sendMessage($userId, $response, FALSE);

            /*$consulta3="UPDATE jugadores SET estado='0' WHERE idUsuario=$idJ1;";
            mysqli_query($conexion,$consulta3);*/

            $consulta4 = "INSERT INTO luchas (jugadorUno, jugadorDos, fecha, victoria, tipo) VALUES('$idJ1','$idJ2',NOW(),'1','amistoso');";
            mysqli_query($conexion,$consulta4);

          }

      }else{
        $response = "⛔ El nombre de jugador que has proporcionado no existe, inténtalo de nuevo cuando lo sepas o utiliza /lucharaleatorio, para luchar contra alguien de forma aleatoria.";
        sendMessage($userId, $response, FALSE);

        include 'config/conexion2.php';
        $usuario2=mysqli_real_escape_string($conexion,$userId);
        $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
        mysqli_query($conexion2,$consulta2);
        mysqli_close($conexion2);

        /*$consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
        mysqli_query($conexion, $consulta);*/
      }

      }else{
      $response = "⛔ $firstname, ¿te crees que puedes luchar contra ti? No estoy a favor del suicidio.";
      sendMessage($userId, $response, FALSE);

      include 'config/conexion2.php';
      $usuario2=mysqli_real_escape_string($conexion,$userId);
      $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
      mysqli_query($conexion2,$consulta2);
      mysqli_close($conexion2);

      /*$consulta="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
      mysqli_query($conexion, $consulta);*/
      }

      include 'config/conexion2.php';
      $usuario2=mysqli_real_escape_string($conexion,$userId);
      $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
      mysqli_query($conexion2,$consulta2);
      mysqli_close($conexion2);

    }else{
      $response = "⛔ $firstname no tienes un personaje registrado a su cuenta de Telegram, por lo tanto no puedes luchar contra nadie. Para registrar tu personaje utiliza /registrar [nombreJugador] raza.";
      sendDeleteMessage($userId, $messageId, $response, FALSE);
    }

    mysqli_close($conexion);
    exit;

  break;

  // SISTEMA PARA LUCHAR DE FORMA COMPETITIVA CONTRA UN BOT.
  case '/lucharbot': case '/lucharbot@FightETSIIT_Bot':

  include 'config/conexion2.php';

  $consulta2="SELECT * FROM jugadores WHERE idUsuario='$userId';";
  $datos2=mysqli_query($conexion2,$consulta2);
  $fila2=mysqli_fetch_array($datos2,MYSQLI_ASSOC);

  if($fila2['estado']==1){
    $response = "⛔ $firstname debes esperar a terminar el combate que estás realizando actualmente.";
    sendMessage($userId, $response, FALSE);
    mysqli_close($conexion2);
    exit;
  }else{
    $consulta3="UPDATE jugadores SET estado='1' WHERE idUsuario='$userId';";
    mysqli_query($conexion2, $consulta3);
    mysqli_close($conexion2);
  }

  include 'config/conexion.php';

  $usuario=mysqli_real_escape_string($conexion,$userId);
  $consulta="SELECT * FROM jugadores WHERE idUsuario='$usuario';";
  $datos=mysqli_query($conexion,$consulta);

  $fila=mysqli_fetch_array($datos,MYSQLI_ASSOC);

  if($fila['estado_pelea']==1){ // COMPROBAR SI EL USUARIO PUEDE PELEAR MÁS O NO.
    include 'config/conexion2.php';
    $usuario2=mysqli_real_escape_string($conexion,$userId);
    $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
    mysqli_query($conexion2,$consulta2);
    mysqli_close($conexion2);

    $response = "⛔ $firstname debes descansar un poco antes de enfrentarte a otros enemigos.";
    sendDeleteMessage($userId, $messageId, $response, FALSE);
    exit;
  }

    if(mysqli_num_rows($datos) > 0){

      $idJ1 = $fila['idUsuario'];
      $razaJ1 = $fila['raza'];
      $nombreJ1 = $fila['nombre'];
      $nivelJ1 = $fila['nivel'];
      $vidaJ1 = $fila['vida'];
      $vidaGJ1 = $vidaJ1;
      $defensaJ1 = $fila['defensa'];
      $ataqueJ1 = $fila['ataque'];
      $premiumJ1 = $fila['premium'];

      $peleasPosibles = $fila['peleas_posibles'];
      $estado = $fila['estado'];

      confirmacion($userId, $peleasPosibles, $estado, $firstname);

      $razaAleatoria = rand(0,2);
      if($razaAleatoria == 0){
        $razaJ2 = 'informático';
      }else if($razaAleatoria == 1){
        $razaJ2 = 'teleco';
      }else{
        $razaJ2 = 'intruso';
      }

      if($nivelJ1 < 10){
      $vidaJ2 = rand($vidaJ1-5,$vidaJ1+5);
      $vidaGJ2 = $vidaJ2;
      $defensaJ2 = rand($vidaJ1-5,$vidaJ1+5);
      $ataqueJ2 = rand($vidaJ1-5,$vidaJ1+5);
    }else if($nivelJ1 >= 10){
      $vidaJ2 = rand($vidaJ1,$vidaJ1+5);
      $vidaGJ2 = $vidaJ2;
      $defensaJ2 = rand($vidaJ1,$vidaJ1+5);
      $ataqueJ2 = rand($vidaJ1,$vidaJ1+5);
    }

      $cont = 0;
      $salir = true;

      if($ataqueJ2 == 0){
        $ataqueJ2 = 1;
      }

      $response = "⏳ ¡Empieza el combate contra el Bot! Que gane el mejor jugador.";
      sendMessage($userId, $response, FALSE);

      while(($vidaJ1 > 0 && $vidaJ2 > 0) && $salir){

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

        $response = "⚔🔵 ¡Has atacado al Bot! Le has hecho $ataqueRJ1 de daño. ¡Le queda $vidaJ2 de vida!";
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

          $response = "⚔🔴 ¡Te ha atacado el Bot! Te ha quitado $ataqueRJ2 de vida. ¡Te queda $vidaJ1 de vida!";
          sendMessage($userId, $response, FALSE);

        }

        $cont++;
        if($cont == 30){
          $salir = false;
        }

      }

      if(!$salir){
        $response = "⚖ ¡Ha habido un empate claro!";
        sendMessage($userId, $response, FALSE);
        mysqli_close($conexion);
        exit;
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

        $expInsertar = rand(1,5);
        $dineroInsertar = rand(1,5);

        $response = "💀 ¡Has salido derrotado contra el Bot! Inténtalo más tarde. Has conseguido $expInsertar de experiencia y $dineroInsertar de dinero.";
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

      }else if($vidaJ2 <= 0){

        $dineroInsertar = rand(5,10);
        $expInsertar = rand(5,10);

        $response = "🏆 ¡Has ganado contra el Bot! Enhorabuena. Has conseguido $expInsertar de experiencia y $dineroInsertar de dinero.";
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

          $consulta3="UPDATE jugadores SET muertes=$muertesInsertar, peleas_posibles=$partidasJugadas, nivel=$nivelInsertar, dinero=$dineroInsertar, `exp`=$expInsertar, ataque=$ataqueInsertar, defensa=$defensaInsertar, vida=$vidaInsertar WHERE idUsuario=$idJ1;";
          mysqli_query($conexion,$consulta3);

        }else{
          $consulta3="UPDATE jugadores SET muertes=$muertesInsertar, peleas_posibles=$partidasJugadas, dinero=$dineroInsertar, `exp`=$expInsertar WHERE idUsuario=$idJ1;";
          mysqli_query($conexion,$consulta3);
        }

      }

    }else{
      $response = "⛔ $firstname no tienes un personaje registrado a su cuenta de Telegram, por lo tanto no puedes luchar contra nadie. Para registrar tu personaje utiliza /registrar [nombreJugador] raza.";
      sendDeleteMessage($userId, $messageId, $response, FALSE);

      include 'config/conexion2.php';
      $usuario2=mysqli_real_escape_string($conexion,$userId);
      $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
      mysqli_query($conexion2,$consulta2);
      mysqli_close($conexion2);
    }

    include 'config/conexion2.php';
    $usuario2=mysqli_real_escape_string($conexion,$userId);
    $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
    mysqli_query($conexion2,$consulta2);
    mysqli_close($conexion2);

    mysqli_close($conexion);
    exit;

  break;

  // SISTEMA PARA LUCHAR DE FORMA COMPETITIVA CONTRA UN JUGADOR ALEATORIO.
  case '/lucharaleatorio': case '/lucharaleatorio@FightETSIIT_Bot':

  include 'config/conexion2.php';

  $consulta2="SELECT * FROM jugadores WHERE idUsuario='$userId';";
  $datos2=mysqli_query($conexion2,$consulta2);
  $fila2=mysqli_fetch_array($datos2,MYSQLI_ASSOC);

  if($fila2['estado']==1){
    $response = "⛔ $firstname debes esperar a terminar el combate que estás realizando actualmente.";
    sendMessage($userId, $response, FALSE);
    mysqli_close($conexion2);
    exit;
  }else{
    $consulta3="UPDATE jugadores SET estado='1' WHERE idUsuario='$userId';";
    mysqli_query($conexion2, $consulta3);
    mysqli_close($conexion2);
  }

  include 'config/conexion.php';

  $usuario=mysqli_real_escape_string($conexion,$userId);
  $consulta="SELECT * FROM jugadores WHERE idUsuario='$usuario';";
  $datos=mysqli_query($conexion,$consulta);

  $fila=mysqli_fetch_array($datos,MYSQLI_ASSOC);

    if($fila['estado_pelea']==1){ // COMPROBAR SI EL USUARIO PUEDE PELEAR MÁS O NO.
      include 'config/conexion2.php';
      $usuario2=mysqli_real_escape_string($conexion,$userId);
      $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
      mysqli_query($conexion2,$consulta2);
      mysqli_close($conexion2);

      $response = "⛔ $firstname debes descansar un poco antes de enfrentarte a otros enemigos.";
      sendMessage($userId, $response, FALSE);
      exit;
    }

    if(mysqli_num_rows($datos) > 0){

        $idJ1 = $fila['idUsuario'];
        $razaJ1 = $fila['raza'];
        $nombreJ1 = $fila['nombre'];
        $nivelJ1 = $fila['nivel'];
        $vidaJ1 = $fila['vida'];
        $vidaGJ1 = $vidaJ1;
        $defensaJ1 = $fila['defensa'];
        $ataqueJ1 = $fila['ataque'];
        $premiumJ1 = $fila['premium'];

        $peleasPosibles = $fila['peleas_posibles'];
        $estado = $fila['estado'];

        confirmacion($userId, $peleasPosibles, $estado, $firstname);

        $consultaJ2 = "SELECT * FROM jugadores WHERE nivel>=($nivelJ1-3) AND nivel<=($nivelJ1+3) AND idUsuario!='$userId';";
        $datosJ2 = mysqli_query($conexion,$consultaJ2);

        $contador = 0;

        if(mysqli_num_rows($datosJ2)>0){
          while($filaJ2=mysqli_fetch_array($datosJ2,MYSQLI_ASSOC)){
            $contador++;
          }
          $contAleatoria = rand(0,$contador-1);
        }else{
          include 'config/conexion2.php';
          $usuario2=mysqli_real_escape_string($conexion,$userId);
          $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
          mysqli_query($conexion2,$consulta2);
          mysqli_close($conexion2);

          $response = "⛔ No hay ningún jugador con el que puedas jugar. Inténtalo más tarde. Si quieres puedes jugar contra algún bot con el comando /lucharbot.";
          sendMessage($userId, $response, FALSE);
          mysqli_close($conexion);
          exit;
        }

        $consultaJ2 = "SELECT * FROM jugadores WHERE nivel>=($nivelJ1-3) AND nivel<=($nivelJ1+3) AND idUsuario!='$userId';";
        $datosJ2 = mysqli_query($conexion,$consultaJ2);

        $contador = 0;

          while($filaJ2=mysqli_fetch_array($datosJ2,MYSQLI_ASSOC)){
            if($contador == $contAleatoria){
              $idJ2 = $filaJ2['idUsuario'];
            }
            $contador++;
          }

          $consultaJ2 = "SELECT * FROM jugadores WHERE idUsuario='$idJ2';";
          $datosJ2 = mysqli_query($conexion,$consultaJ2);

          if(mysqli_num_rows($datosJ2)>0){
            $filaJ2=mysqli_fetch_array($datosJ2,MYSQLI_ASSOC);

            $idJ2 = $filaJ2['idUsuario'];
            $razaJ2 = $filaJ2['raza'];
            $nombreJ2 = $filaJ2['nombre'];
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

                $response = "⚔🔵 ¡Has atacado a $nombreJ2! Le has hecho $ataqueRJ1 de daño. ¡Le queda $vidaJ2 de vida!";
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

                  $response = "⚔🔴 ¡Te ha atacado $nombreJ2! Te ha quitado $ataqueRJ2 de vida. ¡Te queda $vidaJ1 de vida!";
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

                  $consulta3="UPDATE jugadores SET muertes=$muertesInsertar, peleas_posibles=$partidasJugadas, nivel=$nivelInsertar, dinero=$dineroInsertar, `exp`=$expInsertar, ataque=$ataqueInsertar, defensa=$defensaInsertar, vida=$vidaInsertar WHERE idUsuario=$idJ1;";
                  mysqli_query($conexion,$consulta3);

                }else{
                  $consulta3="UPDATE jugadores SET muertes=$muertesInsertar, peleas_posibles=$partidasJugadas, dinero=$dineroInsertar, `exp`=$expInsertar WHERE idUsuario=$idJ1;";
                  mysqli_query($conexion,$consulta3);
                }

                $consulta4 = "INSERT INTO luchas (jugadorUno, jugadorDos, fecha, victoria, tipo) VALUES('$idJ1','$idJ2',NOW(),'1','competitivo');";
                mysqli_query($conexion,$consulta4);

              }
          }



    }else{
      $response = "⛔ $firstname no tienes un personaje registrado a su cuenta de Telegram, por lo tanto no puedes luchar contra nadie. Para registrar tu personaje utiliza /registrar [nombreJugador] raza.";
      sendDeleteMessage($userId, $messageId, $response, FALSE);

      include 'config/conexion2.php';
      $usuario2=mysqli_real_escape_string($conexion,$userId);
      $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
      mysqli_query($conexion2,$consulta2);
      mysqli_close($conexion2);
    }

    include 'config/conexion2.php';
    $usuario2=mysqli_real_escape_string($conexion,$userId);
    $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
    mysqli_query($conexion2,$consulta2);
    mysqli_close($conexion2);

    mysqli_close($conexion);
    exit;
  break;

}

include_once 'estados.php';

?>

<?php

include_once 'funciones.php';
include_once 'variables.php';

// CON EL EXPLODE TOMAMOS EL PRIMER VALOR DEL MENSAJE ASÍ VEMOS SI ESTÁ USANDO EL COMANDO O NO.
$arr = explode(' ',trim($message));
$command = $arr[0];

$message = substr(strstr($message," "), 1);

switch($command){

  // SISTEM PARA RENOVAR LUCHAS.

  case '/renovar': case '/renovar@FightETSIIT_Bot':
  include 'conexion.php';
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
  include 'conexion.php';
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

    $response = "🎉 Bienvenido a Fight ETSIIT, un juego creado por @IgnasiCR.\n\nPara poder crear tu jugador debes usar el comando /registrarse, podrás elegir entre ser Informático, Teleco o Intruso.\n\nSi colocas una '/' en el chat te saldrán todas las opciones posibles para el juego. Si tienes más dudas puedes utilizar el comando /ayuda.";
    sendDeleteMessage($userId, $messageId, $response, FALSE);

    exit;
  break;

  // COMANDO PARA QUE EL USUARIO PUEDA OBTENER MÁS INFORMACIÓN SOBRE EL JUEGO.
  case '/ayuda': case '/ayuda@FightETSIIT_Bot':

    $response .= "❓ <b>¿Cómo funciona el sistema de luchas?</b>\nCada jugador tiene unas características (defensa, ataque y vida), y de forma aleatoria se calcula el ataque final que hará un jugador contra el otro teniendo en cuenta la defensa del contrincante. El sistema de lucha es totalmente automática por lo que el jugador atacará solo. Al final de la lucha se indicará que jugador ha sido el ganador, en caso de perder tan solo obtendrás experiencia, pero en caso de ganar también conseguirá oro.\n";
    $response .="\n❓ <b>¿Qué tipo de luchas existen?</b>\nExisten dos tipos de luchas, las competitivas y las amistosas. En las competitivas ganes o pierdas conseguirás experiencia, y en caso de ganar conseguirás también dinero. En cambio en las amistosas no ganarás nada, ni experiencia ni dinero. Además hay que destacar que en las competitivas solo podrás luchar con gente -+3 niveles que tu, en las amistosas con quién quieras.\n";
    $response .="\n❓ <b>¿Cuántas veces puedo jugar?</b>\nComo máximo se puede jugar 30 luchas cada media hora. Tanto a las en punto como a las y media se regeneran todas las luchas posibles pero las que te quedaron anteriormente no se suman a estas. Aprovecha las 30 de cada media hora para poder conseguir estar en el ranking.\n";
    $response .="\n❓ <b>¿Cómo funciona el sistema de ranking?</b>\nEl ranking está ordenado por los asesinatos. Los jugadores con mayor asesinatos se encontrarán en este ranking, por lo tanto... ¡ponte a luchar para ser el primero!\n";
    $response .="\n❓ <b>¿Cuántos objetos puedo comprar en la tienda?</b>\nNo hay limite de objetos que puedas comprar en la tienda. Siempre y cuando tengas el dinero para hacerte con alguno de ellos podrás hacerlo.\n";
    $response .="\n❓ <b>¿Cuántas razas existen en Fight ETSIIT?</b>\nExisten tres tipos de razas: informático, teleco e intruso. Cada una de ellas tiene una ventaja importante en el combate.\n\n<b>Informático</b>: Tienen un 20% de conseguir un 50% más de ataque en cada turno.\n<b>Teleco</b>: Tienen un 20% de poder aumentar/curarse la vida en un 50% respecto a su base en cada turno.\n<b>Intruso</b>: Tienen un 20% de conseguir un 50% más de defensa en cada turno.\n";
    $response .="\n❓ <b>¿Puedo cambiarme de raza después de haber elegido?</b>\nSí, siempre y cuándo tengas 10.000 de dinero podrás cambiarte de raza utilizando el comando /cambiarseraza. A partir de ese momento en las luchas podrás obtener el poder oculto de la raza a las que te has cambiado y no se te borrarán los objetos comprados anteriormente de la raza a la que pertenecías.\n";

    sendDeleteMessage($userId, $messageId, $response, FALSE);

    exit;
  break;

  case '/creditos': case '/creditos@FightETSIIT_Bot':

    $response .= "📢 <b>Personas que trabajan en el proyecto:</b>\n";
    $response .="\n🖥 <b>Programadores</b>\n";
    $response .="@IgnasiCR\n@ManuJNR\n";
    $response .="\n🎮 <b>Testers/Colaboradores</b>\n";
    $response .="@DarkAsdfgh\n@laurator\n@Sheisenn\n@Nekire\n";
    $response .="\n🤺 Versión 1.0 - Fight ETSIIT\n";

    sendDeleteMessage($userId, $messageId, $response, FALSE);

    exit;
  break;

  case '/donaciones': case '/donaciones@FightETSIIT_Bot':

    $response .="\n💵 Si te ha gustado el juego y quieres aportar a que mejore y/o pasemos el formato a aplicación móvil en un futuro, puedes dejar tu granito de arena en la siguiente cuenta:\n";
    $response .="\n<a href='paypal.me/IgnasiCR17'>PayPal - IgnasiCR17</a>";

    sendDeleteMessage($userId, $messageId, $response, TRUE);

    exit;
  break;

    // SISTEMA DE REGISTRO DEL USUARIO.
    case '/registrarse': case '/registrarse@FightETSIIT_Bot':
    include 'conexion.php';
    $usuario=mysqli_real_escape_string($conexion,$userId);
    $consulta="SELECT * FROM `jugadores` WHERE idUsuario='$usuario';";
    $datos=mysqli_query($conexion,$consulta);

    if(mysqli_num_rows($datos)==0){
      $consulta="INSERT INTO `jugadores` (idUsuario, nombre, estado) VALUES ('$userId', '$firstname', '1');";
      mysqli_query($conexion, $consulta);

      $response = "🆕 $firstname hemos registrado tu cuenta.\n\nTu nombre de jugador será el siguiente: $firstname. Si quieres que sea este tan solo diga 'Si', si quieres otro nombre di el nombre que te gustaría tener.";
      sendMessage($userId, $response, FALSE);
      mysqli_close($conexion);
    }else{
      $response = "⛔ ¡$firstname tu ya tienes un personaje registrado a tu cuenta de Telegram! Puedes utilizar el comando /mipersonaje para más información.";
      sendDeleteMessage($userId, $messageId, $response, FALSE);
    }

    mysqli_close($conexion);
    exit;

  break;


  // COMANDO PARA QUE EL USUARIO PUEDA CONOCER LAS ESTADÍSTICAS DE SU JUGADOR.
  case '/mipersonaje': case 'mipersonaje@FightETSIIT_Bot':
  include 'conexion.php';
    $usuario=mysqli_real_escape_string($conexion,$userId);
    $consulta="SELECT * FROM `jugadores` WHERE idUsuario='$usuario';";
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

      $response = "📊 <b>Estadísticas Personaje</b>\n\n👤 Nombre: $nombre\n$icono Raza: $raza\n🚩 Nivel: $nivel\n🎮 Experiencia: $exp/$expN\n\n💰 Dinero: $dinero\n💀 Asesinatos: $muertes\n\n⚔ Ataque: $ataque\n🛡 Defensa: $defensa\n❤ Vida: $vida";
      sendDeleteMessage($userId, $messageId, $response, FALSE);

    }else{
      $response = "⛔ $firstname no tienes un personaje registrado a tu cuenta, para ello utiliza /registrarse.";
      sendDeleteMessage($userId, $messageId, $response, FALSE);
    }

    mysqli_close($conexion);
    exit;

  break;

  // COMANDO PARA QUE EL USUARIO PUEDA VER AL TIENDA OFICIAL DE OBJETOS.
  case '/tienda': case '/tienda@FightETSIIT_Bot':
  include 'conexion.php';
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
      $response = "⛔ $firstname no tienes un personaje registrado a tu cuenta por lo tanto no puedes hacer uso de la tienda, para ello utiliza /registrarse.";
      sendDeleteMessage($userId, $messageId, $response, FALSE);
    }

    mysqli_close($conexion);
    exit;

  break;

  // SISTEMA PARA QUE EL USUARIO PUEDA REALIZAR COMPRAS DE OBJETOS.
  case '/comprar': case '/comprar@FightETSIIT_Bot':
  include 'conexion.php';
    $usuario=mysqli_real_escape_string($conexion,$userId);
    $consulta="SELECT * FROM `jugadores` WHERE idUsuario='$usuario';";
    $datos=mysqli_query($conexion,$consulta);

    if(mysqli_num_rows($datos) > 0){

      $consulta="UPDATE jugadores SET estado='3' WHERE idUsuario='$userId';";
      mysqli_query($conexion, $consulta);

      $response = "👛 Ahora tendrás que darme el número de identificador del objeto de la tienda que quieres comprar. El dinero se te descontará automáticamente de tu personaje.";
      sendDeleteMessage($userId, $messageId, $response, FALSE);

    }else{
      $response = "⛔ $firstname no tienes un personaje registrado a tu cuenta por lo tanto no puedes comprar ningún objeto de la tienda, para ello utiliza /registrarse.";
      sendDeleteMessage($userId, $messageId, $response, FALSE);
    }

    mysqli_close($conexion);
    exit;

  break;

  // SISTEMA PARA QUE EL USUARIO PUEDA CAMBIARSE DE RAZA.

  case '/cambiarseraza': case '/cambiarseraza@FightETSIIT_Bot':
  include 'conexion.php';
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
      $response = "⛔ $firstname no tienes un personaje registrado a tu cuenta por lo tanto no puedes comprar ningún objeto de la tienda, para ello utiliza /registrarse.";
      sendDeleteMessage($userId, $messageId, $response, FALSE);
    }

    mysqli_close($conexion);
    exit;
  break;

  // COMANDO PARA MOSTRAR EL RANKING PROPIO DEL JUGADOR.
  case '/miranking': case '/miranking@FightETSIIT_Bot':
  include 'conexion.php';
    $consulta = "SELECT * FROM jugadores ORDER BY muertes DESC;";
    $datos=mysqli_query($conexion,$consulta);
    $contador = 1;
    $salida = true;

    $response .="<b>Posición propia en el Ranking General</b>\n";

    while(($fila=mysqli_fetch_array($datos,MYSQLI_ASSOC)) && $salida){

      $idUsuario = $fila['idUsuario'];

      if($idUsuario == $userId){

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

      $consulta2 = "SELECT COUNT(*) as total FROM jugadores;";
      $datos2 = mysqli_query($conexion, $consulta2);
      $fila=mysqli_fetch_array($datos2,MYSQLI_ASSOC);
      $cantidadUsuarios = $fila['total'];

      $response .= "\n$icono <b>Posicion $contador/$cantidadUsuarios:</b>\n\n👤 Nombre: $nombreUsuario\n$iconoR Raza: $raza\n🚩 Nivel: $nivel\n💀 Asesinatos: $muertes\n";
      $salida = false;

      }

      $contador++;
    }

    sendDeleteMessage($userId, $messageId, $response, FALSE);
    mysqli_close($conexion);
    exit;

  break;

  // COMANDO PARA MOSTRAR EL RANKING GENERAL.
  case '/ranking': case '/ranking@FightETSIIT_Bot':
  include 'conexion.php';
    $consulta = "SELECT * FROM jugadores ORDER BY muertes DESC LIMIT 10;";
    $datos=mysqli_query($conexion,$consulta);
    $contador = 1;

    $response .="<b>Ranking General</b>\n";

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

  // COMANDO PARA MOSTRAR EL RANKING DE INFORMÁTICA.
  case '/rankinginformatica': case '/rankinginformatica@FightETSIIT_Bot':
    include 'conexion.php';
    $consulta = "SELECT * FROM jugadores WHERE raza='informático' ORDER BY muertes DESC LIMIT 10;";
    $datos=mysqli_query($conexion,$consulta);
    $contador = 1;

    if(mysqli_num_rows($datos) > 0){
      $response .="<b>Ranking Informática</b>\n";
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

  // COMANDO PARA MOSTRAR EL RANKING DE TELECOS.
  case '/rankingteleco': case '/rankingteleco@FightETSIIT_Bot':
  include 'conexion.php';
    $consulta = "SELECT * FROM jugadores WHERE raza='teleco' ORDER BY muertes DESC LIMIT 10;";
    $datos=mysqli_query($conexion,$consulta);
    $contador = 1;

    if(mysqli_num_rows($datos) > 0){
      $response .="<b>Ranking Telecomunicaciones</b>\n";
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
      $response = "⛔ No existe ningún teleco actualmente en el sistema.";
  }

    sendDeleteMessage($userId, $messageId, $response, FALSE);
    mysqli_close($conexion);
    exit;

  break;

  // COMANDO PARA MOSTRAR EL RANKING DE INTRUSOS.
  case '/rankingintruso': case '/rankingintruso@FightETSIIT_Bot':
  include 'conexion.php';
    $consulta = "SELECT * FROM jugadores WHERE raza='intruso' ORDER BY muertes DESC LIMIT 10;";
    $datos=mysqli_query($conexion,$consulta);
    $contador = 1;

    if(mysqli_num_rows($datos) > 0){
      $response .="<b>Ranking Intrusos</b>\n";
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
    $response = "⛔ No existe ningún intruso actualmente en el sistema.";
  }

    sendDeleteMessage($userId, $messageId, $response, FALSE);
    mysqli_close($conexion);
    exit;

  break;

  // COMANDO PARA CONOCER LAS ÚLTIMAS 5 LUCHAS QUE HAS REALIZADO.
  case '/ultimasluchas': case '/ultimasluchas@FightETSIIT_Bot':
  include 'conexion.php';
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

  include 'conexion2.php';

  $consulta2="SELECT * FROM jugadores WHERE idUsuario='$userId';";
  $datos2=mysqli_query($conexion2,$consulta2);
  $fila2=mysqli_fetch_array($datos2,MYSQLI_ASSOC);

  if($fila2['estado']=='1'){
    $response = "⛔ $firstname debes esperar a terminar el combate que estás realizando actualmente.";
    sendMessage($userId, $response, FALSE);
    mysqli_close($conexion2);
    exit;
  }else{
    $consulta3="UPDATE jugadores SET estado='1' WHERE idUsuario='$userId';";
    mysqli_query($conexion2, $consulta3);
    mysqli_close($conexion2);
  }

  include 'conexion.php';

  $usuario=mysqli_real_escape_string($conexion,$userId);
  $consulta="SELECT * FROM jugadores WHERE idUsuario='$usuario';";
  $datos=mysqli_query($conexion,$consulta);

  $fila=mysqli_fetch_array($datos,MYSQLI_ASSOC);

  if($fila['estado_pelea']==1){ // COMPROBAR SI EL USUARIO PUEDE PELEAR MÁS O NO.
    $response = "⛔ $firstname debes descansar un poco antes de enfrentarte a otros enemigos.";
    sendMessage($userId, $response, FALSE);

    include 'conexion2.php';
    $usuario2=mysqli_real_escape_string($conexion,$userId);
    $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
    mysqli_query($conexion2,$consulta2);
    mysqli_close($conexion2);

    exit;
  }

  if(mysqli_num_rows($datos) > 0){

    $consulta="UPDATE jugadores SET estado='4' WHERE idUsuario='$userId';";
    mysqli_query($conexion, $consulta);

    $response = "📯 Ahora tendrás que darme el nombre del jugador con el que quieres luchar. Recuerda que el jugador no puede tener menos ni más de 3 niveles que tu.";
    sendDeleteMessage($userId, $messageId, $response, FALSE);

  }else{
    $response = "⛔ $firstname no tienes un personaje registrado a su cuenta de Telegram, por lo tanto no puedes luchar contra nadie. Para registrar tu personaje utiliza /registrar.";
    sendDeleteMessage($userId, $messageId, $response, FALSE);
  }

  mysqli_close($conexion);
  exit;

  break;

  // SISTEMA PARA LUCHAR DE FORMA AMISTOSA CONTRA UN JUGADOR EN CONCRETO
  case '/lucharamistoso': case '/lucharamistoso@FightETSIIT_Bot':

  include 'conexion2.php';

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

  include 'conexion.php';

    $usuario=mysqli_real_escape_string($conexion,$userId);
    $consulta="SELECT * FROM `jugadores` WHERE idUsuario='$usuario';";
    $datos=mysqli_query($conexion,$consulta);

    if(mysqli_num_rows($datos) > 0){

      $consulta="UPDATE jugadores SET estado='5' WHERE idUsuario='$userId';";
      mysqli_query($conexion, $consulta);

      $response = "📯 Ahora tendrás que darme el nombre del jugador con el que quieres luchar de forma amistosa. Recuerda que con este combate no obtendrás ni dinero ni experiencia.";
      sendDeleteMessage($userId, $messageId, $response, FALSE);

    }else{
      $response = "⛔ $firstname no tienes un personaje registrado a su cuenta de Telegram, por lo tanto no puedes luchar contra nadie. Para registrar tu personaje utiliza /registrar.";
      sendDeleteMessage($userId, $messageId, $response, FALSE);
    }

    mysqli_close($conexion);
    exit;

  break;

  // SISTEMA PARA LUCHAR DE FORMA COMPETITIVA CONTRA UN BOT.
  case '/lucharbot': case '/lucharbot@FightETSIIT_Bot':

  include 'conexion2.php';

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

  include 'conexion.php';

  $usuario=mysqli_real_escape_string($conexion,$userId);
  $consulta="SELECT * FROM jugadores WHERE idUsuario='$usuario';";
  $datos=mysqli_query($conexion,$consulta);

  $fila=mysqli_fetch_array($datos,MYSQLI_ASSOC);

  if($fila['estado_pelea']==1){ // COMPROBAR SI EL USUARIO PUEDE PELEAR MÁS O NO.
    include 'conexion2.php';
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
      $response = "⛔ $firstname no tienes un personaje registrado a su cuenta de Telegram, por lo tanto no puedes luchar contra nadie. Para registrar tu personaje utiliza /registrar.";
      sendDeleteMessage($userId, $messageId, $response, FALSE);

      include 'conexion2.php';
      $usuario2=mysqli_real_escape_string($conexion,$userId);
      $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
      mysqli_query($conexion2,$consulta2);
      mysqli_close($conexion2);
    }

    include 'conexion2.php';
    $usuario2=mysqli_real_escape_string($conexion,$userId);
    $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
    mysqli_query($conexion2,$consulta2);
    mysqli_close($conexion2);

    mysqli_close($conexion);
    exit;

  break;

  // SISTEMA PARA LUCHAR DE FORMA COMPETITIVA CONTRA UN JUGADOR ALEATORIO.
  case '/lucharaleatorio': case '/lucharaleatorio@FightETSIIT_Bot':

  include 'conexion2.php';

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

  include 'conexion.php';

  $usuario=mysqli_real_escape_string($conexion,$userId);
  $consulta="SELECT * FROM jugadores WHERE idUsuario='$usuario';";
  $datos=mysqli_query($conexion,$consulta);

  $fila=mysqli_fetch_array($datos,MYSQLI_ASSOC);

    if($fila['estado_pelea']==1){ // COMPROBAR SI EL USUARIO PUEDE PELEAR MÁS O NO.
      include 'conexion2.php';
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

        $consultaJ2 = "SELECT * FROM jugadores WHERE nivel>=($nivelJ1-3) AND nivel<=($nivelJ1+3) AND idUsuario!='$userId';";
        $datosJ2 = mysqli_query($conexion,$consultaJ2);

        $contador = 0;

        if(mysqli_num_rows($datosJ2)>0){
          while($filaJ2=mysqli_fetch_array($datosJ2,MYSQLI_ASSOC)){
            $contador++;
          }
          $contAleatoria = rand(0,$contador-1);
        }else{
          include 'conexion2.php';
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
      $response = "⛔ $firstname no tienes un personaje registrado a su cuenta de Telegram, por lo tanto no puedes luchar contra nadie. Para registrar tu personaje utiliza /registrar.";
      sendDeleteMessage($userId, $messageId, $response, FALSE);

      include 'conexion2.php';
      $usuario2=mysqli_real_escape_string($conexion,$userId);
      $consulta2="UPDATE jugadores SET estado='0' WHERE idUsuario='$userId';";
      mysqli_query($conexion2,$consulta2);
      mysqli_close($conexion2);
    }

    include 'conexion2.php';
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

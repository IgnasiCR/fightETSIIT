#!/bin/bash

mysql -u USUARIO -pCONTRASEÑA -h HOST BD -e "UPDATE jugadores SET estado_pelea=0, peleas_posibles=30;"

USERID="444137662" # IDENTIFICADOR DEL GRUPO Y/O USUARIO.
KEY="" # TOKEN DEL BOT.
URL="https://api.telegram.org/bot$KEY/sendMessage"

MSG="‼ LUCHAS RENOVADAS, LUCHAS RENOVADAS ‼"
curl -s --max-time 10 -d "chat_id=$USERID&disable_web_page_preview=1&text=$MSG" $URL

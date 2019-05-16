<?php

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

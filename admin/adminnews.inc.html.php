<?

echo'<p  align = "center">'.$firstTags;
echomarkdown(implode(' ', array_slice(explode(' ', strip_tags($messageText)), 0, 50)));
echo $lastTags.'</p>';
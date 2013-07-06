<?php

/**
* Shorthand function for htmlspecialchars
* @param string $content
* @return string
*/
function h($content) {
return htmlspecialchars($content, ENT_QUOTES, "UTF-8");
}
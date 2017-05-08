<?php

// This function is for securing input and output strings

// The htmlentities() function encodes the characters to
// html entities. Example: &quot; will be encoded to "

function escape($string){
    return htmlentities($string, ENT_QUOTES, 'UTF-8');  
}
// ENT_QUOTES escapes single and double codes
// defining utf-8 makes stuff more secure for some reason 
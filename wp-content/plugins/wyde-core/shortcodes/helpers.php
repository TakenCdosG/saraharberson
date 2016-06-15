<?php

/* Register body styles */
function wyde_add_body_style($handle, $src){
    global $wyde_body_stylesheets;
    if( !$wyde_body_stylesheets ){
        $wyde_body_stylesheets = array();
    }

    $wyde_body_stylesheets[$handle] = $src;    
}
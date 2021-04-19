<?php

// Routes
//**********************| Routes |****************************************** */
$cheks   = 'includes/functions/checksession.php';
$lang    = 'includes/languages/';
$func    = 'includes/functions/';
$tpl     = 'includes/templates/';
$css     = 'layout/css/';
$js      = 'layout/js/';
$fullName ='$firstName ." " . $lastName';
//*********************************************************************** */

//*********************// includes files //********************************//

//include $func . 'checksession.php';
//require_once $cheks;
include $lang . 'english.php';
include $func . 'connect.php';
include $func . 'function.php';
include $tpl . 'header.php';


////////////////////////////////////////////////////////////////////
//incloud navbar on all pages expect the one $noNavebar variable///
if (!isset($noNavbar) && $_SESSION['States'] == 1) {
    include $tpl . 'navbarad.php';
    echo $navAdmin;
} elseif (!isset($noNavbar) && $_SESSION['States'] == 2) {
    include $tpl . 'navbarad.php';
    echo $navlocalAdmin;
} elseif (!isset($noNavbar)){
    include $tpl . 'navbar.php';
}
                //
/////////////////////////////////////////////////////////////////

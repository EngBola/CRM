<?php

    function lang($phrase){
        static $lang = array(

            /*_____________NAVBAR LINKS__________________*/
            'MANAGER'       => 'MANAGER',                    //
            'LOCAL'         => 'LOCAL',                    //
            'HOME'          => 'Home',                    //
            'CATEGORIES'    => 'Categories',             //
            'ITEM'          => 'Item',                  //
            'MEMBER'        => 'Member',                //
            'COMMENTS'      => 'Comments',                //
            'STATIC'        => 'Static',                //
            'LOGS'          => 'Logs',                  //
            'MYPROFILE'     => 'Edite Profile',         //
            'SETTINGS'      => 'Settings',               //
            'LOGOUT'        => 'Logout',                  //
            /*_____________end nav links__________________*/
        );
   
    return $lang[$phrase];
    }
?>

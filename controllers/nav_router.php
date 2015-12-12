<?php
    if(!isset($_SESSION['id']))
    {
        require('views/nav_anon.phtml');
    }
    else
    {
        require('views/nav_user.phtml');
        if($currentUser->getStatus())
        {
            require('views/nav_admin.phtml');
        }
    }

?>
<?php

// Start session
session_start();

// Initialize database
require('db.php');
try
{
    $db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
}
catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}


// Errors
$errors = array();


// Objects autoloader
spl_autoload_register(function($class)
{
	require('models/'.$class.'.class.php');
});


// Load user if session id exists
if (isset($_SESSION['id']))
{
	$userManager = new UserManager($db);
	$currentUser = $userManager -> readById($_SESSION['id']);
}


// Pages
$access_anon 	= array('login', 'register');
$access_user 	= array('logout');
$access_admin	= array();


// Handlers
$handlers_anon 	= array('login' => 'user', 'register' => 'user');
$handlers_user 	= array();
$handlers_admin = array();

if (isset($_GET['page']))
{


	// Logout
	if ($_GET['page'] === 'logout')
	{
		session_destroy();
		$_SESSION = array();
		header('Location: ?page=articles');
		exit;
	}


	// Anonymous access page
	if (in_array($_GET['page'], $access_anon) && !isset($_SESSION['id']))
	{
		$page = $_GET['page'];

		if (isset($handlers_public[$_GET['page']]) && !empty($_POST))
		{
			require('controllers/handlers/handler_'.$handlers_public[$_GET['page']].'.php');
		}
	}
	else if (in_array($_GET['page'], $access_user) && isset($_SESSION['id']))
	{
		$page = $_GET['page'];

		if (isset($handlers_user[$_GET['page']]) && !empty($_POST))
		{
			if (is_array($handlers_user[$_GET['page']]))
			{
				for ($i=0, $c = count($handlers_user[$_GET['page']]); $i < $c; $i++)
				{
					require('controllers/handlers/handler_'.$handlers_user[$_GET['page']][$i].'.php');
				}
			}
			else
			{
				require('controllers/handlers/handler_'.$handlers_user[$_GET['page']].'.php');
			}
		}
	}
	else if (in_array($_GET['page'], $access_admin) && isset($_SESSION['id']) && $currentUser -> getStatus() == 2)
	{
		$page = $_GET['page'];

		if (isset($handlers_admin[$_GET['page']]) && !empty($_POST))
		{
			if (is_array($handlers_admin[$_GET['page']]))
			{
				for ($i=0, $c = count($handlers_admin[$_GET['page']]); $i < $c; $i++)
				{
					require('controllers/handlers/handler_'.$handlers_admin[$_GET['page']][$i].'.php');
				}
			}
			else
			{
				require('controllers/handlers/handler_'.$handlers_admin[$_GET['page']].'.php');
			}
		}
	}
	else
	{
		if (isset($_SESSION['id']))
		{
			$page = 'home';
		}
		else
		{
			$page = 'login';
		}
	}
}
else
{
	if (isset($_SESSION['id']))
	{
		$page = 'home';
	}
	else
	{
		$page = 'login';
	}
}

require('controllers/skel.php');
?>
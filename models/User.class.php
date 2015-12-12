<?php
class User
{


	// Properties
	private $id;
	private $email;
	private $hash;
	private $name;
	private $surname;
	private $status;
	private $db;


	// Constructor
	public function __construct($db)
	{
		$this -> db = $db;
	}


	// Getters


	// Get user id
	public function getId()
	{
		return $this -> id;
	}
	// Get user email
	public function getEmail()
	{
		return $this -> email;
	}
	// Get user hash
	public function getHash()
	{
		return $this -> hash;
	}
	// Get user name
	public function getName()
	{
		return $this -> name;
	}
	// Get user surname
	public function getSurname()
	{
		return $this -> surname;
	}
	// Get user status
	public function getStatus()
	{
		return $this -> status;
	}


	// Setters


	// Set user email
	public function setEmail($email)
	{
		if (strlen($email) > 5 && strlen($email) < 63)
		{
			if (preg_match("#^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z]{2,5}$#", $email))
			{
				$this -> email = $email;
				return true;
			}
			else
			{
				throw new Exception('Email format invalid');
			}
		}
		else
		{
			throw new Exception('Email format invalid');
		}
	}


	// Set user hash
	public function setHash($password, $password2)
	{
		if (strlen($password) > 5 && strlen($password) < 32)
		{
			if ($password == $password2)
			{
				$this -> hash = password_hash($password, PASSWORD_DEFAULT);
				return true;
			}
			else
			{
				throw new Exception("Passwords don't match");
			}
		}
		else
		{
			throw new Exception('Password must be between 6 and 31 characters');
		}
	}


	// Set user name
	public function setName($name)
	{
		if (strlen($name) > 1 && strlen($name) < 32)
		{
			if (preg_match("#[a-zA-Z0-9]+[ -_']*$#", $name))
			{
				$this -> name = $name;
				return true;
			}
			else
			{
				throw new Exception('Invalid name');
			}
		}
		else
		{
			throw new Exception("Name must be between 2 and 31 characters");
		}
	}


	// Set user surname
	public function setSurname($surname)
	{
		if (strlen($surname) > 1 && strlen($surname) < 32)
		{
			if (preg_match("#[a-zA-Z0-9]+[ -_']*$#", $surname))
			{
				$this -> surname = $surname;
				return true;
			}
			else
			{
				throw new Exception('Invalid surname');
			}
		}
		else
		{
			throw new Exception("Surname must be between 2 and 31 characters");
		}
	}


	// Set user status
	public function setStatus($status)
	{
		if ($status == 0)
		{
			$this -> status = 0;
			return true;
		}
		else if ($status == 1)
		{
			$this -> status = 1;
			return true;
		}
		else if ($status == 2)
		{
			$this -> status = 2;
			return true;
		}
		else
		{
			throw new Exception('Invalid status');
		}
	}


	// Check user password
	public function checkPassword($password)
	{
		return password_verify($password, $this -> hash);
	}
}
 ?>
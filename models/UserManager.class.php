<?php
class UserManager
{


	// Properties
	private $db;


	// Constructor
	public function __construct($db)
	{
		$this -> db = $db;
	}


	// Create user
	public function create($email, $password, $password2, $name, $surname)
	{
		$user 	= new User($this -> db);
		$errors = array();

		try {
			$user -> setEmail($email)
		} catch (Exception $e) {
			$errors[] = $e -> getMessage();
		}
		try {
			$user -> setHash($password, $password2);
		} catch (Exception $e) {
			$errors[] = $e -> getMessage();
		}
		try {
			$user -> setName($name);
		} catch (Exception $e) {
			$errors[] = $e -> getMessage();
		}
		try {
			$user -> setSurname($surname);
		} catch (Exception $e) {
			$errors[] = $e -> getMessage();
		}

		if (count($errors) == 0)
		{
			$email 		= $this -> db -> quote($user -> getEmail());
			$hash 		= $this -> db -> quote($user -> getHash());
			$name 		= $this -> db -> quote($user -> getName());
			$surname 	= $this -> db -> quote($user -> getSurname());
			$query 		= 'INSERT INTO user (email, hash, name, surname)
							VALUES ('.$email.','.$hash.','.$name.','.$surname.')';
			$res 		= $this -> db -> exec($query);

			if ($res)
			{
				$id = $this -> db -> lastInsertId();

				if ($id)
				{
					try
					{
						$user = $this -> readById($id);
						return $user;
					}
					catch (Exception $e)
					{
						$errors[] = $e -> getMessage();
						return $errors;
					}
				}
				else
				{
					throw new Exception('Internal server error');
				}
			}
			else
			{
				throw new Exception('Internal server error');
			}
		}
	}


	// Read users
	public function read($n = 0, $s = 'email', $o = 'ASC')
	{
		$n = intval($n);
		$s = $this -> db -> quote($s);
		$o = $this -> db -> quote($o);

		if ($n > 0)
		{
			$query = 'SELECT * FROM user ORDER BY '.$s.' '.$o;
		}
		else
		{
			$query = 'SELECT * FROM user ORDER BY '.$s.' '.$o.' LIMIT '.$n;
		}

		$res = $this -> db -> query($query);

		if ($res)
		{
			$users = $res -> fetchAll(PDO::FETCH_CLASS, 'User', array($this -> db));

			if (is_array($users))
			{
				return $users;
			}
			else
			{
				throw new Exception('Internal server error');
			}
		}
		else
		{
			throw new Exception('Internal server error');
		}
	}


	// Read user by id
	public function readById($id)
	{
		$id 	= intval($id);
		$query 	= 'SELECT * FROM user WHERE id = '.$id;
		$res 	= $this -> db -> query($query);

		if ($res)
		{
			$user = $res -> fetchObject('User', array($this -> db));

			if ($user)
			{
				return $user;
			}
			else
			{
				throw new Exception('Internal server error');
			}
		}
		else
		{
			throw new Exception('Internal server error');
		}
	}


	// Read user by email
	public function readByEmail($email)
	{
		$email 	= $this -> db -> quote($email);
		$query 	= 'SELECT * FROM user WHERE email = '.$email.' ORDER BY email DESC';
		$res 	= $this -> db -> query($query);
		if ($res)
		{
			$users = $res -> fetchObject('User', array($this -> db));
			if($users)
			{
				return $users;
			}
			else
			{
				throw new Exception('Email doesn\'t exist');
			}
		}
		else
		{
			throw new Exception('Internal server error');
		}
	}


	// Read user by name
	public function readByName($name)
	{
		$name 	= $this -> db -> quote($name);
		$query 	= 'SELECT * FROM user WHERE name = '.$name.' ORDER BY name DESC';
		$res 	= $this -> db -> query($query);
		if ($res)
		{
			$users = $res -> fetchAll(PDO::FETCH_CLASS, 'User', array($this -> db));
			return $users;
		}
		else
		{
			throw new Exception('Internal server error');
		}
	}


	// Read user by surname
	public function readBySurname($surname)
	{
		$surname 	= $this -> db -> quote($surname);
		$query 	= 'SELECT * FROM user WHERE surname = '.$surname.' ORDER BY surname DESC';
		$res 	= $this -> db -> query($query);
		if ($res)
		{
			$users = $res -> fetchAll(PDO::FETCH_CLASS, 'User', array($this -> db));
			return $users;
		}
		else
		{
			throw new Exception('Internal server error');
		}
	}


	// Read user by status
	public function readByStatus($status)
	{
		$status = intval($status);
		$query 	= 'SELECT * FROM user WHERE status = '.$status.' ORDER BY email DESC';
		$res 	= $this -> db -> query($query);
		if ($res)
		{
			$users = $res -> fetchAll(PDO::FETCH_CLASS, 'User', array($this -> db));
			return $users;
		}
		else
		{
			throw new Exception('Internal server error');
		}
	}


	// Update user
	public function update(User $user)
	{
		$id 				= intval($user -> getId());
		$email 				= $this -> db -> quote($user -> getEmail());
		$name 				= $this -> db -> quote($user -> getName());
		$surname 			= $this -> db -> quote($user -> getSurname());
		$hash 				= $user -> getHash();
		$status 			= intval($user -> getStatus());
		$query 				= 'UPDATE  user
								SET 	email 			= '.$email.',
										name 			= '.$name.',
										surname 		= '.$surname.',
										`hash` 			= "'.$hash.'",
										`status` 		= '.$status.',
										date_connection = "'.$dateConnection.'"
										WHERE id 	= '.$id;
		$res 				= $this -> db -> exec($query);

		if ($res)
		{
			return $this -> readById($id);
		}
		else
		{
			throw new Exception('Internal server error');
		}
	}


	// Delete user
	public function delete(User $user)
	{
		$id 	= intval($user -> getId());
		$query 	= 'DELETE FROM user WHERE id = '.$id;
		$res 	= $this -> db -> exec($query);
		if ($res)
		{
			return true;
		}
		else
		{
			throw new Exception('Internal server error');
		}
	}
}


?>
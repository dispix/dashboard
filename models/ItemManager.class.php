<?php
class ItemManager
{


		// Properties
		private $db;


		// Constructor
		public function __construct($db)
		{
			$this -> db = $db;
		}


		// Create item
		public function create(Brand $brand, Category $category, $price, $stock, $name, $description)
		{
			$item = new Item($this -> db);
			$errors = array();

			try {
				$item -> setBrand($brand);
			} catch (Exception $e) {
				$errors[] = $e -> getMessage();
			}
			try {
				$item -> setCategory($category);
			} catch (Exception $e) {
				$errors[] = $e -> getMessage();
			}
			try {
				$item -> setPrice($price);
			} catch (Exception $e) {
				$errors[] = $e -> getMessage();
			}
			try {
				$item -> setStock($stock);
			} catch (Exception $e) {
				$errors[] = $e -> getMessage();
			}
			try {
				$item -> setName($name);
			} catch (Exception $e) {
				$errors[] = $e -> getMessage();
			}
			try {
				$item -> setDescription($description);
			} catch (Exception $e) {
				$errors[] = $e -> getMessage();
			}

			if (count($errors) == 0)
			{
				$idBrand 	= intval($item -> getBrand() -> getId());
				$idCategory = intval($item -> getCategory() -> getId());
				$price 		= intval($item -> getPrice());
				$stock 		= intval($item -> getStock());
				$name 		= $this -> db -> quote($item -> getName());
				$surname 	= $this -> db -> quote($item -> getSurname());
				$query 		= 'INSERT INTO item (brand, category, price, stock, name, surname)
								VALUES ('.$idBrand.','.$idCategory.','.$price.','.$stock.','$name.','.$surname.')';
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
			else
			{
				return $errors;
			}
		}


		// Read item by id
		public function readById($id)
		{
			$id 	= intval($id);
			$query 	= 'SELECT * FROM item WHERE id = '.$id;
			$res 	= $this -> db -> query($query);

			if ($res)
			{
				$item = $res -> fetchObject('Item', array($this -> db));
				return $item;
			}
			else
			{
				return null;
			}
		}


		// Read item by brand
		public function readByBrand(Brand $brand)
		{
			$idBrand = intval($brand -> getId());
			$query = 'SELECT * FROM item WHERE id_brand = '.$idBrand;
			$res = $this -> db -> query($query);

			if ($res)
			{
				$items = $res -> fetchAll(PDO::FETCH_CLASS, 'Item', array($this -> db));
				return $items;
			}
			else
			{
				return 'Internal server error';
			}
		}


		// Read item by Category
		public function readByCategory(Category $category)
		{
			$idCategory = intval($category -> getId());
			$query = 'SELECT * FROM item WHERE id_brand = '.$idCategory;
			$res = $this -> db -> query($query);

			if ($res)
			{
				$items = $res -> fetchAll(PDO::FETCH_CLASS, 'Item', array($this -> db));
				return $items;
			}
			else
			{
				return 'Internal server error';
			}
		}


		// Read item by price
		public function readByPrice($min, $max, $n = 0, $o = 'name')
		{
			// Pour plus tard...
		}


		// Read item by stock
		public function readByStock($min, $max, $n = 0, $o = 'name')
		{
			// Pour plus tard...
		}


		// Read item by name
		public function readByName($name, $n = 0, $o = 'name')
		{
			// Pour plus tard...
		}
}
?>
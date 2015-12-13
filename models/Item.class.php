<?php

class Item
{

    private $db;

    private $id;
    private $id_brand;
    private $brand;
    private $id_category;
    private $category;
    private $price;
    private $stock;
    private $name;
    private $description;



    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getBrand()
    {
        if(!$this->brand)
        {
            $brandManager = new BrandManager($this->db);
            $this->brand  = $brandManager->findByid($this->id_brand);
        }
        return $this->brand;
    }
    public function getCategory()
    {
        if(!$this->category)
        {
            $categoryManager = new CategoryManager($this->db);
            $this->category  = $categoryManager->findByid($this->id_category);
        }
        return $this->category;
    }
    public function getPrice()
    {
        return $this->price;
    }
    public function getStock()
    {
        return $this->stock;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getDescription()
    {
        return $this->description;
    }

    public function setBrand(Brand $brand)
    {
        $this->id_brand = $brand->getId();
        $this->brand    = $brand;
        return true;
    }
    public function setCategory(Category $category)
    {
        $this->id_category = $category->getId();
        $this->category    = $category;
        return true;
    }
    public function setPrice($price)
    {
        if(is_numeric($price))
        {
            if($price >= 0)
            {
                $this->price = $price;
            }
            else
            {
                throw new Exception('Price can\'t be negatif');
            }
        }
        else
        {
            throw new Exception('Price invalid format');
        }
    }
    public function setStock($stock)
    {
        $stock = intval($stock);
        if($stock >= 0)
        {
            $this->stock = $stock;
        }
        else
        {
            throw new Exception('Stock can\'t be negatif');
        }
    }
    public function setName($name)
    {
        if(is_string($name))
        {
            if(strlen(trim($name)) >= 1 && strlen(trim($name)) <= 31)
            {
                $this->name = trim($name);
            }
            else
            {
                throw new Exception('Name length is invalid');
            }
        }
        else
        {
            throw new Exception('Name invalid format');
        }
    }
    public function setDescription($description)
    {
        if(is_string($description))
        {
            if(strlen(trim($description)) >= 1 && strlen(trim($description)) <= 1022)
            {
                $this->description = trim($description);
            }
            else
            {
                throw new Exception('Description length is invalid');
            }
        }
        else
        {
            throw new Exception('Description invalid format');
        }
    }


}

?>
<?php

class Category
{

    private $db;

    private $id;
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
    public function getName()
    {
        return $this->name;
    }
    public function getDescription()
    {
        return $this->description;
    }


    public function setName($name)
    {
        if(strlen(trim($name)) >=1 && strlen(trim($name)) <=  31)
        {
            $this->name = $name;
        }
        else
        {
            throw new Exception('Name length is not correct');
        }
    }
    public function setDescription($description)
    {
        if(strlen(trim($description)) <= 1022)
        {
            $this->description = $description;
        }
        else
        {
            throw new Exception('Description is too length');
        }
    }

}


?>
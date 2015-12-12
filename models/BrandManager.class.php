<?php

class BrandManager
{

    private $db;


    public function __construct($db)
    {
        $this->db = $db;
    }


    public function findByid($id)
    {
        $id     = intval($id);
        $query  = "SELECT * FROM brand WHERE id = ".$id;
        $data   = $this->db->query($query);

        if($data)
        {
            $brand = $data->fetchObject('Brand', array($this->db));
            if($brand)
            {
                return $brand;
            }
            else
            {
                throw new Exception('Fetch Object error');
            }
        }
        else
        {
            throw new Exception('Find by id error');
        }
    }


    public function create($name, $description, $errors = array())
    {

        $brand = new Brand($this->db);

        try
        {
            $brand->setName($name);
        }
        catch(Exception $e)
        {
            $errors[] = $e->getMessage();
        }
        try
        {
            $brand->setDescription($description);
        }
        catch(Exception $e)
        {
            $errors[] = $e->getMessage();
        }

        if(count($errors) == 0)
        {
            $name           = $this->db->quote($brand->getName());
            $description    = $this->db->quote($brand->getDescription());
            $query          = "INSERT INTO brand (name, description) VALUES(".$name.", ".$description.")";
            $data           = $this->db->exec($query);

            if($data)
            {
                $id = $this->db->lastInsertId();

                if($id)
                {
                    try
                    {
                        return $brand->findById($id);
                    }
                    catch(Exception $e)
                    {
                        $errors[] = $e->getMessage();
                    }
                }
                else
                {
                    throw new Exception('Last insert error');
                }
            }
            else
            {
                throw new Exception('Insert error');
            }



        }
        else
        {
            return $errors;
        }


    }

}


?>
<?php

class BrandManager
{

    private $db;


    public function __construct($db)
    {
        $this->db = $db;
    }


    /**
     * @param $id
     * @return object
     * @throws Exception
     */
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

    public function findByName($name)
    {
        if(is_string($name))
        {
            $name       = $this->db->quote($name);
            $query      = "SELECT * FROM brand WHERE name =".$name;
            $data       = $this->db->query($query);

            if($data)
            {
                $brand  = $data->fetchObject('Brand', array($this->db));*
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
                throw new Exception('Find by name error');
            }
        }
        else
        {
            throw new Exception('Invalid name format');
        }
    }


    /**
     * @param $word
     * @return array with object
     * @throws Exception
     */
    public function findByWordIntoDescription($word)
    {
        if(is_string($name))
        {
            $word       = $this->db->quote('%'.$word.'%');
            $query      = "SELECT * FROM brand WHERE description LIKE ".$word;
            $data       = $this->db->query($query);

            if($data)
            {
                $brand  = $data->fetchAll(PDO::FETCH_CLASS, 'Brand', array($this->db));

                if($brand)
                {
                    return $brand;
                }
                else
                {
                    throw new Exception('Fetch object error');
                }
            }
            else
            {
                throw new Exception('Find by word error');
            }

        }
        else
        {
            throw new Exception('Invalid word format');
        }
    }


    /**
     * @param $name
     * @param $description
     * @param array $errors
     * @return object
     * @throws Exception
     */
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
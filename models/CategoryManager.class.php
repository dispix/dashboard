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
        $query  = "SELECT * FROM category WHERE id = ".$id;
        $data   = $this->db->query($query);

        if($data)
        {
            $brand = $data->fetchObject('Category', array($this->db));
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
            $query      = "SELECT * FROM category WHERE name =".$name;
            $data       = $this->db->query($query);

            if($data)
            {
                $brand  = $data->fetchObject('Cateogry', array($this->db));
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
    public function findByWordIntoDescription($word, $limit = 0)
    {
        $limit = intval($limit);
        if(is_string($word))
        {
            $word       = $this->db->quote('%'.$word.'%');
            if($limit == 0)
            {
                $query      = "SELECT * FROM category WHERE description LIKE ".$word;
            }
            else
            {
                $query      = "SELECT * FROM category WHERE description LIKE ".$word. "LIMIT ".$limit;
            }
            $data       = $this->db->query($query);

            if($data)
            {
                $brand  = $data->fetchAll(PDO::FETCH_CLASS, 'Category', array($this->db));

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
            $query          = "INSERT INTO category (name, description) VALUES(".$name.", ".$description.")";
            $data           = $this->db->exec($query);

            if($data)
            {
                $id = $this->db->lastInsertId();

                if($id)
                {
                    try
                    {
                        $brand = $this->findById($id);
                        return $brand;
                    }
                    catch(Exception $e)
                    {
                        $errors[] = $e->getMessage();
                        return $errors;
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

    public function update(Brand $brand, $errors = array())
    {
        $id             = $brand->getId();
        $name           = $this->db->quote($brand->getName());
        $description    = $this->db->quote($brand->getDescription());
        $query          = "UPDATE category SET name = ".$name.", description = ".$description."WHERE id = ".$id;
        $data           = $this->db->exec($query);

        if($data)
        {
            $id = $this->db->lastInsertId();
            if($id)
            {
                try
                {
                    $brand = $this->findByid($id);
                    return $brand;
                }
                catch(Exception $e)
                {
                    $errors[] = $e->getMessage();
                    return $errors;
                }
            }
            else
            {
                throw new Exception('Last insert error');
            }
        }
        else
        {
            throw new Exception('Update error');
        }
    }

    public function delete(Brand $brand)
    {
        $query  = "DELETE FROM category WHERE id = ".$brand->getId();
        $data   = $this->db->exec($query);

        if($data)
        {
            return true;
        }
        else
        {
            throw new Exception('Delete error');
        }
    }

}


?>
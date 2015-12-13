<?php

class CategoryManager
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
            $category = $data->fetchObject('Category', array($this->db));
            if($category)
            {
                return $category;
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
                $category  = $data->fetchObject('Cateogry', array($this->db));
                if($category)
                {
                    return $category;
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
                $category  = $data->fetchAll(PDO::FETCH_CLASS, 'Category', array($this->db));

                if($category)
                {
                    return $category;
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

        $category = new Brand($this->db);

        try
        {
            $category->setName($name);
        }
        catch(Exception $e)
        {
            $errors[] = $e->getMessage();
        }
        try
        {
            $category->setDescription($description);
        }
        catch(Exception $e)
        {
            $errors[] = $e->getMessage();
        }

        if(count($errors) == 0)
        {
            $name           = $this->db->quote($category->getName());
            $description    = $this->db->quote($category->getDescription());
            $query          = "INSERT INTO category (name, description) VALUES(".$name.", ".$description.")";
            $data           = $this->db->exec($query);

            if($data)
            {
                $id = $this->db->lastInsertId();

                if($id)
                {
                    try
                    {
                        $category = $this->findById($id);
                        return $category;
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

    public function update(Category $category, $errors = array())
    {
        $id             = $category->getId();
        $name           = $this->db->quote($category->getName());
        $description    = $this->db->quote($category->getDescription());
        $query          = "UPDATE category SET name = ".$name.", description = ".$description."WHERE id = ".$id;
        $data           = $this->db->exec($query);

        if($data)
        {
            $id = $this->db->lastInsertId();
            if($id)
            {
                try
                {
                    $category = $this->findByid($id);
                    return $category;
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

    public function delete(Cateogry $category)
    {
        $query  = "DELETE FROM category WHERE id = ".$category->getId();
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
<?php

/**
 *
 */
class Project
{
    public  $id,
            $title,
            $slug,
            $author,
            $date,
            $description,
            $thumbnail,
            $type,
            $public = true,
            $nb_views = 0,
            $nb_shares = 0,
            $nb_downloads = 0,
            $nb_downloads_limit;

    function __construct($arr = NULL)
    {
        if($arr !== NULL){
            if(!isset($arr['title']))
                throw new Exception("Missing Title", 1);

            if(!isset($arr['date']))
                $this->date = date('Y-m-d', time());

            // Set all attribute from array argument
            foreach ($arr as $key => $value) {
                $this->{$key} = $value;
            }

            // If no author passed in arguments, then author is connected one.
            if(!isset($arr['author'])){
                $user = unserialize($_SESSION['user']);
                $this->author = $user;
            } else {
                $this->author = User::getById($arr['author']);
            }
        }

        $this->slug = String::toAscii($this->title);

        $this->full_url = "projects/$this->id/$this->slug";

        // Get the Author object
        if(is_numeric($this->author)){
            $this->author = User::getById($this->author);
        }

        if($this->id) $this->contentFile = ASSETS_DIR."templates/projects/$this->id.php";
    }

    public function setTitle($value)
    {
        $this->title = $value;
        $this->slug = String::toAscii($this->title);
    }

    static public function getById($id)
    {
        $r = Database::getInstance()->prepare('SELECT * FROM projects WHERE id = :id');
        $r->setFetchMode(PDO::FETCH_CLASS, 'Project');
        $r->bindParam(':id', $id);
        if(!$r->execute()) throw new Exception('DBError : SELECT');
        return $r->fetch();
    }

    public function insert()
    {
        $duplicate = Database::getInstance()->prepare("SELECT id FROM projects WHERE slug = :slug");
        $duplicate->bindParam(':slug', $this->slug);
        if(!$duplicate->execute()) throw new Exception('DBError : SELECT DUPLICATE');
        if($duplicate->fetch()) throw new Exception('DBError : DUPLICATE');

        $r = Database::getInstance()->prepare("INSERT INTO projects (`title`,  `slug`, `author`, `date`, `description`, `thumbnail`,`type`, `public`, `nb_views`, `nb_shares`, `nb_downloads`, nb_downloads_limit)
                                                            VALUES  (:title, :slug,:author,:date,:description,:thumbnail,:type,:public, '0' , '0' , '0',:nb_downloads_limit);");

        $r->bindParam(':title', $this->title);
        $r->bindParam(':slug', $this->slug);
        $r->bindParam(':author', $this->author->id);
        $r->bindParam(':date', $this->date);
        $r->bindParam(':description', $this->description);
        $r->bindParam(':thumbnail', $this->thumbnail);
        $r->bindParam(':type', $this->type);
        $r->bindParam(':public', $this->public);
        $r->bindParam(':nb_downloads_limit', $this->nb_downloads_limit);

        if(!$r->execute()) throw new Exception('DBError : INSERT');

        $this->id = Database::getInstance()->lastInsertId();
        $this->initFileContent();
        return $this->id;
    }

    public function update()
    {
        $duplicate = Database::getInstance()->prepare("SELECT id FROM projects WHERE slug = :slug");
        $duplicate->bindParam(':slug', $this->slug);
        if(!$duplicate->execute()) throw new Exception('DBError : SELECT DUPLICATE');
        if($duplicate->fetch()) throw new Exception('DBError : DUPLICATE');

        $r = Database::getInstance()->prepare("UPDATE projects SET title = :title, slug = :slug, author = :author, projects.date = :date, description = :description, thumbnail = :thumbnail, type = :type, public = :public, nb_views = :nb_views, nb_shares = :nb_shares, nb_downloads = :nb_downloads, nb_downloads_limit = :nb_downloads_limit
                                                            WHERE id = :id");

        $r->bindParam(':id', $this->id);
        $r->bindParam(':title', $this->title);
        $r->bindParam(':slug', $this->slug);
        $r->bindParam(':author', $this->author->id);
        $r->bindParam(':date', $this->date);
        $r->bindParam(':description', $this->description);
        $r->bindParam(':thumbnail', $this->thumbnail);
        $r->bindParam(':type', $this->type);
        $r->bindParam(':public', $this->public);
        $r->bindParam(':nb_views', $this->nb_views);
        $r->bindParam(':nb_shares', $this->nb_shares);
        $r->bindParam(':nb_downloads', $this->nb_downloads);
        $r->bindParam(':nb_downloads_limit', $this->nb_downloads_limit);

        if(!$r->execute()) throw new Exception('DBError : UPDATE');

        return true;
    }

    public function delete()
    {
        $r = Database::getInstance()->prepare('DELETE FROM projects WHERE id = :id');
        $r->bindParam(':id', $this->id);
        if(!$r->execute()) throw new Exception('DBError : DELETE');

        if(file_exists($this->contentFile))
            unlink($this->contentFile);

        return $r->fetch();
    }

    public function initFileContent()
    {
        $this->contentFile = ASSETS_DIR."templates/projects/$this->id.php";

        if(!file_exists($this->contentFile))
            file_put_contents($this->contentFile, null);
    }

    public function gridLayout()
    {
        $template = new Template(ASSETS_DIR.'templates/project.grid.template.php', [
            'project' => $this
        ]);

        return $template->output();
    }
}

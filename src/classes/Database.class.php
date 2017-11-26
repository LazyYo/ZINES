<?php
// require_once 'AppUtil.class.php';

class Database {

    // Instance de la classe PDO
    private $PDOInstance = null;
    public $error = null;

    // Instance de la classe Database
    private static $instance = null;

    // Constante: nom d'utilisateur de la bdd
    const DEFAULT_SQL_USER = 'root';

    // Constante: hôte de la bdd
    const DEFAULT_SQL_HOST = 'localhost';

    // Constante: hôte de la bdd
    const DEFAULT_SQL_PASS = 'root';

    // Constante: port de la bdd
    const DEFAULT_SQL_PORT = 3306;

    // Constante: nom de la bdd
    const DEFAULT_SQL_DTB = 'zines';

    public function __construct(){
        try{
            // Check if database exists. If yes, then connect to instance, else, isntall databases.
            $this->PDOInstance = new PDO("mysql:host=".self::DEFAULT_SQL_HOST.';port='.self::DEFAULT_SQL_PORT, self::DEFAULT_SQL_USER, self::DEFAULT_SQL_PASS);
            $r = $this->PDOInstance->prepare('SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "'.self::DEFAULT_SQL_DTB.'";');
            if(!$r->execute()) throw new Exception('Permission Denied', 403);

            $this->PDOInstance = new PDO('mysql:dbname='.self::DEFAULT_SQL_DTB
                                            .';host='.self::DEFAULT_SQL_HOST
                                            .';port='.self::DEFAULT_SQL_PORT
                                            ,self::DEFAULT_SQL_USER
                                            ,self::DEFAULT_SQL_PASS
                                           );

            $this->PDOInstance->exec("SET NAMES UTF8");
        } catch (Exception $e){
            AppUtil::error([
                'log' => 'Could not connect to database'
            ]);
        }
    }

    static public function Status()
    {
        $PDO = new PDO("mysql:host=".self::DEFAULT_SQL_HOST.';port='.self::DEFAULT_SQL_PORT, self::DEFAULT_SQL_USER, self::DEFAULT_SQL_PASS);
        $o['PDO'] = gettype($PDO) === 'object';
        if(!$o['PDO']) return $o;

        $r = $PDO->prepare('SELECT * FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "'.self::DEFAULT_SQL_DTB.'";');
        if(!$r->execute()) return new Exception("Could not connect to Database", 1);
        $o['Database'] = $r->fetch(PDO::FETCH_ASSOC);

        return $o;
    }

    static public function Install()
    {
        if(!file_exists('install.json'))
            throw new Exception("Database already installed.", 403);

        // Create the database
        $PDO = new PDO("mysql:host=".self::DEFAULT_SQL_HOST.';port='.self::DEFAULT_SQL_PORT, self::DEFAULT_SQL_USER, self::DEFAULT_SQL_PASS);
        $installRequest = $PDO->prepare("CREATE DATABASE IF NOT EXISTS ".self::DEFAULT_SQL_DTB);
        $installRequest->execute();

        if(!self::createUsersTable()) return FALSE;
        if(!self::AddAdminUsers()) return FALSE;
        if(!self::createPostsTable()) return FALSE;
        if(!self::createTagsTable()) return FALSE;
        if(!self::createPostsTagsTable()) return FALSE;
        if(!self::createPagesTable()) return FALSE;

        return unlink('install.json');
    }

    static public function createPagesTable()
    {
        // Create pages table
        $r = Database::getInstance()->prepare("CREATE TABLE `pages` (
            `id` bigint(20) NOT NULL PRIMARY KEY AUTO_INCREMENT,
            `title` varchar(255) NOT NULL,
            `slug` varchar(255) NOT NULL,
            `template` varchar(500) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

            -- Insert data
            INSERT INTO `pages` (`id`, `title`, `slug`, `template`)
            VALUES (1, 'Index', 'index', 'templates/main.php');
        ");
        return $r->execute();
    }

    static public function createUsersTable()
    {
        // Create users table
        $usersRequest = Database::getInstance()->prepare("CREATE TABLE `users` (
          `id` bigint(20) NOT NULL PRIMARY KEY AUTO_INCREMENT,
          `mail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'data/incal.jpg',
          `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
          `last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Membres'");
        return $usersRequest->execute();
    }

    static public function createTagsTable(){
        $createRequest = Database::getInstance()->prepare("CREATE TABLE `tags`
            (`id` int(5) PRIMARY KEY NOT NULL AUTO_INCREMENT,`label` varchar(50) NOT NULL)
            ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
            ALTER TABLE `tags` ADD UNIQUE KEY `label` (`label`);");
        return $createRequest->execute();
    }

    static public function createPostsTagsTable()
    {
        // Create posts table
        $createRequest = Database::getInstance()->prepare("CREATE TABLE `posts_tags` (
          `id` bigint(20) NOT NULL,
          `id_post` bigint(20) NOT NULL,
          `tag` varchar(50) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

          ALTER TABLE `posts_tags`
            ADD PRIMARY KEY (`id`,`id_post`,`tag`),
            ADD KEY `tag` (`tag`),
            ADD KEY `id_post` (`id_post`),
            MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

          ALTER TABLE `posts_tags`
          ADD CONSTRAINT `id_post` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
          ADD CONSTRAINT `tags` FOREIGN KEY (`tag`) REFERENCES `tags` (`label`) ON DELETE CASCADE ON UPDATE CASCADE;
        ");

        return $createRequest->execute();
    }



    static public function AddAdminUsers()
    {
        // Insert the 1st admin user (Yoan Castellani)
        $r = Database::getInstance()->prepare('INSERT INTO users (id, mail, password, avatar, first_name, last_name) VALUES (1, "castellani.yoan@gmail.com", "$2y$10$ZSy71l0rVlySGePqQHIUt.ABmGmuwjVwddH5Cfh0c5SzxCdCnPCR6", "data/incal.jpg", "Yoan", "Castellani"), (NULL, :mail, :password, "data/incal.jpg", "admin", "admin")');
        // $r->execute();
        // Insert the 2nd admin user (The Webmaster)
        $password = password_hash('admin', PASSWORD_DEFAULT);
        $mail = 'admin@admin.com';
        // $r = Database::getInstance()->prepare('INSERT INTO users (id, mail, password, avatar, first_name, last_name) VALUES ');
        $r->bindParam(':mail', $mail);
        $r->bindParam(':password', $password);

        return $r->execute();
    }

    static public function createPostsTable()
    {
        // Create posts table
        $postsRequest = Database::getInstance()->prepare("CREATE TABLE `posts` (
          `id` bigint(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
          `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `highlight` tinyint(1) NOT NULL DEFAULT '0',
          `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `description` text COLLATE utf8_unicode_ci NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Table des posts';");
        return $postsRequest->execute();
    }

    // Crée et retourne l'objet Database
    public static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public static function implodeIN($arr){
        $k = $v = '('; $i = 0;
        foreach ((array)$arr as $key => $value) {
            // Formatting
            if(gettype($value) === 'string') $val = self::getInstance()->quote($value);
            if(gettype($value) === 'boolean') $val = AppUtil::ToString($value);
            if(gettype($value) === 'NULL') $val = 'NULL';

            if(!isset($val)){
                $i++;
                continue;
            }

            $v .= ($i < count((array)$arr) -1 ) ? $val.', ' : $val;
            $k .= ($i < count((array)$arr) -1 ) ? $key.', ' : $key;
            $i++;
        }
        $k .= ')';
        $v .= ')';

        return [
            'keys'   => $k,
            'values' => $v,
        ];
    }

    // Prépare une requête SQL avec PDO
    public function prepare($query, $opts = []){
        return $this->PDOInstance->prepare($query, $opts);
    }

    // Récupère le dernier id inséré en base
    public function lastInsertId(){
        return $this->PDOInstance->lastInsertId();
    }

    // Récupère le dernier id inséré en base
    public function quote($str){
        return $this->PDOInstance->quote($str);
    }

    public function insert($tableName, $values){
        $k = $v = '('; $i = 0;
        foreach ((array)$values as $key => $value) {

            $val = $value;
            if(gettype($value) === 'string'){
                $val = $this->PDOInstance->quote($value);
            }
            if(gettype($value) === 'boolean'){
                $val = AppUtil::ToString($value);
            }
            if(gettype($value) === 'NULL'){
                $val = 'NULL';
            }
            $v .= ($i < count((array)$values) -1 ) ? $val.', ' : $val;
            $k .= ($i < count((array)$values) -1 ) ? $key.', ' : $key;
            $i++;
        }
        $v .= ')'; $k .= ')';

        // echo 'INSERT INTO '.$tableName.' '.$k.' VALUES '.$v;

        $query = self::getInstance()->prepare('INSERT INTO '.$tableName.' '.$k.' VALUES '.$v);
            if(!$query->execute()) throw new Exception("Insertion Error");

        return $this->lastInsertId();
    }

    public function update($tableName, $uid,  $values){
        $val = ''; $i = 0;
        foreach ((array)$values as $key => $value) {

            $val .= ' '.$key.' = ';
            $v = $value;
            if(gettype($value) === 'string'){
                $v = $this->PDOInstance->quote($value);
            }
            if(gettype($value) === 'boolean'){
                $v = AppUtil::ToString($value);
            }
            $val .= $v;
            $val .= ($i < count((array)$values) -1 ) ? ', ' : '';
            $i++;
        }
        // echo 'UPDATE '.$tableName.' SET '.$val.' WHERE id = :id';
        $query = self::getInstance()->prepare('UPDATE '.$tableName.' SET '.$val.' WHERE uid = :uid');
        $query->bindParam(':uid', $uid);
        if(!$query->execute()) return new Exception("Update Error");

        return $uid;
    }

    public function delete($tableName, $uid){
        $query = self::getInstance()->prepare('DELETE FROM '.$tableName.' WHERE uid = :uid');
        $query->bindParam(':uid', $uid);
        if(!$query->execute()) return new Exception("Deletion Error");
        return TRUE;
    }

    static public function getById($table,  $uid){
        $r = Database::getInstance()->prepare('SELECT * FROM '.$table.' WHERE id = :uid');
        $r->bindParam(':uid', $uid);
        if(!$r->execute()) return new Exception('Erreur dans la recherche dans la table '.$table);
        return $r->fetch(PDO::FETCH_ASSOC);
    }

    static public function getPostBySlug($slug){
        $r = Database::getInstance()->prepare('SELECT * FROM posts WHERE slug = :slug');
        $r->bindParam(':slug', $slug);
        if(!$r->execute()) return new Exception('Erreur dans la recherche dans la table '.'posts');
        return $r->fetch(PDO::FETCH_ASSOC);
    }

    static public function Schema($table){
        $r = Database::getInstance()->prepare('DESCRIBE '.$table);
        // $r->bindParam(':table', $table);
        if(!$r->execute()) return new Exception('Erreur dans la récuperation du schéma');
        return $r->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function postHandler($get, $post){
        switch ($get['action']) {
            case 'select':
                if(!isset($get['table']) || !isset($get['uid']))
                    AppUtil::error('Arguments invalide');
                if($get['table'] == 'users')
                    AppUtil::send(self::getUser($get['uid']));
            default:
                break;
        }
    }
}

if(isset($_GET['action']))
    Database::postHandler($_GET, $_POST);

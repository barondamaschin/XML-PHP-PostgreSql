<?php


/**
 * Create table in PostgreSQL 
 */
class curd {

    /**
     * PDO object
     * @var \PDO
     */
    private $pdo;

    /**
     * init the object with a \PDO object
     * @param type $pdo
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * create tables 
     */
    public function createTables() {
        $sqlList = ['CREATE TABLE IF NOT EXISTS author (
                        id serial PRIMARY KEY,
                        name  varchar(255) NOT NULL UNIQUE);',
            'CREATE TABLE IF NOT EXISTS books (
                        id serial PRIMARY KEY,
                        author_id bigserial NOT NULL,
                        bookname varchar(255) NOT NULL UNIQUE );'];

        // execute each sql statement to create new tables
        foreach ($sqlList as $sql) {
            $this->pdo->exec($sql);
        }
        
        return $this;
    }

    /**
     * return tables in the database
     */
    public function getTables() {
        $stmt = $this->pdo->query("SELECT table_name 
                                   FROM information_schema.tables 
                                   WHERE table_schema= 'public' 
                                        AND table_type='BASE TABLE'
                                   ORDER BY table_name");
        $tableList = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $tableList[] = $row['table_name'];
        }

        return $tableList;
    }

    public function insertAuthor($author) {
        // prepare statement for insert
        $sql2 = 'INSERT INTO public.author (name) 
                VALUES(:author) 
                ON CONFLICT  (name) 
                DO 
                UPDATE SET name = EXCLUDED.name 
                RETURNING id, name';
        $stmt = $this->pdo->prepare($sql2);
        
        // pass values to the statement
        $stmt->bindValue(':author', $author);
               
        // execute the insert statement
        $stmt->execute();
        
  
    }

    public function lastID() {
        //return last insert id from author id
        $sql = 'SELECT id, name FROM author ORDER BY id DESC LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);       
        return $result;
    }


     /**
     * Insert multiple authors into the author table
     * @param array $author
     * @return a list of inserted ID
     */
    public function insertAuthors($author) {
        $sql = 'INSERT INTO author(name) VALUES(:author)';
        $stmt = $this->pdo->prepare($sql);
           
        $idList = [];
        foreach ($author as $authors) {
            $stmt->bindValue(':author',  $authors->author );
            $stmt->execute();
            $idList[] = $this->pdo->lastInsertId('author_id_seq');
        }
        return $idList;
    }

    public function insertBook($authid, $title) {

       
        // prepare statement for insert
        $sql = 'INSERT INTO public.books (author_id, bookname) 
                VALUES(:authorid, :bookname) 
                ON CONFLICT  (bookname) 
                DO
                UPDATE SET bookname = EXCLUDED.bookname';
        $stmt = $this->pdo->prepare($sql);
        
        // pass values to the statement
        $stmt->bindValue(':authorid', $authid);
        $stmt->bindValue(':bookname', $title);
               
        // execute the insert statement
        $stmt->execute();
        
        
    }

     /**
     * Return all rows in the author table
     * @return array
     */
    public function getAuthorsTitle() {
        $stmt = $this->pdo->query('SELECT author.name, books.bookname
                                   FROM author 
                                   LEFT JOIN books 
                                   ON author.id = books.author_id ');
             
        $authors = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $authors[] = [
           
                'name' => $row['name'],
                'bookname' => $row['bookname']
            ];
        }
        return $authors;
    }

    

}
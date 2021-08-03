<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/css/bootstrap.css">
    
</head>
<body>
<nav class="navbar navbar-light bg-light">
    
    <a class="nav-link disabled" href="#">Import catalog</a>
    <a class="nav-link " href="search.php" tabindex="-1" aria-disabled="true">Search books catalog</a>
</nav>
    <br>
</body>
</html>
<?php

require_once 'app/connection.php';
require_once 'app/curd.php';

try {
    $pdo = Connection::get()->connect();
    echo 'A connection to the PostgreSQL database sever has been established successfully.<br>';
} catch (\PDOException $e) {
    echo $e->getMessage();
}

try {
    
    // connect to the PostgreSQL database
  //  $pdo = Connection::get()->connect();
    
    // 
    $tableCreator = new curd($pdo);
    
    // create tables and query the table from the
    // database
    $tables = $tableCreator->createTables()
                            ->getTables();
    echo 'Database tables:';
    foreach ($tables as $table){
        
        echo '<li>'. $table . '</li>';
    }
    
} catch (\PDOException $e) {
    echo $e->getMessage();
}

try {
    // connect to the PostgreSQL database
  //  $pdo = Connection::get()->connect();
    // 
    $insertData = new curd($pdo);

    //read from xml file
    echo '<br>';
    if (file_exists('incoming/books.xml')) {
        $xmlf = simplexml_load_file('incoming/books.xml');
    
    // print_r($xmlf);echo '<br>';
        echo '<h3>Xml books.xml content</h3>';
        foreach($xmlf->books as $item){
            printf('<ul><li>'.$item->author.' -'.$item->title.'</li></ul>');
            $author1 = $insertData->insertAuthor($item->author);
            $whatid = $insertData->lastID();
            //echo $whatid;

            //echo $author1;
            $book1 = $insertData->insertBook($whatid, $item->title);
        }
    } else {
        exit('Please provide a books.xml file in folder incoming.');
    }

     // insert a list of authors into the author table
    //  $authorlist = $insertData->insertAuthors($xmlf);
    

    // foreach ($authorlist as $id) {
    //     echo 'The author has been inserted with the id ' . $id . '<br>';
        

    // }
} catch (\PDOException $e) {
    echo $e->getMessage();
}


try{

    $oldname = 'incoming/books.xml';
    $newname = 'processed/books_processed.xml';

if (rename($oldname, $newname)) {
	$message = sprintf(
		'<br>The file %s was renamed to %s successfully!<br>',
		$oldname,
		$newname
	);
} else {
	$message = sprintf(
		'There was an error renaming file %s',
		$oldname
	);
}

}catch (\PDOException $e) {
    echo $e->getMessage();
}


echo $message;


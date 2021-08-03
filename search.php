<?php
require_once 'app/connection.php';
require_once 'app/curd.php';

try {
    // connect to the PostgreSQL database
    $pdo = Connection::get()->connect();
    // 
    $allauth = new curd($pdo);
    // get all stocks     public function getAuthorsTitle() {

    $results = $allauth->getAuthorsTitle();
} catch (\PDOException $e) {
    echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>PostgreSQL PHP Querying Data Demo</title>
        <link rel="stylesheet" href="https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/css/bootstrap.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    </head>
    <body>
    <nav class="navbar navbar-light bg-light">
    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Author List</a>
    <a class="nav-link" href="index.php">Import catalog</a>
    </nav>
        <div class="container">
            <h3>Author List</h3>
          
          <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search .." title="Type in a book"><br>
 
        </form>


          


          <table id="myTable" class="table table-bordered">
                <thead>
                    <tr>
                       
                        <th>Autor Name</th>
                        <th>Book Title</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (!empty($results)){                        
                   
                    foreach ($results as $rowsa) { ?>
                        <tr>
                            
                            <td><?php echo $rowsa['name']; ?></td>
                            <td><?php 
                            if(empty($rowsa['bookname'])){
                                echo 'No books found';
                            } else {
                                echo $rowsa['bookname'];
                            }; ?></td>
                            
                        </tr>
                    <?php } } else{
                        echo '<tr><td>No result returnes. Try another keyword.</td></tr>';
                    }; ?>
                </tbody>
            </table>
        </div>
        <script>
function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}

$("table tr").hide();
$("table tr").each(function(index){
	$(this).delay(index*500).show(1000);
});
</script>
    </body>
</html>
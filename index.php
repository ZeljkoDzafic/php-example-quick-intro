<?php

require "./config/database.php";

$user_id = 1;
$current_category_name = '';
$current_category_id = '';
//$_GET;
//$_POST;
//$_REQUEST;
//
//
//
echo "<pre>";

var_dump($_POST);
var_dump($_REQUEST);
echo "</pre>";
//die();

try {
    $connection = mysqli_connect($host,$user,$password,$db_name,$port);


    if(isset($_POST)){




         if(isset($_POST['category_name']) && isset($_POST['cat_id'])){


             $categry_name = trim($_POST['category_name']);
             $category_id = intval($_POST['cat_id']);

             $sql_update_category = "UPDATE categories  SET name ='{$categry_name}' WHERE id = {$category_id} ;";
             //echo $sql_new_category;

             $result = $connection->query($sql_update_category);


         } else if(isset($_POST['category_name'])){

             $categry_name = trim($_POST['category_name']);
             $sql_new_category = "INSERT INTO categories (name, user_id) value ('".$categry_name."', ".$user_id.");";
             //echo $sql_new_category;

             $result = $connection->query($sql_new_category);
             //$result->close();


         }
    }


    if(isset($_GET)) {


        if(isset($_GET['action'])){

            switch ($_GET['action']) {

                case 'delete':

                        echo "Ovo treba da obrise kategoriju";
                        $sql_category_delete  = "DELETE FROM categories WHERE id = {$_GET['id']};";

                        //echo $sql_category_delete;
                        $result = $connection->query($sql_category_delete);

                        //var_dump($result);

                    break;

                case 'edit':

                    $current_category_select = "SELECT name FROM categories WHERE  id = {$_GET['id']};";

                    $result = $connection->query($current_category_select);
                    $row = $result->fetch_assoc();

                    $current_category_name = $row['name'];
                    $current_category_id = $_GET['id'];
                    //echo "edit_cat";
                    //echo $current_category_name;

                    break;

            }

        }


    }


    $sql_get_user_categories = "SELECT * FROM categories WHERE user_id = '{$user_id}'";

    $categories_result = $connection->query($sql_get_user_categories);

    $categories_data = $categories_result->fetch_all(MYSQLI_BOTH);


    $sql_get_user_tasks = "SELECT tasks.*, categories.name as cat FROM tasks LEFT JOIN categories on tasks.category_id = categories.id WHERE tasks.user_id = '{$user_id}';";

    $tasks_result = $connection->query($sql_get_user_tasks);

    $tasks_data = $tasks_result->fetch_all(MYSQLI_BOTH);


    //var_dump($categories_data);
    //var_dump($tasks_data);
//$connection = new mysqli($host,$user,$password,$db_name,$port);
    //var_dump($connection);
    //$connection->close();
//mysqli_close($nnection);


} catch (Exception $exception) {
    echo "NESTO NE RADI";
    die();
}




?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo List</title>
    <link rel="stylesheet" href="style.css">

    <script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">

        <div class="row">
            <div class="intro col-12">
                <h1>Work To-Dos</h1>
                <div>
                    <div class="border1"></div>
                    <div class="border1"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <h2>Categories</h2>
            <form name="category" method="post" action="index.php">
            <div class="col-12">
                <input id="category_input" name="category_name" type="text" placeholder="New category..." maxlength="27" value="<?php echo $current_category_name; ?>">
                <input type="hidden" name="cat_id" value="<?php echo $current_category_id; ?>"/>
                <button id="enter">ADD</button>
            </div>
            </form>
        </div>

        <div class="row">
            <div class="listItems col-12">
                <ul class="col-12 offset-0 col-sm-8 offset-sm-2">
                    <?php
                        foreach($categories_data as $category) {
                            //var_dump($category);
                            echo "<li>".$category['name']."<span class='actions'><a href='index.php?action=edit&id={$category['id']}'>edit</a> | <a href='index.php?action=delete&id={$category['id']}'>delete</a></span></li>";
                        }
                    ?>

                </ul>
            </div>
        </div>




        <div class="row">
            <div class="helpText col-12">
                <p id="first">Enter text into the input field to add items to your list.</p>
                <p id="second">Click the item to mark it as complete.</p>
                <p id="third">Click the "X" to remove the item from your list.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <input id="userInput" type="text" placeholder="New item..." maxlength="27">
                <button id="enter">ADD</button>
            </div>
        </div>

        <!-- Empty List -->
        <div class="row">
            <div class="listItems col-12">
                <ul class="col-12 offset-0 col-sm-8 offset-sm-2">
                    <?php
                    foreach($tasks_data as $task) {
                        //var_dump($category);
                        echo "<li>".$task['name']."<span> ".$task['cat']." </span> <button>X</button></li>";
                    }
                    ?>
                </ul>
            </div>
        </div>

    </div>
    <script type="text/javascript" src="myScript.js"></script>
</body>

</html>
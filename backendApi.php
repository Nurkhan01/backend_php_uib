<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
$mysqli = new mysqli("localhost", "root", "root", "yii2_uib");

// Подключение к базе
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}
if(isset($_GET['update_id'])){
    $id = $_GET['update_id'];
    if($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $form_data = file_get_contents("php://input");
        $form_data = json_decode($form_data);
        $productName = $form_data->products_name;
        $form_data = json_decode($form_data, true);
        $sql = "UPDATE products SET name= '$productName' WHERE id= $id";
        if ($mysqli->query($sql) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $mysqli->error;
        }
    }
}else {
    // Выполняем запрос и записываем данные в переменную $result
    $result = $mysqli->query("SELECT 
products_category.id products_category_id,
products_category.name products_category_name,
products.id products_id,
products.name products_name,
products.price
FROM products_category 
LEFT JOIN products ON products.category_id = products_category.id");

// Массив который мы вернем, по default пустой
    $returnArray = [];
    if ($result->num_rows > 0) {
        // Итерация по полученным данным из базы
        while ($row = $result->fetch_assoc()) {
            // Добавление данных в массив
            array_push($returnArray, [
                'products_category_id' => $row['products_category_id'],
                'products_category_name' => $row['products_category_name'],
                'products_id' => $row['products_id'],
                'products_name' => $row['products_name'],
                'price' => $row['price'],
            ]);
        }
        echo json_encode($returnArray);
    } else {
        echo "0 results";
    }
}


$mysqli->close();
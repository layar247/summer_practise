<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Tables</title>
    <link rel="stylesheet" href="table.css">
</head>
<body>
    <header>
        <a href="index.php">Запросы в бд</a>
        <a href="showtable.php">Посмотреть бд</a>
    </header>
    <div class="maincontent">
<?php
include 'dbconnect.php';

try {
    // Массив с названиями таблиц
    $tables = array("CLIENTS", "COUNTRIES", "TRIP_PURPOSE", "ROUTES", "TRIPS");

    // Вывод содержимого каждой из указанных таблиц
    foreach ($tables as $table_name) {
        echo "<h2>$table_name</h2>";

        // Получение содержимого таблицы
        $content_query = "SELECT * FROM $table_name";
        $content_result = $db->query($content_query);

        // Вывод содержимого таблицы
        if ($content_result->rowCount() > 0) {
            echo "<table>";
            echo "<tr>";
            $columns = [];
            while ($row = $content_result->fetch(PDO::FETCH_ASSOC)) {
                // Вывод заголовков столбцов
                if (empty($columns)) {
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                        echo "<th>$key</th>";
                        $columns[] = $key;
                    }
                    echo "</tr>";
                }

                // Если таблица ROUTES, заменяем идентификаторы на названия
                if ($table_name === "ROUTES") {
                    $row = replaceIdsWithNames($db, $row, "country_id", "COUNTRIES", "country_name");
                    $row = replaceIdsWithNames($db, $row, "trip_purpose_id", "TRIP_PURPOSE", "purpose_name");
                }

                // Если таблица TRIPS, заменяем идентификатор клиента на ФИО
                if ($table_name === "TRIPS" && isset($row['client_id'])) {
                    $row = replaceClientIdWithName($db, $row);
                }

                // Вывод значений строк
                echo "<tr>";
                foreach ($columns as $column) {
                    echo "<td>" . $row[$column] . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Table is empty.";
        }
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Функция для замены идентификаторов на названия в таблице ROUTES
function replaceIdsWithNames($db, $row, $id_column, $table_name, $name_column) {
    $id = $row[$id_column];
    $query = "SELECT $name_column FROM $table_name WHERE id = :id";
    $statement = $db->prepare($query);
    $statement->bindParam(":id", $id);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $name = $result[$name_column];
    $row[$id_column] = $name;
    return $row;
}

// Функция для замены идентификатора клиента на ФИО в таблице TRIPS
function replaceClientIdWithName($db, $row) {
    $client_id = $row['client_id'];
    $query = "SELECT full_name FROM CLIENTS WHERE id = :id";
    $statement = $db->prepare($query);
    $statement->bindParam(":id", $client_id);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $full_name = $result['full_name'];
    $row['client_id'] = $full_name;
    return $row;
}
?>
</div>
</body>
</html>

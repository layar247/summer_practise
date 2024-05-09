<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Query Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        .maincontent {
            padding: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }
        header {
            background-color: #007bff; /* Зеленый цвет */
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        header a {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            text-decoration: none; /* Убираем подчеркивание */
            transition: background-color 0.3s; /* Анимация при наведении */
        }
        header a:hover {
            background-color: #175699; /* Цвет фона при наведении */
        }

    </style>
</head>
<body>
    <header>
        <a href="index.php">Запросы в бд</a>
        <a href="showtable.php">Посмотреть бд</a>
    </header>
    <div class="maincontent">
<?php

include("dbconnect.php");

$query = $_POST['query'];

if ($query === "1") {
   
    $country = $_POST['country'];
    
    $sql_query = "SELECT * FROM ROUTES WHERE country_id = (SELECT id FROM COUNTRIES WHERE country_name = ?)";

    $stmt = $db->prepare($sql_query);
    $stmt->execute([$country]);

    $result = $stmt->fetchAll();

    $sql_query = "SELECT * FROM TRIP_PURPOSE";
    $stmt = $db->prepare($sql_query);
    $stmt->execute();

    $trip_purposes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $trip_purpose_names = [];
    foreach ($trip_purposes as $purpose) {
        $trip_purpose_names[$purpose['id']] = $purpose['purpose_name'];
    }

    echo "<table>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>Страна</th>";
    echo "<th>Цена за 1 день</th>";
    echo "<th>Стоимость транспортных услуг</th>";
    echo "<th>Цель поездки</th>";
    echo "</tr>";
    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</th>";
        echo "<td>" . $country . "</td>";
        echo "<td>" . $row['cost_per_day'] . "</td>";
        echo "<td>" . $row['transport_cost'] . "</td>";
        echo "<td>" . $trip_purpose_names[$row['trip_purpose_id']] . "</td>"; 
        echo "</tr>";
    }
    echo "</table>";
    
} elseif ($query === "2") {

    $sql_query = "SELECT * FROM COUNTRIES";
    $stmt = $db->prepare($sql_query);
    $stmt->execute();
    $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql_query = "SELECT * FROM TRIP_PURPOSE";
    $stmt = $db->prepare($sql_query);
    $stmt->execute();
    $trip_purposes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $country_names = [];
    foreach ($countries as $country) {
        $country_names[$country['id']] = $country['country_name'];
    }

    $purpose_names = [];
    foreach ($trip_purposes as $purpose) {
        $purpose_names[$purpose['id']] = $purpose['purpose_name'];
    }

    $trip_purpose = $_POST['trip_purpose'];
    $max_cost = $_POST['max_cost'];

    $sql_query = "SELECT * FROM ROUTES WHERE trip_purpose_id = (SELECT id FROM TRIP_PURPOSE WHERE purpose_name = ?) AND cost_per_day <= ?";
    $stmt = $db->prepare($sql_query);
    $stmt->execute([$trip_purpose, $max_cost]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<table>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>Страна</th>";
    echo "<th>Цена за 1 день</th>";
    echo "<th>Стоимость транспортных услуг</th>";
    echo "<th>Цель поездки</th>";
    echo "</tr>";

    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</th>";
        echo "<td>" . $country_names[$row['country_id']] . "</td>";
        echo "<td>" . $row['cost_per_day'] . "</td>";
        echo "<td>" . $row['transport_cost'] . "</td>";
        echo "<td>" . $purpose_names[$row['trip_purpose_id']] . "</td>";
        echo "</tr>";
    }

    echo "</table>";


} elseif ($query === "3") {
    
    $year = $_POST['year'];
    
    $sql_query = "SELECT * FROM CLIENTS WHERE id IN (SELECT client_id FROM TRIPS WHERE YEAR(start_date) = ?)";

    $stmt = $db->prepare($sql_query);
    $stmt->execute([$year]);

    $result = $stmt->fetchAll();

    echo "<table>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>ФИО</th>";
    echo "<th>Паспортные данные</th>";
    echo "</tr>";
    
    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['full_name'] . "</td>";
        echo "<td>" . $row['passport_data'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";

} elseif ($query === "4") {
    
    $trip_purpose = $_POST['trip_purpose'];
    
    $sql_query = "SELECT * FROM ROUTES WHERE trip_purpose_id = (SELECT id FROM TRIP_PURPOSE WHERE purpose_name = ?)";

    $stmt = $db->prepare($sql_query);
    $stmt->execute([$trip_purpose]);

    $result = $stmt->fetchAll();

    $sql_query = "SELECT * FROM COUNTRIES";
    $stmt = $db->prepare($sql_query);
    $stmt->execute();
    $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $country_names = [];
    foreach ($countries as $country) {
        $country_names[$country['id']] = $country['country_name'];
    }

    echo "<table>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>Страна</th>";
    echo "<th>Цена за 1 день</th>";
    echo "<th>Стоимость транспортных услуг</th>";
    echo "<th>Цель поездки</th>";
    echo "</tr>";
    
    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $country_names[$row['country_id']] . "</td>";
        echo "<td>" . $row['cost_per_day'] . "</td>";
        echo "<td>" . $row['transport_cost'] . "</td>";
        echo "<td>" . $trip_purpose . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";

} elseif ($query === "5") {
    
    $min_days = $_POST['min_days'];
    $max_days = $_POST['max_days'];

    $sql_query = "SELECT 
    CLIENTS.full_name, 
    COUNTRIES.country_name, 
    TRIP_PURPOSE.purpose_name, 
    TRIPS.start_date, 
    TRIPS.duration_days
    FROM 
        CLIENTS 
    JOIN 
        TRIPS ON CLIENTS.id = TRIPS.client_id 
    JOIN 
        ROUTES ON TRIPS.route_id = ROUTES.id
    JOIN 
        COUNTRIES ON ROUTES.country_id = COUNTRIES.id
    JOIN 
        TRIP_PURPOSE ON ROUTES.trip_purpose_id = TRIP_PURPOSE.id 
    WHERE 
        TRIPS.duration_days BETWEEN ? AND ?;";

    $stmt = $db->prepare($sql_query);
    $stmt->execute([$min_days, $max_days]);

    $result = $stmt->fetchAll();

    echo "<table>";
    echo "<tr>";
    echo "<th>ФИО</th>";
    echo "<th>Страна назначения</th>";
    echo "<th>Цель поездки</th>";
    echo "<th>День отъезда</th>";
    echo "<th>Кол-во дней пребывания</th>";
    echo "</tr>";
    
    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>" . $row['full_name'] . "</td>";
        echo "<td>" . $row['country_name'] . "</td>";
        echo "<td>" . $row['purpose_name'] . "</td>";
        echo "<td>" . $row['start_date'] . "</td>";
        echo "<td>" . $row['duration_days'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";

} elseif ($query === "6") {
    
    $destination_country = $_POST['destination_country'];
    $trip_purpose = $_POST['trip_purpose'];
    $start_date = $_POST['start_date'];
    $stay_duration = $_POST['stay_duration'];

    $sql_query = "SELECT
    r.cost_per_day AS Cost_Per_Day,
    r.transport_cost AS Transport_Cost,
    c.visa_processing_fee AS Visa_Processing_Fee,
    c.country_name AS Country_Name,
    tp.purpose_name AS Trip_Purpose
    FROM 
        ROUTES r
    JOIN 
        COUNTRIES c ON r.country_id = c.id
    JOIN
        TRIP_PURPOSE tp ON r.trip_purpose_id = tp.id
    WHERE 
        r.country_id = ? AND
        r.trip_purpose_id = ?;
    ";
    
    $stmt = $db->prepare($sql_query);
    $stmt->execute([$destination_country, $trip_purpose]);
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $country_name = $result['Country_Name'];
        $purpose_name = $result['Trip_Purpose'];
        $cost_per_day = $result['Cost_Per_Day'];
        $transport_cost = $result['Transport_Cost'];
        $visa_processing_fee = $result['Visa_Processing_Fee'];
        
        $total_cost_without_vat = $cost_per_day * $stay_duration + $transport_cost + $visa_processing_fee;
    
        echo "<table>";
        echo "<tr>";
        echo "<th>Страна назначения</th>";
        echo "<th>Цель поездки</th>";
        echo "<th>День отъезда</th>";
        echo "<th>Кол-во дней пребывания</th>";
        echo "<th>Общая цена (без НДС)</th>";
        echo "</tr>";
    
        echo "<tr>";
        echo "<td>" . $country_name . "</td>";
        echo "<td>" . $purpose_name . "</td>";
        echo "<td>" . $start_date . "</td>";
        echo "<td>" . $stay_duration . "</td>";
        echo "<td>" . $total_cost_without_vat . "</td>";
        echo "</tr>";
    
        echo "</table>";
    } else {
        echo "Страна не найдена в базе данных.";
    }
    
} elseif ($query === "7") {

    $sql_query = "SELECT
    c.country_name AS Destination_Country,
    AVG(r.cost_per_day) AS Average_Cost_Per_Day
    FROM 
        ROUTES r
    JOIN 
        COUNTRIES c ON r.country_id = c.id
    GROUP BY 
        c.country_name;
    ";

    $stmt = $db->prepare($sql_query);
    $stmt->execute();

    $result = $stmt->fetchAll();

    echo "<table>";
    echo "<tr>";
    echo "<th>Страна назначения</th>";
    echo "<th>Средняя цена за 1 день пребывания</th>";
    echo "</tr>";

    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>" . $row['Destination_Country'] . "</td>";
        echo "<td>" . $row['Average_Cost_Per_Day'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";

} elseif ($query === "8") {
    
    $sql_query = "SELECT
    c.country_name AS Destination_Country,
    MIN(r.transport_cost) AS Min_Transport_Cost,
    MAX(r.transport_cost) AS Max_Transport_Cost
    FROM 
        ROUTES r
    JOIN 
        COUNTRIES c ON r.country_id = c.id
    GROUP BY 
        c.country_name;
    ";

    $stmt = $db->prepare($sql_query);
    $stmt->execute();

    $result = $stmt->fetchAll();

    echo "<table>";
    echo "<tr>";
    echo "<th>Страна назначения</th>";
    echo "<th>Минимальная стоимость транспортных услуг</th>";
    echo "<th>Максимальная стоимость транспортных услуг</th>";
    echo "</tr>";

    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>" . $row['Destination_Country'] . "</td>";
        echo "<td>" . $row['Min_Transport_Cost'] . "</td>";
        echo "<td>" . $row['Max_Transport_Cost'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
}
?>
</div>
</body>
</html>
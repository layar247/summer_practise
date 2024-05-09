<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Database</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <a href="index.php">Запросы в бд</a>
        <a href="showtable.php">Посмотреть бд</a>
    </header>

    <h1>Travel Database</h1>

    <form action="result.php" method="post">
        <label for="query">Выберите запрос:</label>
        <select name="query" id="query">
            <option value="0" disabled selected>Выберите запрос</option>
            <option value="1">1. Показать маршруты в конкретную страну</option>
            <option value="2">2. Показать маршруты, не дороже n рублей с заданной целью поездки.</option>
            <option value="3">3. Показать клиентов, совершивших поездки в течение некоторого года</option>
            <option value="4">4. Показать маршруты с некоторой целью поездки</option>
            <option value="5">5. Показать поездки, где количество дней пребывания из диапазона</option>
            <option value="6">6. Показать стоимость поездок без НДС</option>
            <option value="7">7. Показать среднюю стоимость 1 дня пребывания</option>
            <option value="8">8. Показать минимальную и максимальную стоимость транспортных услуг</option>
        </select>
        <br>

        <?php
        include("dbconnect.php");

        // 1
        $sql_query = "SELECT * FROM COUNTRIES";
        $stmt = $db->prepare($sql_query);
        $stmt->execute();
        $countries = $stmt->fetchAll();

        // 2
        $sql_query = "SELECT * FROM TRIP_PURPOSE";
        $stmt = $db->prepare($sql_query);
        $stmt->execute();
        $purposes = $stmt->fetchAll();

        // 3 не надо, пользователь сам введёт год

        // 4
        $sql_query = "SELECT * FROM TRIP_PURPOSE";
        $stmt = $db->prepare($sql_query);
        $stmt->execute();
        $purposes = $stmt->fetchAll();

        // 5
        $sql_query = "SELECT DISTINCT duration_days FROM TRIPS";
        $stmt = $db->prepare($sql_query);
        $stmt->execute();
        $min_days = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // 6
        $sql_query = "SELECT DISTINCT country_id FROM ROUTES";
        $stmt = $db->prepare($sql_query);
        $stmt->execute();
        $destination_countries = $stmt->fetchAll(PDO::FETCH_COLUMN);
        ?>

        <div id="inputs">
        </div>



        <input type="submit" name="submit" value="submit">
    </form>

    <script>
        document.getElementById("query").addEventListener("change", function() {
            var query = this.value;
            var inputsDiv = document.getElementById("inputs");
            inputsDiv.innerHTML = "";

            if (query === "1") {
                inputsDiv.innerHTML = `
                    <label for="country">Страна:</label>
                    <select name="country" id="country" required>
                        <?php foreach ($countries as $country): ?>
                            <option value="<?php echo $country['country_name']; ?>"><?php echo $country['country_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br>
                `;
            } else if (query === "2") {
                inputsDiv.innerHTML = `
                    <label for="trip_purpose">Цель поездки:</label>
                    <select name="trip_purpose" id="trip_purpose" required>
                        <?php foreach ($purposes as $purpose): ?>
                            <option value="<?php echo $purpose['purpose_name']; ?>"><?php echo $purpose['purpose_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br>
                    <label for="max_cost">Максимальная цена за 1 день пребывания:</label>
                    <input type="number" name="max_cost" id="max_cost" min="0" required>
                    <br>
                `;
            } else if (query === "3") {
                inputsDiv.innerHTML = `
                    <label for="year">Год поездки:</label>
                    <input type="number" name="year" id="year" min="1900" max="2100" required>
                    <br>
                `;
            } else if (query === "4") {
                inputsDiv.innerHTML = `
                    <label for="trip_purpose">Цель поездки:</label>
                    <select name="trip_purpose" id="trip_purpose" required>
                        <?php foreach ($purposes as $purpose): ?>
                            <option value="<?php echo $purpose['purpose_name']; ?>"><?php echo $purpose['purpose_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br>
                `;
            } else if (query === "5") {
                inputsDiv.innerHTML = `
                    <label for="min_days">Нижний диапазон:</label>
                    <input type="number" name="min_days" id="min_days" min="0" required>
                    <br>
                    <label for="max_days">Верхний диапазон:</label>
                    <input type="number" name="max_days" id="max_days" min="0" required>
                    <br>
                `;
            } else if (query === "6") {
                inputsDiv.innerHTML = `
                    <label for="destination_country">Страна назначения:</label>
                    <select name="destination_country" id="destination_country" required>
                        <?php foreach ($countries as $country): ?>
                            <option value="<?php echo $country['id']; ?>"><?php echo $country['country_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br>
                    <label for="trip_purpose">Цель поездки:</label>
                    <select name="trip_purpose" id="trip_purpose" required>
                        <?php foreach ($purposes as $purpose): ?>
                            <option value="<?php echo $purpose['id']; ?>"><?php echo $purpose['purpose_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br>
                    <label for="start_date">Дата начала поездки:</label>
                    <input type="date" name="start_date" id="start_date" required>
                    <br>
                    <br>
                    <label for="stay_duration">Количество дней пребывания:</label>
                    <input type="number" name="stay_duration" id="stay_duration" min="0" required>
                    <br>
                `;
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
        var query = document.getElementById("query").value;
        var inputsDiv = document.getElementById("inputs");

        if (query === "1") {
            inputsDiv.innerHTML = `
                <label for="country">Страна:</label>
                <select name="country" id="country" required>
                    <?php foreach ($countries as $country): ?>
                        <option value="<?php echo $country['country_name']; ?>"><?php echo $country['country_name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <br>
            `;
        } else if (query === "2") {
            inputsDiv.innerHTML = `
                <label for="trip_purpose">Цель поездки:</label>
                <select name="trip_purpose" id="trip_purpose" required>
                    <?php foreach ($purposes as $purpose): ?>
                        <option value="<?php echo $purpose['purpose_name']; ?>"><?php echo $purpose['purpose_name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <br>
                <label for="max_cost">Максимальная цена за 1 день пребывания:</label>
                <input type="number" name="max_cost" id="max_cost" min="0" required>
                <br>
            `;
        } else if (query === "3") {
            inputsDiv.innerHTML = `
                <label for="year">Год поездки:</label>
                <input type="number" name="year" id="year" min="1900" max="2100" required>
                <br>
            `;
        } else if (query === "4") {
            inputsDiv.innerHTML = `
                <label for="trip_purpose">Цель поездки:</label>
                <select name="trip_purpose" id="trip_purpose" required>
                    <?php foreach ($purposes as $purpose): ?>
                        <option value="<?php echo $purpose['purpose_name']; ?>"><?php echo $purpose['purpose_name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <br>
            `;
        } else if (query === "5") {
            inputsDiv.innerHTML = `
                <label for="min_days">Нижний диапазон:</label>
                <input type="number" name="min_days" id="min_days" min="0" required>
                <br>
                <label for="max_days">Верхний диапазон:</label>
                <input type="number" name="max_days" id="max_days" min="0" required>
                <br>
            `;
        } else if (query === "6") {
            inputsDiv.innerHTML = `
                <label for="destination_country">Страна назначения:</label>
                <select name="destination_country" id="destination_country" required>
                    <?php foreach ($countries as $country): ?>
                        <option value="<?php echo $country['id']; ?>"><?php echo $country['country_name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <br>
                <label for="trip_purpose">Цель поездки:</label>
                <select name="trip_purpose" id="trip_purpose" required>
                    <?php foreach ($purposes as $purpose): ?>
                        <option value="<?php echo $purpose['id']; ?>"><?php echo $purpose['purpose_name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <br>
                <label for="start_date">Дата начала поездки:</label>
                <input type="date" name="start_date" id="start_date" required>
                <br>
                <br>
                <label for="stay_duration">Количество дней пребывания:</label>
                <input type="number" name="stay_duration" id="stay_duration" min="0" required>
                <br>
            `;
        }
    });

    </script>
</body>
</html>

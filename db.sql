CREATE TABLE CLIENTS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    passport_data VARCHAR(255) NOT NULL
);

CREATE TABLE COUNTRIES (
    id INT AUTO_INCREMENT PRIMARY KEY,
    country_name VARCHAR(255) NOT NULL,
    visa_processing_fee DECIMAL(10, 2) NOT NULL
);

CREATE TABLE TRIP_PURPOSE (
    id INT AUTO_INCREMENT PRIMARY KEY,
    purpose_name VARCHAR(255) NOT NULL
);

CREATE TABLE ROUTES (
    id INT AUTO_INCREMENT PRIMARY KEY,
    country_id INT,
    trip_purpose_id INT,
    cost_per_day DECIMAL(10, 2) NOT NULL,
    transport_cost DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (country_id) REFERENCES COUNTRIES(id),
    FOREIGN KEY (trip_purpose_id) REFERENCES TRIP_PURPOSE(id)
);

CREATE TABLE TRIPS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT,
    route_id INT,
    start_date DATE NOT NULL,
    duration_days INT NOT NULL,
    trip_year INT,
    FOREIGN KEY (client_id) REFERENCES CLIENTS(id),
    FOREIGN KEY (route_id) REFERENCES ROUTES(id)
);

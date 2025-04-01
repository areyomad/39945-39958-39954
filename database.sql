CREATE DATABASE uzytkownicy;

USE uzytkownicy;

CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       login VARCHAR(255) NOT NULL UNIQUE,
                       haslo VARCHAR(255) NOT NULL
);

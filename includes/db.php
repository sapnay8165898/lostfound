<?php
/**
 * Database Connection
 * ----------------------------------------------------------
 * Creates a single $conn (mysqli connection) that other
 * pages can include whenever they need to talk to MySQL.
 *
 * Always use prepared statements with this connection
 * to prevent SQL injection.
 * ----------------------------------------------------------
 */

// ---- Database configuration ----
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';                 // XAMPP default: empty password
$DB_NAME = 'lostfound_db';

// ---- Create the connection ----
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// ---- Check connection ----
if ($conn->connect_error) {
    // Stop everything and show an error (during development)
    die('Database connection failed: ' . $conn->connect_error);
}

// ---- Set charset to utf8mb4 (supports emojis + all languages) ----
$conn->set_charset('utf8mb4');

// Note: $conn is now available to any file that does:
//     require_once __DIR__ . '/db.php';
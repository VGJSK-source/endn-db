<?php
// Include database configuration
require_once ('./config/database.php');

// Include encryption functions
require_once ('./lib/encryption.php');

// Generate encryption key
$encryptionKey = generateEncryptionKey(32); // Adjust the length as needed

// Sample data to be inserted into the database
$sampleData = [
    'Alice' => 'alice@example.com',
    'Bob' => 'bob@example.com',
    'Charlie' => 'charlie@example.com'
];

/// Connect to the database
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully to database<br>";

    // Insert sample data into database
    foreach ($sampleData as $name => $email) {
        // Encrypt name and email before inserting into the database
        $encryptedName = encryptData($name, $encryptionKey);
        $encryptedEmail = encryptData($email, $encryptionKey);

        $statement = $pdo->prepare("INSERT INTO endntable (name, encrypted_name, encrypted_email) VALUES (:name, :encryptedName, :encryptedEmail)");
        $statement->execute(['name' => $name, 'encryptedName' => $encryptedName, 'encryptedEmail' => $encryptedEmail]);
    }
    echo "Sample data inserted into database successfully<br>";

    // Retrieve encrypted data from database
    $statement = $pdo->prepare("SELECT name, encrypted_name, encrypted_email FROM endntable");
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        // Decrypt name and email retrieved from database
        $decryptedName = decryptData($row['encrypted_name'], $encryptionKey);
        $decryptedEmail = decryptData($row['encrypted_email'], $encryptionKey);
        echo "Name: $decryptedName, Decrypted Email: $decryptedEmail<br>";
    }

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>
<?php
$host = 'localhost'; 
$dbname = 'nfive'; 
$user = 'jiye'; 
$pass = 'jiye';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

$sql = 'SELECT Name, Species, Friendship, Decoration FROM data';
$stmt = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Display</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Data Listing from Table 'data1'</h1>
    <table class="half-width-left-align">
        <thead>
            <tr>
                <!-- <th>Entry ID</th> -->
                <th>Name</th>
                <th>Species</th>
                <th>Friendship</th>
                <th>Decoration</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch()): ?>
            <tr>
                <!-- <td><?php echo htmlspecialchars($row['entry_id']); ?></td> -->
                <td><?php echo htmlspecialchars($row['Name']); ?></td> 
                <td><?php echo htmlspecialchars($row['Species']); ?></td>
                <td><?php echo htmlspecialchars($row['Friendship']); ?></td>
                <td><?php echo htmlspecialchars($row['Decoration']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

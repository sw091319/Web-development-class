<?php
$host = 'localhost'; 
$dbname = 'pikmin'; 
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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vname']) && isset($_POST['species']) && isset($_POST['friendship']) && isset($_POST['decoration'])) {
    $field1 = htmlspecialchars($_POST['vname']);
    $field2 = htmlspecialchars($_POST['species']);
    $field3 = htmlspecialchars($_POST['friendship']);
    $field4 = htmlspecialchars($_POST['decoration']);
    
    $insert_sql = 'INSERT INTO vdata (vname, species, friendship, decoration) VALUES (:vname, :species, :friendship, :decoration)';
    $stmt_insert = $pdo->prepare($insert_sql);
    $stmt_insert->execute(['vname' => $field1, 'species' => $field2, 'friendship' => $field3, 'decoration' => $field4]);
}

$sql = 'SELECT vname, species, friendship, decoration FROM vdata';
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
    <h2>Add New Pikmin</h2>
    <form action="index2.php" method="post">
        <label for="vname">Name:</label>
        <input type="text" id="vname" name="vname" required>
        <br><br>
        
        <label for="species">Species:</label>
        <div>Red <input type="radio" id="species" name="species" value="Red" required/> </div>
        <div>Yellow <input type="radio" id="species" name="species" value="Yellow"/></div>
        <div>Blue <input type="radio" id="species" name="species" value="Blue"/></div>
        <div>Purple <input type="radio" id="species" name="species" value="Purple"/></div>
        <div>White <input type="radio" id="species" name="species" value="White"/></div>
        <div>Winged <input type="radio" id="species" name="species" value="Winged"/></div>
        <div>Rock <input type="radio" id="species" name="species" value="Rock"/></div>
        <br><br>

        <label for="friendship">Friendship:</label>
          <select id="friendship" name="friendship" required>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>
        <br><br>

        <label for="decoration">Decor Pikmin:</label>
        <div>Yes <input type="radio" id="decoration" name="decoration" value="Yes" required/> </div>
        <div>No <input type="radio" id="decoration" name="decoration" value="No" required/> </div>
        <br><br>
        <input type="submit" value="Add Pikmin">
    </form>

    <h2>Data Listing from Table 'vdata'</h2>
    <table class="half-width-left-align">
        <thead>
            <tr>
                <th>Name</th>
                <th>Species</th>
                <th>Friendship</th>
                <th>Decor Pikmin</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['vname']); ?></td>
                <td><?php echo htmlspecialchars($row['species']); ?></td>
                <td><?php echo htmlspecialchars($row['friendship']); ?></td>
                <td><?php echo htmlspecialchars($row['decoration']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

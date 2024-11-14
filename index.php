<?php
$host = 'localhost';
$dbname = 'product';
$user = 'root';
$pass = 'mysql';
$charset = 'utf8mb4';
//Hello
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

// Search
$search_product = null;
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = '%' . $_GET['search'] . '%';
    $search_sql = 'SELECT id, name, count, cost FROM product WHERE name LIKE :search';
    $search_stmt = $pdo->prepare($search_sql);
    $search_stmt->execute(['search' => $search_term]);
    $search_product = $search_stmt->fetchAll();
}

// Insert or delete
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['name']) && isset($_POST['count']) && isset($_POST['cost'])) {
        // Insert new entry
        $name = htmlspecialchars($_POST['name']);
        $count = htmlspecialchars($_POST['count']);
        $cost = htmlspecialchars($_POST['cost']);
        
        $insert_sql = 'INSERT INTO product (name, count, cost) VALUES (:name, :count, :cost)';
        $stmt_insert = $pdo->prepare($insert_sql);
        $stmt_insert->execute(['name' => $name, 'count' => $count, 'cost' => $cost]);
    } elseif (isset($_POST['delete_id'])) {
        // Delete an entry
        $delete_id = (int) $_POST['delete_id'];
        
        $delete_sql = 'DELETE FROM product WHERE id = :id';
        $stmt_delete = $pdo->prepare($delete_sql);
        $stmt_delete->execute(['id' => $delete_id]);
    }
}

// Select all
$sql = 'SELECT id, name, count, cost FROM product';
$stmt = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management System</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Product Management System</h1>
    </header>
    <hr>

    <div class="search">
        <div class="search_header">
            <h2>Search Products</h2>
            <form action="index.php" method="get">
                <input type="text" id="search" name="search" placeholder="Input the name" require>
                <input type="submit" value="Search">
            </form>
        </div>
        <div class="search_results">
            <h3>Search Results</h3>
            <?php if (isset($search_product) && count($search_product) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Count</th>
                            <th>Cost</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($search_product as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['count']); ?></td>
                                <td><?php echo htmlspecialchars($row['cost']); ?></td>
                                <td>
                                    <form action="index.php" method="post">
                                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                        <input type="submit" value="Delete">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Count</th>
                            <th>Cost</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $stmt->fetch()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['count']); ?></td>
                            <td><?php echo htmlspecialchars($row['cost']); ?></td>
                            <td>
                                <form action="index.php" method="post">
                                    <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                    <input type="submit" value="Delete">
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <div class="devide_container">
        <div class="add_product">
            <h2>Add New Product</h2>
            <form action="index.php" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                <br><br>
                <label for="count">Count:</label>
                <input type="number" id="count" name="count" required>
                <br><br>
                <label for="cost">Cost:</label>
                <input type="number" step="0.01" id="cost" name="cost" required>
                <br><br>
                <input type="submit" value="Add Product">
            </form>
        </div>

        <div class="product_list">
            <h2>Product Listing</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Count</th>
                        <th>Cost</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $stmt = $pdo->query($sql); ?>
                    <?php while ($row = $stmt->fetch()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['count']); ?></td>
                        <td><?php echo htmlspecialchars($row['cost']); ?></td>
                        <td>
                            <form action="index.php" method="post">
                                <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                <input type="submit" value="Delete">
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>

<?php
// world.php - country / city lookup service for the "world" database

$host     = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname   = 'world';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo '<p>Database connection failed.</p>';
    exit;
}

$country = isset($_GET['country']) ? trim($_GET['country']) : '';
$lookup  = isset($_GET['lookup'])  ? $_GET['lookup']        : 'country';

$like = '%' . $country . '%';

if ($lookup === 'cities') {
    $stmt = $conn->prepare(
        "SELECT c.name, c.district, c.population
         FROM cities c
         JOIN countries cs ON c.country_code = cs.code
         WHERE cs.name LIKE :country
         ORDER BY c.name"
    );
    $stmt->execute([':country' => $like]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <table>
        <thead>
            <tr><th>Name</th><th>District</th><th>Population</th></tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['district']) ?></td>
                <td><?= htmlspecialchars($row['population']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php
} else {
    $stmt = $conn->prepare("SELECT * FROM countries WHERE name LIKE :country");
    $stmt->execute([':country' => $like]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Continent</th>
                <th>Independence</th>
                <th>Head of State</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['continent']) ?></td>
                <td><?= htmlspecialchars((string)$row['independence_year']) ?></td>
                <td><?= htmlspecialchars((string)$row['head_of_state']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php
}

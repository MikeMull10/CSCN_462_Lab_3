<?php
	//phpinfo();
	$db_host = '127.0.0.1';
	$db_name = 'AD';
	$db_user = 'root';
	$db_pass = '';

	$dsn = "mysql:host={$db_host};dbname={$db_name};port=3306";

	try {
		$pdo = new PDO($dsn, $db_user, $db_pass);
		//echo "Connected";
	} catch (PDOException $e) {
		die("DB connection failed: " . htmlspecialchars($e->getMessage()));
	}

function e($s) {
	return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>

<!doctype html>
<html>
<body>
<form method="POST" action="">
	<input type="text" name="username" placeholder="Enter username" value="" style="width: 90%">
	<input type="submit" value="Search">
</form>
</body>
</html>

<?php
$results = [];
$error = null;
$query_text = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$query_text =  $_POST['username'] ?? '';

	try {
		if ($query_text !== '') {
			$stmt = $pdo->query("SELECT id, username, full_name FROM ADUsers WHERE username = '" . $_POST['username'] . "'");

			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$columns = [];
			$results = $rows;
			if (count($results) > 0) {
				$columns = array_keys($results[0]);
			} else {
				$colCount = $stmt->columnCount();
				for ($i = 0; $i < $colCount; $i++) {
					$meta = $stmt->getColumnMeta($i);
					$columns[] = $meta['name'] ?? ("col{$i}");
				}
			}
			
			if (count($columns) === 0) {
				echo "<p>No columns to display.</p>";
			} else {
				echo "<table><thead><tr>";
				foreach ($columns as $col) {
					echo "<th>" . e($col) . "</th>";
				}
				echo "</tr></thead><tbody>";

				if (count($results) === 0) {
					echo "<tr><td colspan=\"" . count($columns) . "\">No rows returned.</td><tr>";
				} else {
					foreach ($results as $row) {
						echo "<tr>";
						foreach ($columns as $col) {
							echo "<td>" . e($row[$col] ?? '') . "</td>";
						}
						echo "</tr>";
					}
				}
				echo "</tbody></table>";
			}
		}
	} catch (Exception $e) {
		echo "Fail: " . $e;
	}
}
?>

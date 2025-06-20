<?php
session_start();
$conn = new mysqli("localhost", "root", "", "marinpwa");
if ($conn->connect_error) {
    die("Greška kod spajanja na bazu: " . $conn->connect_error);
}

if (!isset($_GET['kat'])) {
    die("Nedostaje parametar kategorije.");
}

$kategorija = $_GET['kat'];
$poruka = '';
$vijestiKategorije = [];


$stmt = $conn->prepare("SELECT * FROM vijesti WHERE kategorija = ? AND prikaz = 1 ORDER BY datum_unosa DESC");
if ($stmt) {
    $stmt->bind_param("s", $kategorija);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $poruka = "Nema dostupnih vijesti u kategoriji: " . htmlspecialchars($kategorija);
    } else {
        while ($row = $result->fetch_assoc()) {
            $vijestiKategorije[] = $row;
        }
    }
    $stmt->close();
} else {
    die("Greška pri pripremi upita: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kategorija: <?= htmlspecialchars($kategorija) ?> - Stern</title>
  <meta name="description" content="Vijesti u kategoriji <?= htmlspecialchars($kategorija) ?>">
  <meta name="keywords" content="Stern, vijesti, <?= htmlspecialchars($kategorija) ?>">
  <meta name="author" content="Marin Škrinjar">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <header class="container py-3">
    <div class="row align-items-start">
      <div class="col-auto">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/ee/Stern_Logo.svg/960px-Stern_Logo.svg.png" alt="Stern logo" class="img-fluid" style="height: 100px;" />
      </div>
      <div class="col navigacija">
        <div class="stern1 fw-bold fs-2 mb-2"><p>stern</p></div>
        <nav>
          <a href="index.php" class="me-4 text-decoration-none fw-bold text-dark">Home</a>
          <a href="kategorija.php?kat=Politik" class="me-4 text-decoration-none fw-bold text-dark">Politik</a>
          <a href="kategorija.php?kat=Gesundheit" class="me-4 text-decoration-none fw-bold text-dark">Gesundheit</a>
          <a href="administrator.php" class="text-decoration-none fw-bold text-dark">Administracija</a>
        </nav>
      </div>
    </div>
  </header>

  <article class="container mt-4">
    <h2 class="mb-4">Vijesti u kategoriji: <?= htmlspecialchars($kategorija) ?></h2>
    <div class="row">
      <?php if (!empty($poruka)): ?>
        <p><?= $poruka ?></p>
      <?php else: ?>
        <?php foreach ($vijestiKategorije as $row): ?>
          <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="artikl">
              <img class="slika-artikl img-fluid mb-2" src="images/<?= htmlspecialchars($row['slika']) ?>" alt="<?= htmlspecialchars($row['naslov']) ?>">
              <h3><?= htmlspecialchars($row['naslov']) ?></h3>
              <p>
                <a href="clanak.php?id=<?= $row['id'] ?>" class="text-decoration-none text-dark">
                  <?= htmlspecialchars($row['podnaslov']) ?>
                </a>
              </p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </article>

  <footer class="mt-5">
    <p class="footer">
      Nachrichten vom <?= date("d.m.Y") ?> |
      ©stern.de GmbH |
      <?= htmlspecialchars($kategorija) ?>
    </p>
  </footer>
</body>
</html>

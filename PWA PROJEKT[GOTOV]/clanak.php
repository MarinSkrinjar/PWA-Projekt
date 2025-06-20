<?php
session_start();
$conn = new mysqli("localhost", "root", "", "marinpwa");
if ($conn->connect_error) {
    die("Greška kod spajanja na bazu: " . $conn->connect_error);
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Neispravan ID članka.");
}

$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM vijesti WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Članak nije pronađen.");
    }

    $row = $result->fetch_assoc();
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
  <meta name="description" content="<?= htmlspecialchars($row['naslov']) ?>">
  <meta name="keywords" content="Clanak, <?= htmlspecialchars($row['kategorija']) ?>">
  <meta name="author" content="Marin Škrinjar">
  <title><?= htmlspecialchars($row['naslov']) ?> - Stern</title>
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

  <article class="container">
    <div class="clanaknaslov mt-4">
      <h1>
        <?= htmlspecialchars($row['naslov']) ?>
        <span class="vrijeme text-muted fs-6 ms-3"><?= date("d.m.Y", strtotime($row['datum_unosa'])) ?></span>
      </h1>
      <p class="tekstic fs-5"><?= htmlspecialchars($row['podnaslov']) ?></p>
    </div>

    <div class="row my-4">
      <div class="col-12">
        <div class="clanaksadrzaj">
          <img class="slika-clanak img-fluid rounded mb-3" src="images/<?= htmlspecialchars($row['slika']) ?>" alt="Slika članka">
          <hr>
          <p class="clanaktekst fs-5"><?= nl2br(htmlspecialchars($row['sadrzaj'])) ?></p>
        </div>
      </div>
    </div>
  </article>

  <footer class="container">
    <p class="footerclanak text-center text-muted py-4">
      Nachrichten vom <?= date("d.m.Y", strtotime($row['datum_unosa'])) ?> |
      ©stern.de GmbH | <?= htmlspecialchars($row['naslov']) ?>
    </p>
  </footer>
</body>
</html>

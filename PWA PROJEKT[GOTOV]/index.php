<?php
$conn = new mysqli("localhost", "root", "", "marinpwa");
if ($conn->connect_error) {
    die("Greška kod spajanja na bazu: " . $conn->connect_error);
}

$politik = $conn->query("SELECT * FROM vijesti WHERE kategorija='Politik' AND prikaz=1 ORDER BY datum_unosa DESC");
$gesundheit = $conn->query("SELECT * FROM vijesti WHERE kategorija='Gesundheit' AND prikaz=1 ORDER BY datum_unosa DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Seminarski rad - Stern</title>
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

  <article>
    <div class="container">
      <div class="politik"><p>POLITIK ></p></div>
      <div class="row">
        <?php while($row = $politik->fetch_assoc()): ?>
          <div class="col-12 col-md-6 col-lg-4 mb-4">    
            <div class="artikl">
              <img class="slika-artikl img-fluid" src="images/<?= htmlspecialchars($row['slika']) ?>" alt="<?= htmlspecialchars($row['naslov']) ?>">
              <h3><?= htmlspecialchars($row['naslov']) ?></h3>
              <p><a href="clanak.php?id=<?= $row['id'] ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($row['podnaslov']) ?></a></p>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </article>

  <article>
    <div class="container">
      <div class="gesundheit"><p>GESUNDHEIT ></p></div>
      <div class="row">
        <?php while($row = $gesundheit->fetch_assoc()): ?>
          <div class="col-12 col-md-6 col-lg-4 mb-4">    
            <div class="artikl">
              <img class="slika-artikl2 img-fluid" src="images/<?= htmlspecialchars($row['slika']) ?>" alt="<?= htmlspecialchars($row['naslov']) ?>">
              <h3><?= htmlspecialchars($row['naslov']) ?></h3>
              <p><a href="clanak.php?id=<?= $row['id'] ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($row['podnaslov']) ?></a></p>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </article>

  <footer>
    <div class="container">
      <p class="footer">Nachrichten vom 17.05.2019 | ©stern.de GmbH</p>
    </div>
  </footer>
</body>
</html>

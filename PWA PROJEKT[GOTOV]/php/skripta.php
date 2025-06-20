<?php

if (
    isset($_POST['naslov']) &&
    isset($_POST['podnaslov']) &&
    isset($_POST['sadrzaj']) &&
    isset($_POST['kategorija']) &&
    isset($_FILES['slika']) &&
    $_FILES['slika']['error'] === UPLOAD_ERR_OK 
) {
    $naslov = $_POST['naslov'];
    $podnaslov = $_POST['podnaslov']; 
    $sadrzaj = $_POST['sadrzaj'];
    $kategorija = $_POST['kategorija'];
    $prikaz = isset($_POST['prikaz']) ? 1 : 0; 


    $ime_slike = basename($_FILES['slika']['name']);
    $slika_folder = '../images/';
    $upload_path = $slika_folder . $ime_slike;

    if (move_uploaded_file($_FILES['slika']['tmp_name'], $upload_path)) {
        $slika = $ime_slike;
    } else {
        echo "Greška prilikom prijenosa slike.";
        exit;
    }


    $conn = new mysqli("localhost", "root", "", "marinpwa");
    if ($conn->connect_error) {
        die("Greška kod spajanja na bazu: " . $conn->connect_error);
    }


    $stmt = $conn->prepare("INSERT INTO vijesti (naslov, podnaslov, slika, sadrzaj, kategorija, prikaz) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $naslov, $podnaslov, $slika, $sadrzaj, $kategorija, $prikaz);
    $stmt->execute();
    $stmt->close();
    $conn->close();
} else {
    echo "<p>Greška: Nisu poslani svi podaci iz forme.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Clanak">
  <meta name="keywords" content="Clanak,Politik, Gesundheit">
  <meta name="author" content="Marin Škrinjar">
  <title>Seminarski rad - Stern</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../style.css" />
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
          <a href="../index.php" class="me-4 text-decoration-none fw-bold text-dark">Home</a>
          <a href="../kategorija.php?kat=Politik" class="me-4 text-decoration-none fw-bold text-dark">Politik</a>
          <a href="../kategorija.php?kat=Gesundheit" class="me-4 text-decoration-none fw-bold text-dark">Gesundheit</a>
          <a href="../unos.html" class="text-decoration-none fw-bold text-dark">Administracija</a>
        </nav>
      </div>
    </div>
  </header>

  <article>
    <div class="clanaknaslov">
        <h1><?php echo htmlspecialchars($naslov); ?> 
        <span class="vrijeme"><?php echo date("d.m.Y"); ?></span></h1>
        <p class="tekstic"><?php echo htmlspecialchars($podnaslov); ?></p>
    </div>

    <div class="row">
        <div class="col-lg-12 clanak-prva">    
            <div class="clanaksadrzaj">
                <img src="../images/<?php echo htmlspecialchars($slika); ?>" alt="Slika članka" class="slika-clanak">
                <hr>
                <p class="clanaktekst"><?php echo nl2br(htmlspecialchars($sadrzaj)); ?></p>
            </div>
        </div>
    </div>
  </article>

  <footer>
    <p class="footerclanak">
      Nachrichten vom <?php echo date("d.m.Y"); ?> | ©stern.de GmbH | <?php echo htmlspecialchars($naslov); ?>
    </p>
  </footer>
</body>
</html>

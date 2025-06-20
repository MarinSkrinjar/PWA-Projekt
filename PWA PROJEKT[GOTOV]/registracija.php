<?php
session_start(); 
$conn = new mysqli("localhost", "root", "", "marinpwa"); 

if ($conn->connect_error) {
    die("Greška spajanja na bazu: " . $conn->connect_error);
}

$msg = '';
$registriranKorisnik = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ime = $_POST['ime'] ?? '';
    $prezime = $_POST['prezime'] ?? '';
    $username = $_POST['username'] ?? '';
    $lozinka = $_POST['lozinka'] ?? '';
    $ponovljena_lozinka = $_POST['ponovljena_lozinka'] ?? '';

    if (empty($ime) || empty($prezime) || empty($username) || empty($lozinka) || empty($ponovljena_lozinka)) {
        $msg = 'Sva polja moraju biti popunjena!';
    } elseif ($lozinka !== $ponovljena_lozinka) {
        $msg = 'Lozinke se ne podudaraju!';
    } else {
        $hashed_password = password_hash($lozinka, CRYPT_BLOWFISH);
        $razina = 0; 

        
        $sql_check = "SELECT korisnicko_ime FROM korisnik WHERE korisnicko_ime = ?";
        $stmt_check = $conn->prepare($sql_check);
        if ($stmt_check) {
            $stmt_check->bind_param("s", $username);
            $stmt_check->execute();
            $stmt_check->store_result();
            if ($stmt_check->num_rows > 0) {
                $msg = 'Korisničko ime već postoji. Molimo odaberite drugo.';
            }
            $stmt_check->close();
        } else {
            $msg = 'Greška pri pripremi upita za provjeru korisnika: ' . $conn->error;
        }

        if (empty($msg)) { 
          
            $sql_insert = "INSERT INTO korisnik (ime, prezime, korisnicko_ime, lozinka, razina) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);

            if ($stmt_insert) {
                $stmt_insert->bind_param("ssssi", $ime, $prezime, $username, $hashed_password, $razina);
                if ($stmt_insert->execute()) {
                    $registriranKorisnik = true;
                } else {
                    $msg = 'Greška pri registraciji korisnika: ' . $stmt_insert->error;
                }
                $stmt_insert->close();
            } else {
                $msg = 'Greška pri pripremi upita za registraciju: ' . $conn->error;
            }
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registracija - Stern</title>
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
          <a href="index.php" class="me-4">Home</a>
          <a href="kategorija.php?kat=Politik" class="me-4">Politik</a>
          <a href="kategorija.php?kat=Gesundheit" class="me-4">Gesundheit</a>
          <a href="administrator.php">Administracija</a>
        </nav>
      </div>
    </div>
  </header>

  <article class="container mt-4">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <?php if ($registriranKorisnik): ?>
          <p class="text-success text-center fw-bold fs-5">Korisnik je uspješno registriran!</p>
          <p class="text-center"><a href="administrator.php">Prijavite se ovdje</a></p>
        <?php else: ?>
          <form action="" method="POST" class="forma mx-auto p-4 rounded">
            <h2 class="text-center mb-4">Registracija</h2>
            <?php if (!empty($msg)): ?>
              <p class="text-danger text-center"><?= $msg ?></p>
            <?php endif; ?>

            <div class="mb-3">
              <label for="ime" class="form-label">Ime: </label>
              <input type="text" class="form-control" id="ime" name="ime" required value="<?= htmlspecialchars($_POST['ime'] ?? '') ?>">
            </div>

            <div class="mb-3">
              <label for="prezime" class="form-label">Prezime:</label>
              <input type="text" class="form-control" id="prezime" name="prezime" required value="<?= htmlspecialchars($_POST['prezime'] ?? '') ?>">
            </div>

            <div class="mb-3">
              <label for="username" class="form-label">Korisničko ime:</label>
              <input type="text" class="form-control" id="username" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>

            <div class="mb-3">
              <label for="lozinka" class="form-label">Lozinka:</label>
              <input type="password" class="form-control" id="lozinka" name="lozinka" required>
            </div>

            <div class="mb-3">
              <label for="ponovljena_lozinka" class="form-label">Ponovite lozinku:</label>
              <input type="password" class="form-control" id="ponovljena_lozinka" name="ponovljena_lozinka" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Registriraj se</button>
            <p class="text-center mt-3">Već imaš račun? <a href="administrator.php">Prijavi se ovdje</a></p>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </article>

  <footer><p class ="footer">Nachrichten vom 17.05.2019|©stern.de GmbH</p></footer>
</body>
</html>
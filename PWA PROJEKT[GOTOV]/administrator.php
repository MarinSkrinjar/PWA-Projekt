<?php
session_start(); 

$conn = new mysqli("localhost", "root", "", "marinpwa"); 
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Greška spajanja na bazu: " . $conn->connect_error);
}

$uspjesnaPrijava = false;
$admin = false;
$imeKorisnika = '';
$levelKorisnika = 0;
$msg = '';


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['prijava'])) {
    $enteredUsername = $_POST['username'] ?? '';
    $enteredPassword = $_POST['lozinka'] ?? '';


    $sql = "SELECT ime, lozinka, razina FROM korisnik WHERE korisnicko_ime = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $enteredUsername);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($dbIme, $dbLozinka, $dbRazina);
            $stmt->fetch();

            if (password_verify($enteredPassword, $dbLozinka)) {
                $uspjesnaPrijava = true;
                $imeKorisnika = $dbIme;
                $levelKorisnika = $dbRazina;

                $_SESSION['username'] = $imeKorisnika;
                $_SESSION['level'] = $levelKorisnika;

                if ($levelKorisnika == 1) { 
                    $admin = true;
                } else {
                    $admin = false;
                    $msg = 'Bok ' . htmlspecialchars($imeKorisnika) . '! Uspješno ste prijavljeni, ali niste administrator.';
                }
            } else {
                $msg = 'Korisničko ime i/ili lozinka nisu ispravni.';
            }
        } else {
            $msg = 'Korisničko ime i/ili lozinka nisu ispravni.';
        }
        $stmt->close();
    } else {
        $msg = 'Greška pri pripremi upita za prijavu: ' . $conn->error;
    }
}


if (isset($_SESSION['username']) && isset($_SESSION['level'])) {
    $uspjesnaPrijava = true;
    $imeKorisnika = $_SESSION['username'];
    $levelKorisnika = $_SESSION['level'];
    if ($levelKorisnika == 1) {
        $admin = true;
    } else {
        $admin = false;
        $msg = 'Bok ' . htmlspecialchars($imeKorisnika) . '! Uspješno ste prijavljeni, ali niste administrator.';
    }
}


if ($uspjesnaPrijava && $admin) {
    if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
        $id = (int)$_GET['delete'];
        $stmt = $conn->prepare("DELETE FROM vijesti WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
            header("Location: administrator.php");
            exit;
        } else {
            $greska = "Greška kod pripreme upita za brisanje: " . $conn->error;
        }
    }


    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['naslov'])) {
        $naslov = $_POST['naslov'] ?? '';
        $podnaslov = $_POST['podnaslov'] ?? '';
        $sadrzaj = $_POST['sadrzaj'] ?? '';
        $kategorija = $_POST['kategorija'] ?? '';
        $prikaz = isset($_POST['prikaz']) ? 1 : 0;

        $slika = basename($_FILES['slika']['name'] ?? '');
        $target = "images/" . $slika;

        if (!empty($slika) && move_uploaded_file($_FILES['slika']['tmp_name'], $target)) {
            $stmt = $conn->prepare("INSERT INTO vijesti (naslov, podnaslov, slika, sadrzaj, kategorija, prikaz) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sssssi", $naslov, $podnaslov, $slika, $sadrzaj, $kategorija, $prikaz);
                $stmt->execute();
                $stmt->close();
                header("Location: administrator.php");
                exit;
            } else {
                $greska = "Greška kod pripreme upita za unos: " . $conn->error;
            }
        } else {
            $greska = "Greška kod prijenosa slike ili slika nije odabrana.";
        }
    }
}


$vijesti = [];
$result = $conn->query("SELECT * FROM vijesti ORDER BY datum_unosa DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $vijesti[] = $row;
    }
}
$conn->close();
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
          <?php if (isset($_SESSION['username'])): ?>
            <a href="odjava.php" class="ms-4 text-decoration-none fw-bold text-dark">Odjava</a>
          <?php endif; ?>
        </nav>
      </div>
    </div>
  </header>

    <article>
        <?php if ($uspjesnaPrijava && $admin): ?>
            <form action="administrator.php" method="post" enctype="multipart/form-data" class="forma">
                <h2>Unos članka:</h2>
                <?php if (isset($greska)): ?>
                    <p class="text-danger text-center"><?= $greska ?></p>
                <?php endif; ?>

                <label for="naslov">Unesite naslov: </label>
                <br>
                <input type="text" name="naslov" autofocus required>
                <br>

                <label for="podnaslov">Unesite podnaslov:</label>
                <br>
                <input type="text" name="podnaslov" required>
                <br>

                <label for="slika">Unesite sliku:</label>
                <br>
                <input type="file" name="slika" accept="image/*" required>
                <br>

                <label for="sadrzaj">Tekst članka:</label>
                <br>
                <textarea name="sadrzaj" rows="4" cols="50" required></textarea>
                <br>

                <label for="kategorija">Izaberite kategoriju:</label>
                <br>
                <select name="kategorija" required>
                    <option value="" selected disabled hidden>Odaberi ovdje</option>
                    <option value="Politik">Politik</option>
                    <option value="Gesundheit">Gesundheit</option>
                </select>
                <br>
                <br>

                <input type="checkbox" name="prikaz">
                <label for="prikaz">Želite da se obavijest prikazuje na stranici?</label>
                <br>
                <input type="submit" value="Upload">
            </form>
            <hr class="hr">

            <div class="row">
                <div class="col-12">
                    <h3>Popis svih vijesti</h3>
                    <?php if (count($vijesti) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered mt-3">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Naslov</th>
                                        <th>Kategorija</th>
                                        <th>Datum</th>
                                        <th>Prikaz</th>
                                        <th>Akcije</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($vijesti as $v): ?>
                                        <tr>
                                            <td><?php echo $v['id']; ?></td>
                                            <td><?php echo htmlspecialchars($v['naslov']); ?></td>
                                            <td><?php echo htmlspecialchars($v['kategorija']); ?></td>
                                            <td><?php echo date("d.m.Y.", strtotime($v['datum_unosa'])); ?></td>
                                            <td><?php echo $v['prikaz'] ? 'DA' : 'NE'; ?></td>
                                            <td>
                                                <a href="administrator.php?id=<?php echo $v['id']; ?>" class="btn btn-primary btn-sm">Uredi</a>
                                                <a href="administrator.php?delete=<?php echo $v['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Jeste li sigurni da želite obrisati članak?');">Obriši</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p>Nema dostupnih vijesti.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php elseif ($uspjesnaPrijava && !$admin): ?>
            <div class="text-center mt-5">
                <p class="fw-bold fs-4"><?= $msg ?></p>
                <p><a href="index.php">Povratak na početnu stranicu</a></p>
                <p class="mt-3"><a href="odjava.php" class="btn btn-secondary">Odjava</a></p> </div>
        <?php else: ?>
            <form action="administrator.php" method="POST" class="forma">
                <h2>Prijava</h2>
                <?php if (!empty($msg)): ?>
                    <p class="text-danger text-center"><?= $msg ?></p>
                <?php endif; ?>

                <label for="username">Korisničko ime:</label>
                <input type="text" name="username" required>
                <br>

                <label for="lozinka">Lozinka:</label>
                <input type="password" name="lozinka" required>
                <br>

                <input type="submit" name="prijava" value="Prijavi se">
                <p class="text-center mt-3">Nemaš račun? <a href="registracija.php">Registriraj se ovdje</a></p>
            </form>
        <?php endif; ?>
    </article>
  <footer><p class ="footer">Nachrichten vom 17.05.2019|©stern.de GmbH | Administracija</p></footer>
</body>
</html>
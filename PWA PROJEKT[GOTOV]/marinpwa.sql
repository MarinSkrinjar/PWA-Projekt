-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2025 at 03:20 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `marinpwa`
--

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

CREATE TABLE `korisnik` (
  `id` int(11) NOT NULL,
  `ime` varchar(255) NOT NULL,
  `prezime` varchar(255) NOT NULL,
  `korisnicko_ime` varchar(255) NOT NULL,
  `lozinka` varchar(255) NOT NULL,
  `razina` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `korisnik`
--

INSERT INTO `korisnik` (`id`, `ime`, `prezime`, `korisnicko_ime`, `lozinka`, `razina`) VALUES
(1, 'Marin', 'Škrinjar', 'MarinŠkrinko', '$2y$10$s4H6qkwGu2Yw60pChy2Q4.J.086YHfjC88/oogu3bZp6qt3jcqn9O', 1);

-- --------------------------------------------------------

--
-- Table structure for table `osobe`
--

CREATE TABLE `osobe` (
  `id` int(11) NOT NULL,
  `ime` varchar(50) DEFAULT NULL,
  `prezime` varchar(50) DEFAULT NULL,
  `grad` varchar(50) DEFAULT NULL,
  `postanski_broj` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `osobe`
--

INSERT INTO `osobe` (`id`, `ime`, `prezime`, `grad`, `postanski_broj`) VALUES
(1, 'Nikola', 'Franovic', 'Bedekovčina', 12000);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'Marin', '$2y$10$zwqCfKLbSQHgtO07J5w9D.y8g8yTgWg3T8VrKKxLaP5gdeEkx/NzS', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `vijesti`
--

CREATE TABLE `vijesti` (
  `id` int(11) NOT NULL,
  `naslov` varchar(255) DEFAULT NULL,
  `podnaslov` varchar(255) DEFAULT NULL,
  `slika` varchar(255) DEFAULT NULL,
  `sadrzaj` text DEFAULT NULL,
  `kategorija` varchar(50) DEFAULT NULL,
  `prikaz` tinyint(1) DEFAULT NULL,
  `datum_unosa` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vijesti`
--

INSERT INTO `vijesti` (`id`, `naslov`, `podnaslov`, `slika`, `sadrzaj`, `kategorija`, `prikaz`, `datum_unosa`) VALUES
(3, 'Eine Donald-Trump-Gebetsmunze und die aberwitzige These dahinter', 'Ein US-Fernsehprediger bewirbt in seiner TV-show eine Trump-Gebetsmunze. Diese diene als \"Kontaktpunkt\" fur alle Glaubigen, die fur eine Widerwahl des US-Prasidente im Jahr 2020 beten. Hinet dem Motiv steckt eine bei christlichen Fanatikern beliebte These', 'licensed-image.jpg', 'dweafdawbdwabdawbd', 'Gesundheit', 1, '2025-06-06 17:36:53'),
(5, 'Trumpovinjo', 'Prijeti Iran-u da će uzvratit istom jačinom kojom je Izrael-u.', 'licensed-image.jpg', 'DGBWAZUDGAWZUDGASUDASDAW', 'Politik', 1, '2025-06-15 12:39:58'),
(6, 'Eine Donald-Trump-Gebetsmunze und die aberwitzige These dahinter', 'Ein US-Fernsehprediger bewirbt in seiner TV-show eine Trump-Gebetsmunze. Diese diene als \"Kontaktpunkt\" fur alle Glaubigen, die fur eine Widerwahl des US-Prasidente im Jahr 2020 beten. Hinet dem Motiv steckt eine bei christlichen Fanatikern beliebte These', 'licensed-image.jpg', 'Donald Trump war Zeit seines Lebens nicht unbedingt als Vorzeigechrist aufgefallen. In den USA sind jedoch sehr viele Menschen streng gläubig und so suchte Trump bereits im Wahlkampf die Nähe zu christlichen Predigern. Seit seiner Wahl zum 45. Präsidenten zeigt er sich gerne mit besonders fanatischen Vertretern dieser Zunft. So betete er 2017 etwa mit dem evangelikalen Pastor Robert Jeffress im Oval Office. Der dankte Gott für \"das Geschenk Donald Trump\", weil dieser das Land heilen würde. In fundamentalistischen christlichen Kreisen ist Trump ob seiner erzkonservativen Positionen sehr beliebt und diese Beliebtheit hat sich nun in einer goldfarbenen Gebetsmünze manifestiert, beworben von einem zwielichtigen TV-Prediger (wobei man darüber streiten kann, ob das nicht eine Tautologie ist).\r\n\r\n45 Dollar soll die Münze kosten, deren Material nicht weiter erläutert wird. Im Pack mit einigen Büchern und DVDs (ja, nicht Blu-rays) muss man satte 450 Dollar berappen. Beworben wird das Produkt von den TV-Evangelikalen Lance Wallnau und Jim Bakker. Letzterer gehört zu den Pionieren auf dem Gebiet des Fernsehpredigens, musste jedoch Ende der 80er-Jahre wegen Veruntreuung von Geldern vier Jahre ins Gefängnis. Nach einigen Jahren abseits der Bildschirme kehrte er Anfang des Jahrtausends zurück ins Fernsehen und moderiert mittlerweile die \"Jim Bakker Show\". Eben dort trat Wallnau auf und durfte erläutern, warum er diese Münze verkauft, oder besser: Warum die Leute sie kaufen sollen.\r\n\r\nAuf der Münze ist neben Donald Trump auch ein Bild von Kyros II. abgebildet, sowie ein Bibelvers, in dem eben jener persische König erwähnt wird. In den fundamentalistischen christlichen Kreisen, insbesondere bei den Evangelikalen, ist die These sehr beliebt, dass Donald Trump quasi eine Art Wiedergeburt des biblischen Königs sei, wie die \"New York Times\" bereits im Dezember ausführte. Dieser hatte rund 600 vor Christus gelebt und laut Bibel als erster Herrscher Persiens eine große Gruppe Juden aus Babylon befreit. Der auf der Münze abgebildete Vers Isaiah 45:1 feiert ihn eben dafür ab.\r\n\r\nKyros gilt demnach als ein Nichtgläubiger, der von Gott auserwählt wurde, um den Willen der Gläubigen umzusetzen. Eben dies sei auch Donald Trump, behaupten Menschen wie TV-Prediger Wallnau. Er hat sogar ein Buch mit dem Titel \"Kyros Trump\" (engl. \"Cyrus Trump\") darüber geschrieben – enthalten in dem 450-Dollar-Paket samt Münze. Darin behauptet er, Trumps Aufstieg sei in der Bibel prophezeit worden. Ein Zeichen dafür soll etwa sein, dass der Kyros-Vers der 45. ist und Trump der 45. Präsident der USA.\r\n\r\n\"Deswegen müssen wir beten\", bewirbt der Mann in der \"Jim Bakker Show\" sein Produkt. \"Weil sie denken, dass wir die Verrückten sind, dabei sind wir tatsächlich die geistig Gesunden.\"', 'Politik', 1, '2025-06-15 13:21:58'),
(7, 'Eine Donald-Trump-Gebetsmunze und die aberwitzige These dahinter', 'Ein US-Fernsehprediger bewirbt in seiner TV-show eine Trump-Gebetsmunze. Diese diene als \"Kontaktpunkt\" fur alle Glaubigen, die fur eine Widerwahl des US-Prasidente im Jahr 2020 beten. Hinet dem Motiv steckt eine bei christlichen Fanatikern beliebte These', 'licensed-image.jpg', 'dawdawvbawbdawb', 'Gesundheit', 1, '2025-06-15 15:14:40');

-- --------------------------------------------------------

--
-- Table structure for table `zaposlenik`
--

CREATE TABLE `zaposlenik` (
  `id` int(11) NOT NULL,
  `ime_zaposlenika` varchar(50) DEFAULT NULL,
  `prezime_zaposlenika` varchar(50) DEFAULT NULL,
  `OIB` bigint(20) DEFAULT NULL,
  `e_mail` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zaposlenik`
--

INSERT INTO `zaposlenik` (`id`, `ime_zaposlenika`, `prezime_zaposlenika`, `OIB`, `e_mail`) VALUES
(1, 'Marin', 'Skrinjar', 123456789, 'pedjo@gmail.com'),
(2, 'Niko', 'Babic', 145312341, 'babic@gmail.com'),
(3, 'Nikola', 'Franovic', 2453112345, 'franovic@gmail.com'),
(4, 'Toni', 'Ambramovič', 123456786, 'toni@yahoo.com'),
(5, 'Toni', 'Ambramovič', 123456786, 'toni@yahoo.com'),
(6, 'Ivano', 'Balić', 1234567321, 'balic@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `korisnicko_ime` (`korisnicko_ime`);

--
-- Indexes for table `osobe`
--
ALTER TABLE `osobe`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vijesti`
--
ALTER TABLE `vijesti`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zaposlenik`
--
ALTER TABLE `zaposlenik`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `korisnik`
--
ALTER TABLE `korisnik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `osobe`
--
ALTER TABLE `osobe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vijesti`
--
ALTER TABLE `vijesti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `zaposlenik`
--
ALTER TABLE `zaposlenik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

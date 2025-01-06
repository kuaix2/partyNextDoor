<?php
session_start();
require_once('fpdf186/fpdf.php');
 // Inclure la bibliothèque FPDF
 require('phpqrcode-master/qrlib.php'); // Inclure la bibliothèque PHPQRCode


if (!isset($_SESSION['user_id'])) {
    die("Vous devez être connecté pour télécharger ce billet.");
}

if (!isset($_GET['ticket_id'])) {
    die("Billet introuvable.");
}

$ticket_id = intval($_GET['ticket_id']);

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=bddpartynextdoor", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer les informations du billet et de l'événement
$sql = "
    SELECT t.id AS ticket_id, e.event_name, e.event_date, e.event_adresse, e.event_price, u.nom_utilisateur
    FROM tickets t
    JOIN events e ON t.event_id = e.id
    JOIN utilisateur u ON t.user_id = u.id
    WHERE t.id = :ticket_id AND t.user_id = :user_id
";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':ticket_id', $ticket_id, PDO::PARAM_INT);
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();

$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
    die("Billet introuvable ou accès non autorisé.");
}

// Générer un QR code
$qrData = "Billet ID: " . $ticket['ticket_id'] . "\n" .
          "Événement: " . $ticket['event_name'] . "\n" .
          "Date: " . $ticket['event_date'] . "\n" .
          "Utilisateur: " . $ticket['nom_utilisateur'];
$qrFilePath = 'qrcodes/' . $ticket['ticket_id'] . '.png';
QRcode::png($qrData, $qrFilePath, QR_ECLEVEL_L, 4);



require_once('fpdf186/fpdf.php');


$pdf = new FPDF();
$pdf->AddPage();

// Titre
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Billet d\'Entree', 0, 1, 'C');

// Détails du billet
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Nom de l\'utilisateur : ' . utf8_decode($ticket['nom_utilisateur']), 0, 1);
$pdf->Cell(0, 10, 'Nom de l\'evenement : ' . utf8_decode($ticket['event_name']), 0, 1);
$pdf->Cell(0, 10, 'Date de l\'evenement : ' . date("d/m/Y", strtotime($ticket['event_date'])), 0, 1);
$pdf->Cell(0, 10, 'Adresse : ' . utf8_decode($ticket['event_adresse']), 0, 1);
$pdf->Cell(0, 10, 'Prix : ' . number_format($ticket['event_price'], 2, ',', '') . ' EUR', 0, 1);
// QR Code
$pdf->Ln(10);
$pdf->Cell(0, 10, 'Scannez ce QR code pour valider votre billet :', 0, 1, 'C');
$pdf->Image($qrFilePath, 80, $pdf->GetY(), 50, 50); // Position et taille du QR code

// Numéro de billet
$pdf->Ln(60);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Numero de Billet : ' . $ticket['ticket_id'], 0, 1, 'C');
// 
// Sortie du PDF
$pdf->Output('I', 'billet.pdf');

// Supprimez le fichier QR code après génération
unlink($qrFilePath);
?>

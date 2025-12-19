<?php
require('includes/db.inc.php');

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);

    $result = mysqli_query($con, "SELECT medical_document FROM time_off_request WHERE id = '$id'");
    $row = mysqli_fetch_assoc($result);

    if ($row && !empty($row['medical_document'])) {
        $filePath = __DIR__ . '/../' . $row['medical_document'];
        
        // Nëse është path (ruajtur si file path)
        if (file_exists($filePath)) {
            header('Content-type: application/pdf');
            header('Content-Disposition: inline; filename="medical_document.pdf"');
            readfile($filePath);
        } else {
            // Nëse është binary (ruajtur direkt në DB - për kompatibilitet me të dhënat e vjetra)
            header('Content-type: application/pdf');
            echo $row['medical_document'];
        }
    } else {
        echo "No PDF uploaded.";
    }
}
?>

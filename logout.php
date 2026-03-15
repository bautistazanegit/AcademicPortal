<?php
session_start();
session_unset(); // Linisin ang lahat ng session variables
session_destroy(); // Sirain ang session

// I-redirect sa index.php na nasa root folder
header("Location: /AcademicPortal/index.php"); 
exit();
?>
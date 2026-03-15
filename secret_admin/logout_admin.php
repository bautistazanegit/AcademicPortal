<?php
session_start();
session_unset();
session_destroy();
header("Location: /AcademicPortal/secret_admin/index.php"); 
exit();
?>
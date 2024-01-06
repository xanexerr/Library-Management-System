<<<<<<< HEAD
<?php
session_start();
session_destroy();
header("Location: login.php");
// header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
=======
<?php
session_start();
session_destroy();
header("Location: index.php");
// header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
>>>>>>> e8e6f18fc4e84e741c7390069d2e2a561dc90624
?>
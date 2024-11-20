<?php

use App\Controller\Curl;

session_unset();
session_destroy();
setcookie('user_data', '', time() - 3600, '/');
echo '<script>window.location.href = "/";</script>';
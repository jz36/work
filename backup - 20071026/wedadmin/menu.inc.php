<?php
if (!defined("WA_PATH")) define("WA_PATH", "./");
if (!defined("WA_URL")) define("WA_URL", "/wedadmin/");
echo '<ul>';
echo '<li><a href="">Аккаунты</a></li>';
echo '<li>';
echo '<a href='.WA_URL.'"catalogue.php">Каталог товаров</a>';
echo '</li>';
echo '</ul>';
?>
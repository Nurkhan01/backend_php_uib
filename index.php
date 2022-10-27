<?php
echo 'Здравствуйте '. htmlspecialchars($_POST['name']).
' Вам '. (int)$_POST['age']. ' лет';

if ($_POST['age'] < 18){
    echo '</br> you have no access';
}else {
    echo '</br> good';
}
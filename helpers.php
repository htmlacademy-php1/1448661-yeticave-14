
<?php
/**
 * Функция распечатывает массив в читаемом формате
 * @param array $val
 * @return void
 */
function myPrint(array $val)
{
    echo '<pre>';
    print_r($val);
    echo  '</pre>';
}

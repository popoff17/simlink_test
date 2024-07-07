<?php
$target = $_SERVER['DOCUMENT_ROOT'] . "/core";
$link = $_SERVER['DOCUMENT_ROOT'] . "/test";

if (file_exists($link)) {
    echo "Симлинк уже существует.";
} else {
    if (symlink($target, $link)) {
        echo "Симлинк успешно создан.";
    } else {
        echo "Не удалось создать симлинк. Пожалуйста, проверьте права доступа.";
    }
}
?>

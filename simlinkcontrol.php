<?php

$simlinksArr = [
				"/core" => "/subdomain/core"
			];



foreach($simlinksArr as $target=>$name){
	$target = $_SERVER['DOCUMENT_ROOT'].$target;
	$name = $_SERVER['DOCUMENT_ROOT'].$name;
	
	if (file_exists($name)) {
		var_dump(readlink($name));
		die("x");
	}
	
	
	
	break;
	if (file_exists($name)) {
		echo "Симлинк уже существует.";
	} else {
		if (symlink($target, $name)) {
			echo "Симлинк успешно создан.";
		} else {
			echo "Не удалось создать симлинк. Пожалуйста, проверьте права доступа.";
		}
	}
}
?>

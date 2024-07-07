<?php

/*
	todo:
	1 - писать в гитигнор только создаваемые симлинки. 
	в идеале проверять уже существующие симлинки. если запись есть в гитигноре, а самого линка нет - удалять запись о нем из гитигнора
	
	
*/

class SimlinkControl {
    public $simlinksArr;

    function __construct($simlinksArrAdd = []) {
        $this->simlinksArr = [
            "/core" => "/subdomain/core",
            "/test" => "/subdomain/test",
        ];

		$this->simlinksArr = array_merge($this->simlinksArr, $simlinksArrAdd);
		
		$this->makeSimlink();
    }

    public function makeSimlink() {
        foreach ($this->simlinksArr as $target => $name) {
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . $target;
            $namePath = $_SERVER['DOCUMENT_ROOT'] . $name;
				
			// создаем симлинки только на реальные файлы или папки
			if(is_dir($targetPath) || file_exists($targetPath)){
				//если путь не является симлинком
				if (!is_link($namePath)) {
					// удаляем и создаем заново
					if (file_exists($namePath)) {
						unlink($namePath);
					}
					if (symlink($targetPath, $namePath)) {
						$this->updateGitignore($this->simlinksArr);
					} else {
						//return "Не удалось создать симлинк: $namePath. Пожалуйста, проверьте права доступа.\n";
						return false;
					}
				}
			}
        }
    }
	
	public function updateGitignore($simlinksArr) {
		$gitignorePath=$_SERVER['DOCUMENT_ROOT'].'/.gitignore';
		if(file_exists($gitignorePath)){
			$gitignoreContent = file_get_contents($gitignorePath);
			// Проверить, существует ли блок "##### simlink control #####"
			$startMarker = "##### simlink control #####";
			$endMarker = "##### simlink control end #####";
			$blockStart = strpos($gitignoreContent, $startMarker);
			$blockEnd = strpos($gitignoreContent, $endMarker);
			if ($blockStart === false || $blockEnd === false || $blockEnd < $blockStart) {
				// Если блок не найден, создать его
				$blockContent = "$startMarker\n";
				foreach ($simlinksArr as $link) {
					$blockContent .= "$link\n";
				}
				$blockContent .= "$endMarker\n";
				$gitignoreContent .= "\n$blockContent";
			} else {
				// Если блок найден, проверить наличие записей в этом блоке
				$blockContent = substr($gitignoreContent, $blockStart, $blockEnd - $blockStart + strlen($endMarker));
				foreach ($simlinksArr as $link) {
					if (strpos($blockContent, $link) === false) {
						$blockContent = str_replace($endMarker, "$link\n$endMarker", $blockContent);
					}
				}
				$gitignoreContent = substr_replace($gitignoreContent, $blockContent, $blockStart, $blockEnd - $blockStart + strlen($endMarker));
			}
			// Записать обновленное содержимое обратно в файл .gitignore
			file_put_contents($gitignorePath, $gitignoreContent);
		}
	}
}

$simlinksArrAdd = [
    "/core" => "/subdomain/core",
    "/test" => "/subdomain/test",
    "/lol" => "/subdomain/lol",
];
$simlinkControl = new SimlinkControl($simlinksArrAdd);



?>

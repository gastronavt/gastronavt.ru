<?php	
spl_autoload_register(function ($class_name){
	$lib_path = search_file(CORE_FOLDER.'/application/lib',strtolower($class_name).'.class.php');
	$core_path = search_file(CORE_FOLDER.'/application/core',strtolower($class_name).'.php');
	$controllers_path = CORE_FOLDER.'/application/controllers/'.strtolower(substr($class_name, 0, -10)).'.controller.php';
	$models_path = search_file(CORE_FOLDER.'/application/models',strtolower($class_name).'.php');
	
	if( file_exists($lib_path) ){
		require_once($lib_path);
	}
	elseif( file_exists($core_path) ){
		require_once($core_path);
	}
	elseif( file_exists($controllers_path) ){
		require_once($controllers_path);
	}
	elseif( file_exists($models_path) ){
		require_once($models_path);
	}else{
		header('HTTP/1.1 404 OK');
		exit();
	}
});

function search_file($path, $filename) {
    if(($dir = opendir($path)) == FALSE)
       return '';
    $link = '';
    while(($fp = readdir($dir)) !== FALSE) {
         $link = $path . '/' . $fp;
         if(is_file($link)) {
             if($fp == $filename) {
                  closedir($dir);
                  return $link;
             }
         }else if(! preg_match('/^[\.]{1,2}$/', $fp) && is_dir($link)) {
             if(($link = search_file($link, $filename)) != '') {
                  closedir($dir);
                  return $link;
             }
         }
    }
    closedir($dir);
    return ''; 
}

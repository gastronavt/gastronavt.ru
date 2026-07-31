<?php
class View
{	
	function generate($template_view, $data = null)
	{
		$template_path = CORE_FOLDER.'/application/views/'.$template_view;
		
		if( !file_exists($template_path) ) {
			throw new Exception('Template file is not found in path '.$template_path);
		}
		
		include $template_path;
	}
	
	function generateWithTemplate($content_view, $template_view, $data = null)
	{
		$template_path = CORE_FOLDER.'/application/views/'.$template_view;
		
		if( !file_exists($template_path) ) {
			throw new Exception('Template file is not found in path '.$template_path);
		}
		
		include $template_path;
	}
}
?>
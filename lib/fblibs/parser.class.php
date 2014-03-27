<?php
class Parser
{
	public function __construct() {
		
	}
	public function parseContentWithFields($filename, $fusion_fields)
	{
		$content = file_get_contents($filename);
		preg_match_all('/@@\w+@@/', $content, $matches);
		foreach ($matches[0] as $match) {
			$field = preg_replace('/@@/', '', $match);
			$replace = $fusion_fields[$field];			
			$content = preg_replace('/@@'.$field.'@@/', $replace, $content);
		}

		return $content;
	}
}
?>

<?php
class CustomView extends \Slim\View {
    public function render($template) {

    	foreach ($this->data as $key => $val)
    		$$key = $val;

    	ob_start();
    	include('./templates/' . $template);
		$view_content = ob_get_clean();

		include('./templates/layout.php');
		
    }
}
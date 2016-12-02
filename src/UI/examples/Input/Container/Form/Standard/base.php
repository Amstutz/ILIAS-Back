<?php
/**
 * Demo Example
 */
function base() {
	//Loading factories
	global $DIC;

	$f = $DIC->ui()->factory();
	$renderer = $DIC->ui()->renderer();

	$text_input1 = $f->input()->item()->field()->text("Example Field")
			->withValidator(function ($res) {
				return "Hello World"==$res;
			})
			->withTitle("TODO")
			->required(true);

	$text_input2 = $f->input()->item()->field()->text("Example Field 2")
			->withValidator(function ($res) {
				return "Hello World"==$res;
			})
			->withTitle("TODO2")
			->required(true);
	$html = "";

	if($_POST){

		$text_input = $text_input1->withContent($_POST['TODO']);
		if($text_input1->validate()){
			$html .= $text_input1->content();
		}else{
			$html .= "Error: ".$_POST['TODO'];
		}
	}

	$form = $f->input()->container()->form()
			->standard("#",[$text_input1,$text_input2]);

	$html .= $renderer->render($form);

	return $html;
}

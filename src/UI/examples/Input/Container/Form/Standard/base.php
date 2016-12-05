<?php
/**
 * Demo Example
 */
function base() {
	//Loading factories
	global $DIC;

	$f = $DIC->ui()->factory();
	$renderer = $DIC->ui()->renderer();

    $validator = new \ILIAS\UI\Implementation\Component\Input\Validation(function ($res) {
        return "Hello World"==$res;
    },"Hello World Validation 1");

	$text_input1 = $f->input()->item()->field()->text("Example Field")
			->withValidator($validator)
			->withTitle("TODO")
			->required(true);

	$text_input2 = $f->input()->item()->field()->text("Example Field 2")
			->withValidator($validator)
			->withTitle("TODO2")
			->required(true);
	$html = "";

    $form = $f->input()->container()->form()
        ->standard("#",[$text_input1,$text_input2]);

	if($_POST){
        $form = $form->withPostInput();
        if($form->hasValidContent()){
            foreach($form->content() as $content_item){
                $html .= "Valid content: ".$content_item."</br>";
            }

        }else{
            foreach($form->validationErrors() as $error){
                $html .= "Validation Error from ".$error->getItem()->title().": ".$error->getMessage()."</br>";
            }
        }
	}

	$html .= $renderer->render($form);

	return $html;
}

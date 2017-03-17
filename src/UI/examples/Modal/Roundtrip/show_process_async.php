<?php
function show_process_async()
{
	global $DIC;
	$factory = $DIC->ui()->factory();
	$renderer = $DIC->ui()->renderer();

	require_once('./Services/Form/classes/class.ilSelectInputGUI.php');

	if($_GET["step"] ){
		if($_GET["step"] == 1){
			$content = $factory->legacy('Step 2.1');
		}else{
			$content = $factory->legacy('Step 2.2');
		}
		$modal2 = $factory->modal()->roundtrip('My Modal 1',$content);
		$finish_button = $factory->button()->primary('Finish', '#')
				->withOnClick($modal2->getCloseSignal());
		$modal2 = $modal2->withActionButtons([$finish_button]);

		echo $renderer->renderAsync($modal2);
		exit;
	}

	// Build the select Input
	$select_input = new ilSelectInputGUI('Next Step', 'step');
	$select_input->setOptions(["1" => "Step 2.1", "2" => "Step 2.2"]);

	//Step 1 Create Modals
	$modal1 = $factory->modal()->roundtrip('My Modal 1', $factory->legacy($select_input->getToolbarHTML()));


	$modal2 = $factory->modal()->roundtrip('', $factory->legacy(''))
			->withAsyncRenderUrl()
			->withAsyncUrlParamsCode(
					"return '&step='+$('select[name=step]').val();"
			);
	//$modal1->withReplacement($modal2);
	$submit = $factory->button()->primary('Next', '#')
			->withOnClick($modal1->getReplaceSignal());

	$modal1 = $modal1->withActionButtons([$submit]);

	$opening_button = $factory->button()->standard('Open Modal 1', '#')
			->withOnClick($modal1->getShowSignal());

	return $renderer->render([$opening_button, $modal1,$modal2]);
}
<?php
function show_process()
{
	global $DIC;
	$factory = $DIC->ui()->factory();
	$renderer = $DIC->ui()->renderer();

	//Step 1 Create Modals
	$modal1 = $factory->modal()->roundtrip('My Modal 1', $factory->legacy('Step 1'));
	$modal2 = $factory->modal()->roundtrip('My Modal 1', $factory->legacy('Step 2'));

	//Step 2 Create Buttons and wire them up with modals
	$opening_button = $factory->button()->standard('Open Modal 1', '#')
			->withOnClick($modal1->getShowSignal());
	$to_step2_button = $factory->button()->primary('Next', '#')
			->withOnClick($modal2->getShowSignal())
			->appendOnClick($modal1->getCloseSignal());
	$finish_button = $factory->button()->primary('Finish', '#')
			->withOnClick($modal2->getCloseSignal());

	//Step 3 Attach buttons to modals
	$modal1 = $modal1->withActionButtons([$to_step2_button]);
	$modal2 = $modal2->withActionButtons([$finish_button]);

	return $renderer->render([$opening_button, $modal1, $modal2]);
}
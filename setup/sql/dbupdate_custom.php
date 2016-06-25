<#1>
<?php

include_once('./Services/Migration/DBUpdate_3560/classes/class.ilDBUpdateNewObjectType.php');

$type_id = ilDBUpdateNewObjectType::getObjectTypeId('stys');
if($type_id)
{
	$new_ops_id = ilDBUpdateNewObjectType::addCustomRBACOperation('sty_write_content', 'Edit Content Styles', 'object', 6101);
	if($new_ops_id)
	{
		ilDBUpdateNewObjectType::addRBACOperation($type_id, $new_ops_id);

		$src_ops_id = ilDBUpdateNewObjectType::getCustomRBACOperationId('write');
		if($src_ops_id)
		{
			ilDBUpdateNewObjectType::cloneOperation('stys', $src_ops_id, $new_ops_id);
		}
	}
}
?>
<#2>
<?php

include_once('./Services/Migration/DBUpdate_3560/classes/class.ilDBUpdateNewObjectType.php');

$type_id = ilDBUpdateNewObjectType::getObjectTypeId('stys');
if($type_id)
{
	$new_ops_id = ilDBUpdateNewObjectType::addCustomRBACOperation('sty_write_system', 'Edit System Styles', 'object', 6100);
	if($new_ops_id)
	{
		ilDBUpdateNewObjectType::addRBACOperation($type_id, $new_ops_id);

		$src_ops_id = ilDBUpdateNewObjectType::getCustomRBACOperationId('write');
		if($src_ops_id)
		{
			ilDBUpdateNewObjectType::cloneOperation('stys', $src_ops_id, $new_ops_id);
		}
	}
}
?>
<#3>
<?php

include_once('./Services/Migration/DBUpdate_3560/classes/class.ilDBUpdateNewObjectType.php');

$type_id = ilDBUpdateNewObjectType::getObjectTypeId('stys');
if($type_id)
{
	$new_ops_id = ilDBUpdateNewObjectType::addCustomRBACOperation('sty_write_page_layout', 'Edit Page Layouts', 'object', 6102);
	if($new_ops_id)
	{
		ilDBUpdateNewObjectType::addRBACOperation($type_id, $new_ops_id);

		$src_ops_id = ilDBUpdateNewObjectType::getCustomRBACOperationId('write');
		if($src_ops_id)
		{
			ilDBUpdateNewObjectType::cloneOperation('stys', $src_ops_id, $new_ops_id);
		}
	}
}
?>
<#4>
<?php
include_once('./Services/Migration/DBUpdate_3560/classes/class.ilDBUpdateNewObjectType.php');
$ops_id = ilDBUpdateNewObjectType::getCustomRBACOperationId('write');
ilDBUpdateNewObjectType::deleteRBACOperation('stys', $ops_id);
?>

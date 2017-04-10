<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	global $msgListItem;
	
	if ($msgListItem["unread"] == true)
		$spm__unread = " active";
	else
		$spm__unread = "";
?>
<a href="" class="list-group-item<?php print($spm__unread); ?>">
	<h4 class="list-group-item-heading"><?php print($msgListItem["title"]); ?></h4>
	<p class="list-group-item-text"><?php print($msgListItem["message"]); ?></p>
</a>
<?php
	unset($spm__unread);
?>
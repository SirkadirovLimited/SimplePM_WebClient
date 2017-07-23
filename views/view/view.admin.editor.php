<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__view.admin__") or die('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::administrator);
	
	include(_S_SERV_INC_ . "view.admin.editor.sender.php");
	
	function generate_page($id, $name, $content){
		global $_SPM_CONF;
		
		$action = ($id > 0 ? "index.php?service=view.admin&edit=" . $id : "index.php?service=view.admin&create");
		
?>
<script src="<?php print(_S_TPL_); ?>js/tinymce/tinymce.min.js"></script>
<style>
	div.mce-fullscreen {
		z-index: 100;
	}
</style>
<script>
tinymce.init({
  selector: 'textarea',
  height: 300,
  theme: 'modern',
  plugins: [
    'advlist autolink lists link image charmap preview hr anchor',
    'searchreplace wordcount visualblocks visualchars code',
    'insertdatetime media nonbreaking table contextmenu directionality',
    'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc'
  ],
  toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  toolbar2: 'print preview media | forecolor backcolor emoticons | codesample',
  image_advtab: true,
 });
</script>
<form action="<?=$action?>" method="post">
	<div class="form-group">
		<label for="pageId">ID сторінки</label>
		<input type="text" class="form-control" id="pageId" name="id" value="<?=$id?>" readonly>
	</div>
	<div class="form-group">
		<label for="name">Найменування сторінки</label>
		<input type="text" class="form-control" name="pname" id="name" minlength="1" maxlength="255" value="<?=$name?>" >
	</div>
	<div class="form-group">
		<label for="content">Контент сорінки</label>
		<textarea class="form-control" name="pcontent" id="content" rows="10" style="z-index: 50;"><?=$content?></textarea>
	</div>
	<button type="submit" class="btn btn-primary btn-block">Зберегти</button>
	<a class="btn btn-danger btn-block" href="index.php?service=view.admin">Відмінити</a>
</form>
<?php
		
	}
	
	if (isset($_GET['edit']) && strlen(trim($_GET['edit'])) > 0)
	{
		
		if (!$db_result = $db->query("SELECT * FROM `spm_pages` WHERE id = '" . mysqli_real_escape_string($db, trim($_GET['edit'])) . "'"))
			die(header('location: index.php?service=error&err=db_error'));
		
		if ($db_result->num_rows == 0)
			include_once(_S_TPL_ERR_ . $_SPM_CONF["ERR_PAGE"]["404"]);
		else
		{
			$page_info = $db_result->fetch_assoc();
			generate_page($page_info['id'], $page_info['name'], htmlspecialchars_decode($page_info['content']));
		}
		
		
	}
	elseif (isset($_GET['create']))
		generate_page(0, "", "");
	
?>
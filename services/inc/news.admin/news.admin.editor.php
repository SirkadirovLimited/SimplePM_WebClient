<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	defined("__view.admin__") or die('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::administrator);
	
	require(_S_SERV_INC_ . "news.admin/news.admin.editor.sender.php");
	
	function generate_page($id, $name, $content){
		global $_SPM_CONF;
		
		if ($id > 0)
			$action = "index.php?service=news.admin&edit=" . $id;
		else
			$action = "index.php?service=news.admin&create";
?>
<script src="<?=_S_TPL_?>js/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({
  selector: 'textarea',
  height: 300,
  theme: 'modern',
  plugins: [
    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
    'searchreplace wordcount visualblocks visualchars code fullscreen',
    'insertdatetime media nonbreaking save table contextmenu directionality',
    'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc'
  ],
  toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  toolbar2: 'print preview media | forecolor backcolor emoticons | codesample',
  image_advtab: true,
 });
</script>
<form action="<?=$action?>" method="post">
	<div class="form-group">
		<label for="pageId">ID новости</label>
		<input type="text" class="form-control" id="pageId" value="<?=$id?>" readonly>
	</div>
	<div class="form-group">
		<label for="name">Название новости</label>
		<input type="text" class="form-control" name="pname" id="name" minlength="1" maxlength="255" value="<?=$name?>" required>
	</div>
	<div class="form-group">
		<label for="content">Контент новости</label>
		<textarea class="form-control" name="pcontent" id="content" rows="10"><?=$content?></textarea>
	</div>
	<input type="submit" class="btn btn-primary btn-block" value="Сохранить" name="save">
	<a class="btn btn-danger btn-block" href="index.php?service=news.admin">Отменить</a>
</form>
<?php
		
	}
	
	if (isset($_GET['edit']) && strlen(trim($_GET['edit'])) > 0){
		
		if (!$db_result = $db->query("SELECT * FROM `spm_news` WHERE id = '" . htmlspecialchars(strip_tags(trim($_GET['edit']))) . "'"))
			die('Произошла непредвиденная ошибка при выполнении запроса к базе данных.<br/>');
		
		if ($db_result->num_rows === 0){
			include_once(_S_TPL_ERR_ . $_SPM_CONF["ERR_PAGE"]["404"]);
		}else{
			$page_info = $db_result->fetch_assoc();
			generate_page($page_info['id'], $page_info['title'], htmlspecialchars_decode($page_info['content']));
		}
		
		
	}elseif (isset($_GET['create'])){
		generate_page(-1, "", "");
	}
?>
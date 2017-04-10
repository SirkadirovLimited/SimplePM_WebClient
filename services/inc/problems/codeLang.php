<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	
	abstract class CODE_LANG
	{
		const freepascal = 1;
		const csharp = 2;
		const cpp = 3;
		const c = 4;
	}
	
	function switchCodeLang($codeLang){
		//CODE LANGUAGE
		switch ((int)$codeLang){
			case CODE_LANG::freepascal:
				return 'freepascal';
				break;
			case CODE_LANG::csharp:
				return 'csharp';
				break;
			case CODE_LANG::cpp:
				return 'cpp';
				break;
			case CODE_LANG::c:
				return 'c';
				break;
			default:
				die('<strong>Язык программирования, на котором написано приложение, не выбран!</strong>');
				break;
		}
	}
?>
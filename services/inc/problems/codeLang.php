<?php
	abstract class CODE_LANG
	{
		const freepascal = 1;
		const csharp = 2;
		const cpp = 3;
		const c = 4;
		const lua = 5;
		const java = 6;
		const python = 7;
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
			case CODE_LANG::lua:
				return 'lua';
				break;
			case CODE_LANG::python:
				return 'python';
				break;
			case CODE_LANG::java:
				return 'java';
				break;
			default:
				die(header('location: index.php?service=error&err=input'));
				break;
		}
	}
?>
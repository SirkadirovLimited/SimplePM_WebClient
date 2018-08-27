<?php

/*
 * ███████╗██╗███╗   ███╗██████╗ ██╗     ███████╗██████╗ ███╗   ███╗
 * ██╔════╝██║████╗ ████║██╔══██╗██║     ██╔════╝██╔══██╗████╗ ████║
 * ███████╗██║██╔████╔██║██████╔╝██║     █████╗  ██████╔╝██╔████╔██║
 * ╚════██║██║██║╚██╔╝██║██╔═══╝ ██║     ██╔══╝  ██╔═══╝ ██║╚██╔╝██║
 * ███████║██║██║ ╚═╝ ██║██║     ███████╗███████╗██║     ██║ ╚═╝ ██║
 * ╚══════╝╚═╝╚═╝     ╚═╝╚═╝     ╚══════╝╚══════╝╚═╝     ╚═╝     ╚═╝
 *
 * SimplePM WebApp is a part of software product "Automated
 * verification system for programming tasks "SimplePM".
 *
 * Copyright (C) 2016-2018 Yurij Kadirov
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * GNU Affero General Public License applied only to source code of
 * this program. More licensing information hosted on project's website.
 *
 * Visit website for more details: https://spm.sirkadirov.com/
 */

class Security {

    private $mysqli_connection;

    /**
     * Security constructor.
     * @param mysqli $mysqli_connection
     */

    public function __construct(mysqli $mysqli_connection)
    {

        $this->mysqli_connection = $mysqli_connection;

    }

    /**
     * @param string $param
     * @return string
     */

    public function ClearVisibleParam(string $param) : string
    {

        return htmlspecialchars(
            strip_tags(
                trim(
                    $param
                )
            )
        );

    }

    /**
     * @return void
     */

    public function ClearGET() : void
    {

        foreach ($_GET as &$param) {

            $param = $this->ClearVisibleParam($param);

        }

    }

	/**
	 * @param null $arr
	 * @return void
	 */

    public function ClearPOST(&$arr = null) : void
    {

    	if ($arr === null)
    		$arr = &$_POST;

        foreach ($arr as &$param) {

			if (is_array($param))
			{

				$this->ClearPOST($param);

			}
			else
			{

				$param = mysqli_real_escape_string(
					$this->mysqli_connection,
					$param
				);

			}

        }

    }

    /**
     * @return mixed
     */

    public static function &getCurrentSession()
    {

        global $_CONFIG;

        return $_SESSION[$_CONFIG->getWebappConfig()["site_session_id"]];

    }

    /**
     * @param int $user_permissions
     * @param int $requested_permission
     * @param bool $die_if_access_denied
     * @return bool
     */

    public static function CheckAccessPermissions(int $requested_permission, bool $die_if_access_denied = false, int $user_permissions = -1) : bool
    {

        // Автоподстановка прав текущего пользователя
        if ($user_permissions == -1)
            $user_permissions = Security::getCurrentSession()['user_info']->getUserInfo()['permissions'];

        $result = $user_permissions & $requested_permission;

        if (!$die_if_access_denied || $result)
            return $result;
        else {

            self::ThrowError("403");

        }

        return false;

    }

    /**
     * Проверяем, имеет ли текущий по
     * льзователь доступ к предоставл
     * яемой нами информации или нет,
     * а также выполняем соответствую
     * щие полученным фактам действия
     *
     * Предотсавляем доступ в случае,
     * если текущий пользователь:
     * - Администратор системы
     * - Преподаватель автора решения
     * - Автор решения
     *
     * @param int $userId Идентификатор редактируемого пользователя
     * @param bool $canSameUserAccess
     * @return bool Разрешение или запрет на внесение изменений или доступ к сервису
     */

    public static function CheckAccessPermissionsForEdit(int $userId, bool $canSameUserAccess = true) : bool
    {

        /*
         * Получаем информацию об
         * указанном пользователе
         */

        $user_info = UserInfo::getUserInfo($userId);

        /*
         * Производим необходимые проверки
         * и возвращаем  результат  работы
         * данной функции.
         */

        return (

        (
            // Если мы разрешаем, то...
            $canSameUserAccess &&

            // Если это один и тот же пользователь
            $user_info['id'] == self::getCurrentSession()["user_info"]->getUserId()
        ) ||

            // Если текущий пользователь - администратор
            self::CheckAccessPermissions(
                PERMISSION::ADMINISTRATOR,
                false
            ) ||

            // Если текущий пользователь - куратор указанного пользователя
            $user_info['teacherId'] == self::getCurrentSession()["user_info"]->getUserId()

        );

    }

    /**
     * @param string $errorId
     * @return void
     */

    public static function ThrowError(string $errorId) : void
    {

        switch (strtolower($errorId))
        {

            case "403":
                $error_message = _("Нажаль Ви не маєте права доступу до цього сервісу!");
                break;
            case "404":

            	/*
            	 * В  соревновательном   режиме
            	 * некоторые ошибки кардинально
            	 * меняют свой смысл...
            	 */

            	if (UserInfo::IsAuthUser() && Olymp::IsAssociatedWithOlymp())
				{

					// Переопределяем заголовок
					header('location: ' . _SPM_ . 'index.php', true);

					// Завершаем работу текущего экземпляра веб-приложения
					exit;

				}

                $error_message = _("Упс... Такої сторінки тут немає!");
                break;
            case "db":
                $error_message = _("Ой-ой! Щось трапилося при підключення до БД!");
                break;
            case "input":
                $error_message = _("Дані надані не вірно, цей запит не буде оброблений!");
                break;
            default:
                $error_message = (string)$errorId;
                break;

        }

        /*
         * Отображаем страницу с информацией.
         */

		(new LayoutManager("message"))->DisplayLayout(
        	$error_message,
			"SimplePM",
			true
		);

    }

    /**
     * @param array $post_variables
     * @return bool
     */

    public static function CheckPostDataIssetAndNotNull(array $post_variables) : bool
    {

        // Обнуляем счётчик существующих ключей
        $success_count = 0;

        /*
         * В цикле вычисляем количество
         * существующих  переменных  из
         * переданного нам списка.
         */

        foreach ($post_variables as $post_variable) {

            $success_count += (int)(
                isset($_POST[$post_variable]) &&
                $_POST[$post_variable] !== NULL
            );

        }

        /*
         * Проверяем, все ли переменные
         * существуют или нет и выводим
         * соответствующий результат.
         */

        return $success_count >= count($post_variables);

    }

	/**
	 * ФУНКЦИЯ ГЕНЕРАЦИИ ПАРОЛЯ ИЗ ДОПУСТИМЫХ СИМВОЛОВ
	 * @param int $max_chars Принимает значение количества элементов генерируемого пароля.
	 * @return string Возвращает сгенерированный пароль.
	 */
	public static function GenerateKeyCode(int $max_chars) : string
	{

		/*
		 * Массив допустимых символов в пароле
		 */

		$chars_arr = array(
			'a','b','c','d','e','f',
			'g','h','i','j','k','l',
			'm','n','o','p','r','s',
			't','u','v','x','y','z',
			'A','B','C','D','E','F',
			'G','H','I','J','K','L',
			'M','N','O','P','R','S',
			'T','U','V','X','Y','Z',
			'1','2','3','4','5','6',
			'7','8','9','0','_'
		);
		/*
		 * Временная переменная, хранящая пароль
		 */

		$pass = "";

		/*
		 * Цикл добавления символов пароля
		 */

		for($i = 0; $i < $max_chars; $i++) {

			/*
			 * Выбираем индекс элемента
			 * массива     $chars_array
			 * случайным образом/
			 */

			$index = rand(0, count($chars_arr) - 1);

			/*
			 * Добавляем  полученный
			 * символ в конец строки
			 * пароля.
			 */

			$pass .= $chars_arr[$index];
		}

		// Возвращаем сгенерированный пароль
		return $pass;

	}

}
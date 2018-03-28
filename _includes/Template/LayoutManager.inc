<?php

/*
 * Copyright (C) 2018, Yurij Kadirov.
 * All rights are reserved.
 * Licensed under Apache License 2.0 with additional restrictions.
 *
 * @Author: Yurij Kadirov
 * @Website: https://sirkadirov.com/
 * @Email: admin@sirkadirov.com
 * @Repo: https://github.com/SirkadirovTeam/SimplePM_Server
 */

class LayoutManager {

    private $layout_name;

    public function __construct(string $layout_name)
    {

        $this->layout_name = $layout_name;

        if (!self::CheckLayoutExists($this->layout_name)) {

            global $_CONFIG;

            $this->layout_name = $_CONFIG->getWebappConfig()["default_layout_name"];

        }

    }

    /**
     * @param string $include_path
     * @return void
     */

    public function DisplayService(string $include_path) : void
    {

        $layout = file_get_contents(
            self::GetLayoutPath(
                $this->layout_name
            )
        );

        

    }

    /**
     * @param string $layout_name
     * @return bool
     */

    public static function CheckLayoutExists(string $layout_name) : bool
    {
        return file_exists(
            self::GetLayoutPath(
                $layout_name
            )
        );
    }

    /**
     * @param string $layout_name
     * @return string
     */

    public static function GetLayoutPath(string $layout_name) : string
    {
        return _SPM_template_ . "Layouts/" . $layout_name . ".inc";
    }

    /**
     * @return string
     */

    public function getLayoutName() : string
    {
        return $this->layout_name;
    }

}
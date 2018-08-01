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

class SimpleImage {

    var $image;
    var $image_type;

    function load($filename) : void
    {

        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];

        if( $this->image_type == IMAGETYPE_JPEG )
        {
            $this->image = imagecreatefromjpeg($filename);
        }
        elseif( $this->image_type == IMAGETYPE_GIF )
        {
            $this->image = imagecreatefromgif($filename);
        }
        elseif( $this->image_type == IMAGETYPE_PNG )
        {
            $this->image = imagecreatefrompng($filename);
        }

    }

    function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 75, $permissions = null) : void
    {

        if ( $image_type == IMAGETYPE_JPEG )
        {
            imagejpeg($this->image, $filename, $compression);
        }
        elseif ( $image_type == IMAGETYPE_GIF )
        {
            imagegif($this->image, $filename);
        }
        elseif ( $image_type == IMAGETYPE_PNG )
        {
            imagepng($this->image, $filename);
        }

        if ( $permissions != null)
        {
            chmod($filename, $permissions);
        }

    }

    function output($image_type = IMAGETYPE_JPEG) : void
    {

        if( $image_type == IMAGETYPE_JPEG )
        {
            imagejpeg($this->image);
        }
        elseif( $image_type == IMAGETYPE_GIF )
        {
            imagegif($this->image);
        }
        elseif( $image_type == IMAGETYPE_PNG )
        {
            imagepng($this->image);
        }

    }

    function getWidth() : int
    {

        return imagesx($this->image);

    }

    function getHeight() : int
    {
        return imagesy($this->image);
    }

    function resizeToHeight($height) : void
    {

        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;

        $this->resize($width,$height);

    }

    function resizeToWidth($width) : void
    {

        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;

        $this->resize($width,$height);

    }

    function scale($scale) : void
    {

        $width = $this->getWidth() * $scale/100;
        $height = $this->getheight() * $scale/100;

        $this->resize($width,$height);

    }

    function resize($width,$height) : void
    {

        $new_image = imagecreatetruecolor($width, $height);

        imagecopyresampled(
            $new_image,
            $this->image,
            0,
            0,
            0,
            0,
            $width,
            $height,
            $this->getWidth(),
            $this->getHeight()
        );

        $this->image = $new_image;

    }

}

?>
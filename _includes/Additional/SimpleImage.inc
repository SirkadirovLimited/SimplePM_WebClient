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
 * Copyright 2018 Yurij Kadirov
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
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
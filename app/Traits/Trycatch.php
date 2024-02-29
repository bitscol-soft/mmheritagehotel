<?php

namespace App\Traits;

trait Trycatch
{

    public function load($data = array())
    {
        try {
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

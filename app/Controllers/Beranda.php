<?php

namespace App\Controllers;

class Beranda extends BaseController
{
    public function getIndex(): string
    {
        $pengaturan = config('Pengaturan');
        return view('beranda', ['pengaturan' => $pengaturan]);
    }
}

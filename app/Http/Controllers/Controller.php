<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    // Menyediakan $this->authorize() untuk mengecek Policy di controller turunan.
    use AuthorizesRequests;
}

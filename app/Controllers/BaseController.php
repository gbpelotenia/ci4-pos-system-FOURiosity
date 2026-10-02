<?php

namespace App\Controllers;

use CodeIgniter\Controller;

abstract class BaseController extends Controller
{
    protected $helpers = ['url', 'form'];

    protected function redirectBackWithError(string $message)
    {
        return redirect()->back()->withInput()->with('error', $message);
    }

    protected function redirectBackWithSuccess(string $message)
    {
        return redirect()->back()->with('success', $message);
    }
}

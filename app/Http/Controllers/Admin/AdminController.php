<?php


namespace App\Http\Controllers\Admin;


class AdminController
{
    public function index()
    {
        return view('admin/home');
    }
    public function course()
    {
        return view('admin/course');
    }
}
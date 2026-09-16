<?php

namespace App\Http\Controllers;

class Controller
{
    protected function authorize($action)
    {
        $user = auth()->user();
        
        if ($action === 'isAdmin' && (!$user || !$user->isAdmin())) {
            abort(403, 'Unauthorized action.');
        }
    }
}

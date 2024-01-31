<?php

namespace App\Controller;

use App\Core\View;

class Alert {
    public static function getSuccess($message) {
        return View::render('alert/status', [
            'alertType' => 'success',
            'message' => $message
        ]);
    }

    public static function getError($message) {
        return View::render('alert/status', [
            'alertType' => 'danger',
            'message' => $message
        ]);
    }
}
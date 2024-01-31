<?php

require './bootstrap/app.php';

$obRouter = new \App\Http\Router(URL);

include __DIR__.'/routes/pages.php';
include __DIR__.'/routes/admin/admin.php';

$obRouter->run()->sendResponse();
<?php

require './bootstrap/app.php';

$obRouter = new \App\Http\Router(URL);

include __DIR__.'/routes/pages.php';

$obRouter->run()->sendResponse();
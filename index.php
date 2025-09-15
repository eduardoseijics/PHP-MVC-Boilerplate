<?php

require './bootstrap/app.php';

$obRouter = new \App\Http\Router(URL, $container);

include __DIR__.'/routes/admin.php';

include __DIR__.'/routes/pages.php';

$obRouter->run()->sendResponse();
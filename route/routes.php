<?php

use App\Controllers\EventController;
use Wpint\Route\Enums\RouteHttpMethodEnum;
use Wpint\Support\Facades\WebRoute;

WebRoute::method(RouteHttpMethodEnum::GET)
->name('events')
->path('/events')
->controller([EventController::class, 'index']);


WebRoute::method(RouteHttpMethodEnum::GET)
->name('events.show')
->path('/events/{event}')
->controller([EventController::class, 'show']);



WebRoute::method(RouteHttpMethodEnum::POST)
->name('events.attend')
->path('/events/{event}/attend')
->controller([EventController::class, 'attend']);



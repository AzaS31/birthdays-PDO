<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Render;

class AboutController {
    public function actionIndex() {
    $phone = '+789786786786';
    $render = new Render();
    return  $render->renderPage('about.twig', [
        'phone' => $phone
    ]);
    }
}
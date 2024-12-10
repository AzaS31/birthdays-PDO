<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\User;

class UserController {

    public function actionIndex(): string {
        $users = User::getAllUsersFromStorage();
        
        $render = new Render();

        if(!$users){
            return $render->renderPage(
                'user-empty.twig', 
                [
                    'title' => 'Список пользователей в хранилище',
                    'message' => "Список пуст или не найден"
                ]);
        }
        else{
            return $render->renderPage(
                'user-index.twig', 
                [
                    'title' => 'Список пользователей в хранилище',
                    'users' => $users
                ]);
        }
    }

    public function actionSave(): string {
        if(User::validateRequestData()) {
            $user = new User();
            $user->setParamsFromRequestData();
            $user->saveToStorage();

            $render = new Render();

            return $render->renderPage(
                'user-created.twig', 
                [
                    'title' => 'Пользователь создан',
                    'message' => "Создан пользователь " . $user->getUserName() . " " . $user->getUserLastName()
                ]);
        }
        else {
            throw new \Exception("Переданные данные некорректны");
        }
    }

    public function actionUpdate(): string {
        if (isset($_GET['id']) && User::exists($_GET['id'])) {
            $user = new User();
            $user->setUserId($_GET['id']);

            $arrayData = [];
    
            if (isset($_GET['name'])) {
                $arrayData['user_name'] = htmlspecialchars($_GET['name']);
            }
    
            if (isset($_GET['lastname'])) {
                $arrayData['user_lastname'] = htmlspecialchars($_GET['lastname']);
            }
    
            if (!empty($arrayData)) {
                $user->updateUser($arrayData);
            }
    
            $render = new Render();
            return $render->renderPage(
                'user-updated.twig', 
                [
                    'title' => 'Пользователь обновлен',
                    'message' => "Обновлен пользователь с ID: " . $user->getUserId()
                ]
            );
        } else {
            throw new \Exception("Пользователь не существует");
        }
    }
    

    public function actionDelete(): string {
        if (isset($_GET['id'])) {
            $userId = $_GET['id'];

            if (User::exists($userId)) {
                User::deleteFromStorage($userId);
    
                $render = new Render();
                return $render->renderPage(
                    'user-removed.twig', 
                    [
                        'title' => 'Пользователь удален',
                        'message' => "Пользователь с ID $userId был удален."
                    ]
                );
            } else {
                throw new \Exception("Пользователь с ID $userId не существует");
            }
        } else {
            throw new \Exception("Не указан ID пользователя");
        }
    }
    
}
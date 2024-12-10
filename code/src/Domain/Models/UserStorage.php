<?php
namespace Geekbrains\Application1\Domain\Models;

class UserStorage {

    public static function saveUser(User $user): bool {
        $address = $_SERVER['DOCUMENT_ROOT'] . User::getStorageAddress();
        $file = fopen($address, 'a');
        if ($file) {
            $userString = $user->getUserName() . "," . date("Y-m-d", $user->getUserBirthday()) . "\n";
            fwrite($file, $userString);
            fclose($file);
            return true;
        }
        return false;
    }

    public static function getAllUsersFromStorage(): array {
        $address = $_SERVER['DOCUMENT_ROOT'] . User::getStorageAddress();
        $users = [];

        if (file_exists($address)) {
            $file = fopen($address, 'r');
            while (($line = fgets($file)) !== false) {
                $data = explode(',', $line);
                if (count($data) == 2) {
                    $name = trim($data[0]);
                    $birthday = strtotime(trim($data[1]));
                    $users[] = new User($name, $birthday);
                }
            }
            fclose($file);
        }
        
        return $users;
    }
}

<?php
declare(strict_types=1);

namespace App;

class Authorization
{
    private Database $database;
    private Session $session;
    private Cookie $cookie;

    public function __construct(Database $database, Session $session, Cookie $cookie)
    {
        $this->database = $database;
        $this->session = $session;
        $this->cookie = $cookie;
    }

    public function register(array $data): bool
    {
        if (empty($data["username"])) {
            throw new AuthorizationException("Поле логин не заполнено!");
        }
        if (strlen($data["username"]) < 2) {
            throw new AuthorizationException("Длина логина должна быть больше 2х символов!");
        }
        if (strlen($data["username"]) > 20) {
            throw new AuthorizationException("Длина логина не должна быть более 20 символов!");
        }
        if (!preg_match('/^(?=.*\d)[a-zA-Z0-9]+$/', $data["username"])) {
            throw new AuthorizationException("Логин должен состоять из латинских букв и цифр!");
        }
        if (empty($data["password"])) {
            throw new AuthorizationException("Поле пароль не заполнено!");
        }
        if (strlen($data["password"]) < 5) {
            throw new AuthorizationException("Пароль должен состоять более чем из 5 символов!");
        }
        if (!preg_match('/^(?=.*\d)[a-zA-Z0-9]+$/', $data["password"])) {
            throw new AuthorizationException("Пароль должен содержать как символы, так и цифры!");
        }

        $statement = $this->database->getConnection()->prepare(
            'SELECT * FROM user WHERE username = :username'
        );

        $statement->execute([
            "username" => $data["username"]
        ]);
        $user = $statement->fetch();
        if (!empty($user)) {
            throw new AuthorizationException("Пользователь с таким именем уже существует!");
        }

        $statement = $this->database->getConnection()->prepare(
            'INSERT INTO user (username, password) VALUES (:username, :password)'
        );

        $statement->execute([
            "username" => $data["username"],
            "password" => password_hash($data["password"], PASSWORD_BCRYPT)
        ]);

        return true;
    }

    public function login(array $data)
    {
        if (empty($data["username"])) {
            throw new AuthorizationException("Поле логин не заполнено!");
        }

        if (empty($data["password"])) {
            throw new AuthorizationException("Поле пароль не заполнено!");
        }

        $statement = $this->database->getConnection()->prepare(
            'SELECT * FROM user WHERE username = :username'
        );
        $statement->execute([
            "username" => $data["username"]
        ]);
        $user = $statement->fetch();

        if (empty($user)) {
            throw new AuthorizationException("Такого пользователя не существует!");
        }

        if (password_verify($data["password"], $user["password"])) {
            $this->session->setData("username", [
                "username" => $user["username"]
            ]);

            $this->cookie->setData("username", $user["username"]);

            return true;
        } else {
            throw new AuthorizationException("Не правильный пароль!");
        }
    }

    public function validateCookie($name):bool {
        $statement = $this->database->getConnection()->prepare(
            'SELECT * FROM user WHERE username = :username'
        );
        $statement->execute([
            "username" => $name
        ]);
        $user = $statement->fetch();

        return !empty($user);
    }
}
<?php

namespace App;

class Cookie
{
    public function setData(string $name, $value): void
    {
        setcookie($name, $value);
    }

    public function getData(string $name): mixed
    {
        return !empty($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }

    public function unset(string $name) {
        if (isset($_COOKIE[$name])) {
            setcookie($name, '', time() - 3600);
            unset($_COOKIE[$name]);
        }
    }
}
<?php
class Pengguna
{
    public function __construct(
        protected string $nama,
        protected string $email,

    ) {}
}

$p = new Pengguna("subahehe","subahehe@gmail.com");
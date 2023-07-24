<?php

use Asaa\Auth\Auth;
use Asaa\Auth\Authenticatable;

function auth(): ?Authenticatable
{
    return Auth::user();
}

function isGuest(): bool
{
    return Auth::isGuest();
}

<?php

class User extends Model
{
    //protected $table = 'user';

    protected $allowedColumns = [
        'password',
        'email'
    ];

    
}
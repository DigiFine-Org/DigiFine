<?php

class Credentials
{
    public string $user_id;
    public string $password;
}

class AdminAuth
{
    private static $credentials = [];

    public static function add_credential(string $user_id, string $password)
    {
        AdminAuth::$credentials[$user_id] = password_hash(
            $password,
            PASSWORD_DEFAULT
        );
    }
    public static function check_credential(string $user_id, string $password): bool|null
    {
        if (!array_key_exists($user_id, AdminAuth::$credentials))
            return null;

        return password_verify($password, AdminAuth::$credentials[$user_id]);
    }

    // public static function print()
    // {
    //     var_dumpml(AdminAuth::$credentials);
    // }

}

AdminAuth::add_credential('DigiAdmin27', '123456');

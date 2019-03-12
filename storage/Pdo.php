<?php

namespace filsh\yii2\oauth2server\storage;

class Pdo extends \OAuth2\Storage\Pdo
{
    public $dsn;

    public $username;

    public $password;

    public $connection = 'db';

    public $storageSchema = '';

    public function __construct($connection = null, $config = array())
    {
        if ($connection === null) {
            if ($this->connection !== null && \Yii::$app->has($this->connection)) {
                $db = \Yii::$app->get($this->connection);
                if (!($db instanceof \yii\db\Connection)) {
                    throw new \yii\base\InvalidConfigException('Connection component must implement \yii\db\Connection.');
                }

                if (!$db->getIsActive()) {
                    $db->open();
                }

                if(!empty($this->storageSchema)) {
                    $this->storageSchema .= '.';
                }

                $connection = $db->pdo;
                $config = array_merge(array(
                    'client_table' => $this->storageSchema . 'oauth_clients',
                    'access_token_table' => $this->storageSchema . 'oauth_access_tokens',
                    'refresh_token_table' => $this->storageSchema . 'oauth_refresh_tokens',
                    'code_table' => $this->storageSchema . 'oauth_authorization_codes',
                    'user_table' => $this->storageSchema . 'oauth_users',
                    'jwt_table' => $this->storageSchema . 'oauth_jwt',
                    'jti_table' => $this->storageSchema . 'oauth_jti',
                    'scope_table' => $this->storageSchema . 'oauth_scopes',
                    'public_key_table' => $this->storageSchema . 'oauth_public_keys',
                ), $config);

            } else {
                $connection = [
                    'dsn' => $this->dsn,
                    'username' => $this->username,
                    'password' => $this->password
                ];
            }
        }

        parent::__construct($connection, $config);
    }
}

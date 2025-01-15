<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $fio
 * @property string $login
 * @property string $email
 * @property string $password
 * @property int $admin
 *
 * @property Problem[] $problems
 */
class RegForm extends User
{

    public $agree;
    public $passwordConfirm;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fio', 'login', 'email', 'password', 'passwordConfirm', 'agree'], 'required', 'message'=>'Поле обязятельно для заполнения'],
            ['fio', 'match', 'pattern' => '/^[А-Яа-я\s\-]{5,}$/u', 'message'=>'Только кириллица, пробелы и тире не менее 5 символов'],
            ['login', 'match', 'pattern' => '/^[A-Za-z]{1,}$/u', 'message'=>'Только латинские символы'],
            ['login', 'unique', 'message'=>'Такой логин уже используется'],
            ['email', 'email', 'message' => 'Heкoppeктный email'],
            ['passwordConfirm', 'compare', 'compareAttribute' => 'password', 'message'=>'Пароли не совпадат'], ['agree', 'boolean'],
            ['agree', 'compare', 'compareValue' => true, 'message' => 'Heoбxодимо согласиe'],
            [['admin'], 'integer'],
            [['fio', 'login', 'email', 'password'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fio' => 'ФИО',
            'login' => 'Логин',
            'email' => 'Email',
            'password' => 'Пароль',
            'admin' => 'Роль администратора',
            'passwordConfirm' => 'Подтверждение пароля',
            'agree' => 'Даю согласие на обработку',
        ];
    }

    
}

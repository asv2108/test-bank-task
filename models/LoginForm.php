<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;


/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
	/**
	 * @var
	 */
	public $username;

	/**
	 * here we have username after called getUser()
	 *
	 * @var bool
	 */
	private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username is required
            ['username', 'required'],
        ];
    }


    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
        	$user_data = $this->getUser();
        	if(isset($user_data)){
				return Yii::$app->user->login($user_data,0);
			}else{

				$user=new User;
				$user->username=$this->username;
				$user->auth_key = \Yii::$app->security->generateRandomString();
				$user->save();
				//$user_data = $this->getUser();
				//return Yii::$app->user->login($user_data,0);
				Yii::$app->response->redirect(Url::to('invite'));
			}
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

}

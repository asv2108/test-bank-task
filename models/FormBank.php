<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class FormBank
 * @package app\models
 */
class FormBank extends Model
{
	/**
	 * user's name for send money
	 *
	 * @var
	 */
	public $username;

	/**
	 * value money which user send to another user
	 *
	 * @var
	 */
	public $sum;

	/**
	 * form validate rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			// username and sum fields are required
				[['username','sum'],'required'],
				['sum', 'double'],
		];
	}


	/**
	 * if value is not minus we put some value to necessary user and take of value from sending
	 *
	 * @return bool
	 */
	public function get()
	{

		if($this->sum>0)
		{
			$user_to=User::findByUsername($this->username);

			// if the necessary user does not already exist we create one into the db
			if(!$user_to)
			{
				$user=new User;
				$user->username=$this->username;
				$user->auth_key = \Yii::$app->security->generateRandomString();
				$user->save();
				$id=Yii::$app->db->getLastInsertID();
			}
			else
			{
				$id=$user_to['id'];
			}

			//put on the balance
			$bank=new Bank;
			$bank->user_id=$id;
			$bank->sum=round($this->sum,2);
			$bank->save();
			$id=Yii::$app->db->getLastInsertID();

			// take off the balance
			$use=new Bank;
			$use->user_id=Yii::$app->user->identity['id'];
			$use->parent_id=$id;
			$use->sum=round($this->sum,2)-(round($this->sum,2)*2);
			$use->save();
			$id2=Yii::$app->db->getLastInsertID();

			// find last put balance row id and set it for link with last take of the balance row
			$use=$use->findId($id);
			$use->parent_id=$id2;
			$use->update();

			return true;
		}
		else
		{
			return false;
		}
	}

}
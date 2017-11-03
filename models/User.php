<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
	/**
	 * return table name
	 *
	 * @return string
	 */
	public static function tableName()
	{
		return 'user';
	}

	/**
	 * validation rule for form field
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			[
				['username'],
				'string',
				'max'=>50
			],

		];

	}

	public function login(IdentityInterface $identity, $duration = 0){

	}

	/**
	 * Finds an identity by the given ID.
	 *
	 * @param string|int $id the ID to be looked for
	 * @return IdentityInterface|null the identity object that matches the given ID.
	 */
	public static function findIdentity($id)
	{
		return static::findOne($id);
	}


	/**
	 * get info about all users and their balances from db
	 *
	 * @return array
	 */
	public static function qUserbank()
	{
		return Yii::$app->db->createCommand('select username,(select sum(sum) from bank where bank.user_id=user.id) as balance from user')->queryAll();
	}
	
	/**
	 * get all history balance about logged user
	 *
	 * @return array
	 */
	public static function UserHistoryBank()
	{
		return Yii::$app->db->createCommand('SELECT id,sum,
			(SELECT user_id FROM bank as bn where bn.id=bank.parent_id) AS user_idr,
			(select username from user where user.id=user_idr) as info,
			\'in\' as status
			 FROM bank where user_id = :id AND sum > 0  
			 UNION
			 SELECT id,sum,
			(SELECT user_id FROM bank as bn where bn.id=bank.parent_id) AS user_idr,
			(select username from user where user.id=user_idr) as info,
			\'out\' as status
			 FROM bank where user_id = :id AND sum < 0')
			->bindValue(':id',Yii::$app->user->identity['id'])
			->queryAll();
	}

	/**
	 * Finds an identity by the given token.
	 *
	 * @param string $token the token to be looked for
	 * @return IdentityInterface|null the identity object that matches the given token.
	 */
	public static function findIdentityByAccessToken($token,$type=null)
	{
		return static::findOne(['access_token'=>$token]);
	}

	/**
	 * @param $un
	 * @return static
	 */
	public static function findByUsername($un)
	{
		return static::findOne(['username'=>$un]);
	}


	/**
	 * @return int|string current user ID
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string current user auth key
	 */
	public function getAuthKey()
	{
		return $this->auth_key;
	}

	/**
	 * @param string $authKey
	 * @return bool if auth key is valid for current user
	 */
	public function validateAuthKey($authKey)
	{
		return $this->getAuthKey()===$authKey;
	}
}

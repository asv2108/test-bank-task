<?php
namespace models;

use app\models\Bank;
use app\models\FormBank;
use app\models\User;
use Yii;

/**
 * Class FormBankTest
 * @package models
 */
class FormBankTest extends \Codeception\Test\Unit
{
	/**
	 * check the require field - user name
	 */
	public function testUserNameFieldRequired()
    {
		$model = new FormBank;
		$model->load(array(
			'FormBank'=>array(
				'username'=>'',
				'sum'=>11
			)
		));
		$model->validate();
		expect('Username is require',$model->errors)->hasKey('username');
    }

	/**
	 * check the require field - amount of payment
	 */
	public function testSumFieldRequired()
    {
		$model = new FormBank;
		$model->load(array(
			'FormBank'=>array(
				'username'=>'admin',
				'sum'=>''
			)
		));
		$model->validate();
		expect('Sum is require',$model->errors)->hasKey('sum');
    }

	/**
	 * check the field - amount of payment, only integer
	 */
	public function testSumFieldIsIntegerType()
	{
		$model = new FormBank;
		$model->load(array(
			'FormBank'=>array(
				'username'=>'admin',
				'sum'=>'string'
			)
		));
		$model->validate();
		expect('Sum is only integer',$model->errors)->hasKey('sum');
	}

	/**
	 * check amount of payment is a negative number
	 */
	public function testSumIsNegativeNumber(){
		$model = new FormBank;
		$model->load(array(
			'FormBank'=>array(
				'username'=>'admin',
				'sum'=>'-11'
			)
		));
		verify($model->sum)->lessThan(1);
	}

	/**
	 * check - get an user, whom payer sends payment
	 */
	public function testGetReceiver(){
		$model = new FormBank;
		$model->load(array(
			'FormBank'=>array(
				'username'=>'admin',
				'sum'=>'11'
			)
		));
		verify(User::findByUsername($model->username))->hasKey('id');
	}

	/**
	 * check the possibility of creating a new receiver
	 */
	public function testCreateNewReceiver(){
		$model = new FormBank;
		$model->load(array(
			'FormBank'=>array(
				'username'=>'new_receiver',
				'sum'=>'11'
			)
		));
		$user = User::find()
			->where(['username' => $model->username])
			->one();
		if($user){
			$user->delete();
		}

		$user_new = new User();
		$user_new->username = $model->username;
		$user_new->auth_key = 1;
		$id = $user_new->save();
		verify($id)->true();
	}

	/**
	 * check the rounding of the number under two decimal places
	 */
	public function testRoundingUpToTwoCharacters(){
		verify((string)round(22.33315,2))->contains('.33');
	}

	/**
	 * check the creation of a test transaction
	 */
	public function testSaveTransactionPutOn(){

		$model = new FormBank;
		Bank::deleteAll();
		$model->load(array(
			'FormBank'=>array(
				'username'=>'admin',
				'sum'=>'11.333'
			)
		));
		$user_to=User::findByUsername($model->username);
		$bank=new Bank();
		$bank->user_id=$user_to['id'];
		$bank->sum=round($model->sum,2);
		$bank->save();
		$id=Yii::$app->db->getLastInsertID();

		expect('Get id last inserted put on row',$id)->notNull();
		$bank->findId($id);
		$bank->parent_id = $id+=1;
		$bank->update();
		$id2=Yii::$app->db->getLastInsertID();
		expect('Get id last updated row after set link with take off row',$id2)->notNull();
	}

	/**
	 * check the creation of a negative number
	 */
	public function testCreateNegativeNumber(){
		verify((string) (22-(22*2)))->contains('-22');
	}

}
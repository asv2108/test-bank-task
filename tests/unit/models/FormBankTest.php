<?php
namespace models;

use app\models\Bank;
use app\models\FormBank;
use app\models\User;
use Yii;

class FormBankTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;


    protected function _before()
    {
    }

    protected function _after()
    {
    }

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

	public function testSumFieldType()
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

	public function testSaveTransactionPutOn(){
		$model = new FormBank;
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
		verify($id)->true();
	}

}
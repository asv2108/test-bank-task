<?php
namespace tests\models;
use app\models\User;

class UserTest extends \Codeception\Test\Unit
{
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected function _before()
	{
		$user = User::find()->where(['username'=>'oleg'])->one();
		if($user){
			$user->delete();
		}
	}


	public function testFindUserById()
    {
        expect_that($user = User::findIdentity(57));
        expect($user->username)->equals('ttt');

        expect_not(User::findIdentity(999));
    }

    public function testFindUserByUsername()
    {
		expect_that($user = User::findByUsername('admin'));
        expect_not(User::findByUsername('not-admin'));
    }

	function testSavingUser()
	{
		$user = new User();
		$user->username = 'oleg';
		$user->save();
		$this->assertEquals('oleg', $user->username);
	}
}

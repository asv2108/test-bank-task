<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\FormBank;
use app\models\LoginForm;
use yii\data\ArrayDataProvider;


/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access'=>[
				'class'=>AccessControl::className(),
				'only'=>['logout'],
				'rules'=>[
					[
						'actions'=>['logout'],
						'allow'=>true,
						'roles'=>['@'],
					],
				],
			],
			'verbs'=>[
				'class'=>VerbFilter::className(),
				'actions'=>[
					'logout'=>['post'],
				],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions()
	{
		return [
			'error'=>[
				'class'=>'yii\web\ErrorAction',
			],
			'captcha'=>[
				'class'=>'yii\captcha\CaptchaAction',
				'fixedVerifyCode'=>YII_ENV_TEST?'testme':null,
			],
		];
	}

	/**
	 * Displays Bunk puy Page for logged user or home page.
	 *
	 * @return string
	 */
	public function actionIndex()
	{
		$model= new FormBank;
		if($model->load(Yii::$app->request->post()))
		{
			if(!$model->get())
			{
				return $this->render('error-bank');
			}
			else
			{
				return $this->render('success-bank');
			}
		}

		if(Yii::$app->user->isGuest)
		{
			return $this->render('index');
		}
		else
		{
			return $this->render('form-bank',['model'=>$model]);
		}
	}

	/**
	 * Display page with user's bank history
	 *
	 * @return string|Response
	 */
	public function actionMyBank(){

		if(Yii::$app->user->isGuest)
		{
			return $this->goHome();
		}

		$data=new ArrayDataProvider([
			'allModels'=>User::UserHistoryBank(),
			'sort'=>[

			],
			'pagination'=>[
				'pageSize'=>500,
			],
		]);
		return $this->render('my-bank',['data'=>$data]);
	}

	/**
	 * Login action.
	 *
	 * @return Response|string
	 */
	public function actionLogin()
	{
		if(!Yii::$app->user->isGuest)
		{
			return $this->goHome();
		}

		$model=new LoginForm();
		if($model->load(Yii::$app->request->post())&&$model->login())
		{
			return $this->goBack();
		}
		return $this->render('login',[
			'model'=>$model,
		]);
	}

	public function actionInvite(){
		return $this->render('invite');
	}

	/**
	 * Logout action.
	 *
	 * @return Response
	 */
	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}

	/**
	 * Displays all users with their balances
	 *
	 * @return Response|string
	 */
	public function actionBank()
	{
		$data=new ArrayDataProvider([
			'allModels'=>User::qUserbank(),
			'sort'=>[
				'attributes'=>[
					'id',
					'name'
				],
				//'defaultOrder'=>['name'=>SORT_ASC]
			],
			'pagination'=>[
				'pageSize'=>500,
			],
		]);


		return $this->render('bank',['data'=>$data]);
	}


}

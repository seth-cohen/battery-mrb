<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
			{
				$this->redirect(Yii::app()->user->returnUrl);
			}
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}
	
	/**
	 * Displays the change password page, when requried
	 */
	public function actionChangePassword($returnUrl, $user_id)
	{
		$model=new ChangePasswordForm;
		$user = User::model()->findByPk($user_id);
		$model->username = $user->username;
		
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='password-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['ChangePasswordForm']))
		{
			$model->attributes=$_POST['ChangePasswordForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate())
			{
				$user->password = CPasswordHelper::hashPassword($model->password);
				$user->change_password = 0;
				
				if($user->save())
				{
					$model->_identity=new UserIdentity($user->username,$model->password);
					$model->_identity->authenticate();
					$model->login();
					
					// redirect to where they came from
					$this->redirect($returnUrl);
				}
			}
		}
		// display the login form
		$this->render('changepassword',array('model'=>$model));
	}
	
	/**
	 * Displays the registration page
	 */
	public function actionRegister()
	{
		$model=new RegistrationForm;
		$department = new Department;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='registration-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['RegistrationForm']))
		{
			$model->attributes=$_POST['RegistrationForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate())
			{
				$user = new User;
				$user->attributes = $model->attributes;
				
				if($user->save()){
					$identity = new UserIdentity($user->username, $user->password);
					$identity->authenticate();
					Yii::app()->user->login($identity,0);
					
					// redirect to where they came from
					$this->redirect(Yii::app()->user->returnUrl);
				}
			}
		}
		
		// display the login form
		$this->render('register',array('model'=>$model, 'department'=>$department));
	}
	

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
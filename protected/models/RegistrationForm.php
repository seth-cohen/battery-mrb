<?php

/**
 * Registration form class.
 * RegistrationForm is the data structure for keeping
 * user data. It is used by the 'register' action of 'SiteController'.
 */
class RegistrationForm extends User
{
	public $verifyPassword;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password, verifyPassword, first_name, last_name, depart_id', 'required'),
			array('username, password, first_name, last_name', 'length', 'max'=>50),
			array('username', 'unique', 'message'=>'This username already exists'),
			array('verifyPassword', 'compare', 'compareAttribute'=>'password', 'message' => 'Passwords do not match'),
			array('email', 'unique', 'message'=>'This email address already exists'),
			array('depart_id','length','max'=>10),
			array('email', 'length', 'max'=>128),
			array('email','email'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'depart_id'=>'Department',
		);
	}

	/**
	 * Registers a new user using the given username and password in the model.
	 */
	public function register()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}

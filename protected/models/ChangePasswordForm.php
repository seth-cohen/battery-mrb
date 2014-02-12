<?php

/**
 * Registration form class.
 * RegistrationForm is the data structure for keeping
 * user data. It is used by the 'register' action of 'SiteController'.
 */
class ChangePasswordForm extends CFormModel
{
	public $password;
	public $verifyPassword;
	public $username;
	public $rememberMe;
	
	public $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password, verifyPassword', 'required'),
			array('rememberMe', 'boolean'),
			array('username, password', 'length', 'max'=>50),
			array('verifyPassword', 'compare', 'compareAttribute'=>'password', 'message' => 'Passwords do not match'),
		);
	}

	
	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
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
}

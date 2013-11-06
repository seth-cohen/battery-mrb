<?php

/**
 * This is the model class for table "tbl_user".
 *
 * The followings are the available columns in table 'tbl_user':
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $depart_id
 *
 * The followings are the available model relations:
 * @property Cell[] $cellsStacked
 * @property Cell[] $cellsFilled
 * @property Cell[] $cellsInspected
 * @property Cycler[] $cyclersCalibrated
 * @property FormationDetail[] $formationDetails
 * @property Kit[] $kits
 * @property Department $depart
 */
class User extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, first_name, last_name', 'required'),
			array('username, password, first_name, last_name', 'length', 'max'=>50),
			array('username','unique', 'message'=>'Username is already taken'),
			
			array('email','unique', 'message'=>'Email address is already taken'),
			array('email', 'length', 'max'=>128),
			array('depart_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, username, password, first_name, last_name, email, depart_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'cellsStacked' => array(self::HAS_MANY, 'Cell', 'stacker_id'),
			'cellsFilled' => array(self::HAS_MANY, 'Cell', 'filler_id'),
			'cellsInpected' => array(self::HAS_MANY, 'Cell', 'inspector_id'),
			'cyclersCalibrated' => array(self::HAS_MANY, 'Cycler', 'calibrator_id'),
			'formationDetails' => array(self::HAS_MANY, 'FormationDetail', 'operator_id'),
			'kits' => array(self::HAS_MANY, 'Kit', 'kitter_id'),
			'depart' => array(self::BELONGS_TO, 'Department', 'depart_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'email' => 'Email',
			'depart_id' => 'Depart',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('depart_id',$this->depart_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function beforeSave(){
		if ($this->isNewRecord)
		{
			$this->password = CPasswordHelper::hashPassword($this->password);
		}
		return parent::beforeSave();
	}
	
	public function validatePassword($password)
	{
		return CPasswordHelper::verifyPassword($password, $this->password);
	}
	
	public function hashPassword($password)
	{
		
		return CPasswordHelper::hashPassword($password);
	}
}

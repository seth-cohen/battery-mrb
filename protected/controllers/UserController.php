<?php

class UserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'roles'=>array('engineering tech')
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow',	
				'actions'=>array('ajaxusersearch'),
				'roles'=>array('manufacturing supervisor'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','ajaxassignrole'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = User::model()->with(array(
			'cellsStacked'=>array('with'=>array(
				'kit', 
				'kit.celltype', 
				'kit.anodes', 
				'kit.cathodes'
			)),
		))->findByPk($id);

		if($model == null)
		{
			$model = User::model()->findByPk($id);
		}
		$roles = array();
		$cells = array();
		
		if(!empty($model->roles))
		{
			foreach($model->roles as $key=>$role){
				$roles[] = array('id'=>$key+1, 'role'=>$role->name);
			}
		}		
		$roleDataProvider = new CArrayDataProvider($roles);
		
		if(!empty($model->cellsStacked))
		{
			foreach($model->cellsStacked as $key=>$cell){
				$cells[] = array('num'=>$key+1, 'serial'=>$cell->kit->getFormattedSerial(), 'id'=>$cell->id);
			}
		}	
		$cellDataProvider = new CArrayDataProvider($cells);
	
		$this->render('view',array(
			'model'=>$model,
			'roleDataProvider'=>$roleDataProvider,
			'cellDataProvider'=>$cellDataProvider,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new User;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save()){
				foreach($_POST['User']['roleIds'] as $role)
				{
					$commandInsert->insert('tbl_user_role', array(
						'user_id'=>$model->id,
						'role_id'=>$role,
					));
				}
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
			
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
			{
				/* clear the join table of roles */
				$commandDelete = Yii::app()->db->createCommand();
				$commandDelete->delete('tbl_user_role', 
					'user_id = :id',
					array(':id'=>$id)
				);
		
				/* add new roles list */
				foreach($_POST['User']['roleIds'] as $role)
				{
					$commandInsert = Yii::app()->db->createCommand();
					$commandInsert->insert('tbl_user_role', array(
						'user_id'=>$model->id,
						'role_id'=>$role,
					));
				}
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
			
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param User $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * ajax saving of the users roles.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionAjaxAssignRole()
	{	
		if(isset($_POST['id']))
		{
			$id = $_POST['id'];
			$roles = array();
						
			/* clear the join table of roles */
			$commandDelete = Yii::app()->db->createCommand();
			$commandDelete->delete('tbl_user_role', 
				'user_id = :id',
				array(':id'=>$id)
			);
				
			if(isset($_POST['roles']))
			{
				$roles = $_POST['roles'];
				/* save the roles in the join table */
				$commandInsert = Yii::app()->db->createCommand();
				foreach($roles as $role)
				{
					$commandInsert->insert('tbl_user_role', array(
						'user_id'=>$id,
						'role_id'=>$role,
					));
				}	
			}
		}
	}
	
	/**
	 * ajax user search
	 * @param string $term the term to search for
	 */
	public function actionAjaxUserSearch($term)
	{	
		$results = array();
		
		$criteria = new CDbCriteria;
		
		$criteria->compare('first_name',$term, true, 'OR');
		$criteria->compare('last_name',$term, true,'OR');
		
		$criteria->addCondition('id<>1');
		
		$criteria->order = 'last_name';
		$criteria->select = 'first_name, last_name, id';
		
		$users = User::model()->findAll($criteria);
		
		foreach ($users as $user){
			$results[] = array(
					'value'=>$user->last_name.', '.$user->first_name,
					'id'=>$user->id,
			);
		}
		
		echo json_encode($results);
		
		Yii::app()->end();
	}
	
}

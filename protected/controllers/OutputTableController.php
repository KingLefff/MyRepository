<?php

	class OutputTableController extends Controller
	{

		/**
		 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
		 * using two-column layout. See 'protected/views/layouts/column2.php'.
		 */
		public $layout = '//layouts/column4';


/*		public
		function init()
		{
			session_start();
			if (!isset($_SESSION['TableOne']))
				$_SESSION['TableOne'] = new OutputTable();

			if (!isset($_SESSION['TableTwo']))
				$_SESSION['TableTwo'] = new OutputTable();

			if (!isset($_SESSION['TableThree']))
				$_SESSION['TableThree'] = new OutputTable();


			parent::init();
		}*/

		/**
		 * @return array action filters
		 */
		public
		function filters()
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
		public
		function accessRules()
		{
			return array(
				array('allow',  // allow all users to perform 'index' and 'view' actions
				      'actions' => array('index',
				                         'view',
				                         'preview',
				                         'create',
				                         'update',
				                         'delete',
				                         'save',
				                         'reset',
				                         'template',
				                         'ListTable',
				                         'ListTableTwo',
				                         'ListTableThree',
				                         'SetColumn',
				                         'SampleData',
				                         'UnsetExp',
				                         'Test1',
				                         'Test2',
				                         'Test3',
				                         'GetDataCondition'
				                    ),
				      'roles'   => array('admin'),
				),
				array('deny',  // deny all users
				      'users' => array('*'),
				),
			);
		}

		public
		function actionReset()
		{
			session_destroy();
			$this->render('_reset');
		}

		public
		function actionIndex()
		{
//			session_unset();
//			session_reset();
			unset($_SESSION['TableOne']);
			unset($_SESSION['TableTwo']);
			unset($_SESSION['TableThree']);
			/*
			 * Yii::app()->authManager->isAssigned('Admin',Yii::app()->user->id)
			 * Yii::app()->user->name
			 *
			 */
			if (!isset($_SESSION['TableOne']))
				$_SESSION['TableOne'] = new OutputTable();

			if (!isset($_SESSION['TableTwo']))
				$_SESSION['TableTwo'] = new OutputTable();

			if (!isset($_SESSION['TableThree']))
				$_SESSION['TableThree'] = new OutputTable();

			$this->render('index2');
		}

		public
		function actionView()
		{
			$TableOne = '';
			$TableTwo = '';
			$TableThree = '';

			if (isset($_SESSION['TableOne']))
			{
				$TableOne = new OutputTable();
				$TableOne = $_SESSION['TableOne'];
			}
			if (isset($_SESSION['TableTwo']))
			{
				$TableTwo = new OutputTable();
				$TableTwo = $_SESSION['TableTwo'];
			}
			if (isset($_SESSION['TableThree']))
			{
				$TableThree = new OutputTable();
				$TableThree = $_SESSION['TableThree'];
			}

			if($TableOne->TableName != "" || $TableTwo->TableName != "" || $TableThree->TableName != "")
			{
				$this->render('view', array(
					'TableHeader' => $TableOne,
					'TableColumn' => $TableTwo,
					'TableCenter' => $TableThree
				));
			}else{
				$this->redirect(array('OutputTable/index'));
			}

		}

		public
		function actionCreate()
		{
			$Table = new OutputTable();
			$limit = mt_rand();
			$model = ['id' => $limit];
			$data = $this->getJsonInput();

			if (!empty($data))
			{
				switch ($data['class'])
				{
					case 'Header':
					{
						if (isset($_SESSION['TableOne']))
						{
							$Table = $_SESSION['TableOne'];
						}
						break;
					}
					case 'Column':
					{
						if (isset($_SESSION['TableTwo']))
						{
							$Table = $_SESSION['TableTwo'];
						}
						break;
					}
					case 'Center':
					{
						if (isset($_SESSION['TableThree']))
						{
							$Table = $_SESSION['TableThree'];
						}
						break;
					}
				}

				if(!$Table->isWhere() && sizeof($Table->condition) != 0)
					unset($Table->condition);

				$Table->SetCondition($limit, $data);
				$this->sendResponse(201, CJSON::encode($model));
			}
			else
			{
				$this->sendResponse(400, CJSON::encode(array('errors' => 'Заполните поля')));
			}
		}

		public
		function actionUpdate($id)
		{
			$Table = new OutputTable();
			$model = ['id' => $id];
			$data = $this->getJsonInput();

			if (!empty($data))
			{
				switch ($data['class'])
				{
					case 'Header':
					{
						if (isset($_SESSION['TableOne']))
						{
							$Table = $_SESSION['TableOne'];
						}
						break;
					}
					case 'Column':
					{
						if (isset($_SESSION['TableTwo']))
						{
							$Table = $_SESSION['TableTwo'];
						}
						break;
					}
					case 'Center':
					{
						if (isset($_SESSION['TableThree']))
						{
							$Table = $_SESSION['TableThree'];
						}
						break;
					}
				}

				$Table->SetCondition($id, $data);
				$this->sendResponse(201, CJSON::encode($model));
			}
			else
			{
				$this->sendResponse(400, CJSON::encode(array('errors' => 'Заполните поля')));
			}
		}

		public
		function actionDelete($id, $class)
		{
			$Table = new OutputTable();

			switch ($class)
			{
				case 'Header':
				{
					if (isset($_SESSION['TableOne']))
					{
						$Table = $_SESSION['TableOne'];
					}
					break;
				}
				case 'Column':
				{
					if (isset($_SESSION['TableTwo']))
					{
						$Table = $_SESSION['TableTwo'];
					}
					break;
				}
				case 'Center':
				{
					if (isset($_SESSION['TableThree']))
					{
						$Table = $_SESSION['TableThree'];
					}
					break;
				}
			}

			$Table->UnsetCondition($id);
			$this->sendResponse(200);
		}

		public
		function actionPreview()
		{

			$TableTwo = '';
			$TableOne = '';
			$TableThree = '';

			if (isset($_SESSION['TableOne']))
			{
				$TableOne = new OutputTable();
				$TableOne = $_SESSION['TableOne'];
			}
			if (isset($_SESSION['TableTwo']))
			{
				$TableTwo = new OutputTable();
				$TableTwo = $_SESSION['TableTwo'];
			}
			if (isset($_SESSION['TableThree']))
			{
				$TableThree = new OutputTable();
				$TableThree = $_SESSION['TableThree'];
			}
			$TableOne->UnsetConditionAll();
			$TableTwo->UnsetConditionAll();
			$TableThree->UnsetConditionAll();

			if ($TableOne->TableName != "" || $TableTwo->TableName != "" || $TableThree->TableName != "")
			{
				$this->render('preview', array(
					'TableHeader' => $TableOne,
					'TableColumn' => $TableTwo,
					'TableCenter' => $TableThree
				));
			}else{
				$this->redirect(array('OutputTable/index'));
			}
		}

		public
		function actionListTable()
		{
			static $TableOne;

			if (isset($_SESSION['TableOne']))
			{
				$TableOne = new OutputTable();
				$TableOne = $_SESSION['TableOne'];
			}

			if (isset($_POST['AllTable']))
			{
				$TableOne->TableName = $_POST['AllTable'];
				$list = $TableOne->GetAllTable();

				$key = array_search($TableOne->TableName, $list);
				if ($key !== false)
				{
					unset($list[$key]);

				}
				$_SESSION['TableOne'] = $TableOne;
				echo CHtml::dropDownList('Table1', '', $list, array('empty' => 'Выберите таблицу'));
			}
		}

		public
		function actionListTableTwo()
		{
			static $TableTwo;
			static $TableOne;

			if (isset($_SESSION['TableOne']))
			{
				$TableOne = new OutputTable();
				$TableOne = $_SESSION['TableOne'];
			}
			if (isset($_SESSION['TableTwo']))
			{
				$TableTwo = new OutputTable();
				$TableTwo = $_SESSION['TableTwo'];
			}

			if (isset($_POST['TwoTable']) and ($_POST['TwoTable'] != '' /*and $_POST['ThreeTable'] == ''*/))
			{
				$TableTwo->TableName = $_POST['TwoTable'];
				$_SESSION['TableTwo'] = $TableTwo;

				$list = $TableTwo->GetRelation($TableOne->TableName);

				echo CHtml::dropDownList('Table1', '', $list, array('empty' => 'Выберите таблицу'));
			}
		}

		public
		function actionListTableThree()
		{
			$TableOne = '';
			$TableTwo = '';
			$TableThree = '';

			if (isset($_SESSION['TableThree']))
			{
				$TableThree = new OutputTable();
				$TableThree = $_SESSION['TableThree'];
			}
			if (isset($_POST['ThreeTable']) and ($_POST['ThreeTable'] != ''))
			{
				$TableThree->TableName = $_POST['ThreeTable'];
				$_SESSION['TableThree'] = $TableThree;

			}

			if (isset($_SESSION['TableOne']))
			{
				$TableOne = new OutputTable();
				$TableOne = $_SESSION['TableOne'];
			}
			if (isset($_SESSION['TableTwo']))
			{
				$TableTwo = new OutputTable();
				$TableTwo = $_SESSION['TableTwo'];
			}

			$keys = OutputTable::GetKeyRelationsTables($TableOne->TableName, $TableThree->TableName);

			if (!empty($keys))
			{
				if ($TableOne->TableName == $keys['MAIN_TABLE'])
				{
					$TableOne->FkOne = $keys['PK_MAIN'];
					$TableThree->FkOne = $keys['PK_SUB'];
				}
				else
				{
					$TableOne->FkOne = $keys['PK_SUB'];
					$TableThree->FkOne = $keys['PK_MAIN'];
				}
			}

			$keys = OutputTable::GetKeyRelationsTables($TableTwo->TableName, $TableThree->TableName);

			if (!empty($keys))
			{
				if ($TableTwo->TableName == $keys['MAIN_TABLE'])
				{
					$TableTwo->FkOne = $keys['PK_MAIN'];
					$TableThree->FkTwo = $keys['PK_SUB'];
				}
				else
				{
					$TableTwo->FkOne = $keys['PK_SUB'];
					$TableThree->FkTwo = $keys['PK_MAIN'];
				}
			}
			unset($TableOne, $TableTwo, $TableThree, $keys);

		}

		public
		function actionSetColumn()
		{

			if (isset($_POST['DataTableHeader']) and !empty($_POST['DataTableHeader']))
			{
				$Table1 = new OutputTable();
				if (isset($_SESSION['TableOne']))
				{
					$Table1 = $_SESSION['TableOne'];
					$Table1->Column = $_POST['DataTableHeader'];
					if (isset($_POST['DataTableHeader2']) and !empty($_POST['DataTableHeader2']))
					{
						$Table1->setExpanded(true);
						$Table1->ExpColumn1 = $_POST['DataTableHeader2'];
					}
					if (isset($_POST['DataTableHeader3']) and !empty($_POST['DataTableHeader3']))
					{
						$Table1->setExpanded(true);
						$Table1->ExpColumn2 = $_POST['DataTableHeader3'];
					}
					$_SESSION['TableOne'] = $Table1;
					unset($Table1);
				}
			}
			if (isset($_POST['DataTableColumn']) and !empty($_POST['DataTableColumn']))
			{
				$Table2 = new OutputTable();
				if (isset($_SESSION['TableTwo']))
				{
					$Table2 = $_SESSION['TableTwo'];
					$Table2->Column = $_POST['DataTableColumn'];
					if (isset($_POST['DataTableColumn2']) and !empty($_POST['DataTableColumn2']))
					{
						$Table2->setExpanded(true);
						$Table2->ExpColumn1 = $_POST['DataTableColumn2'];
					}
					if (isset($_POST['DataTableColumn3']) and !empty($_POST['DataTableColumn3']))
					{
						$Table2->setExpanded(true);
						$Table2->ExpColumn2 = $_POST['DataTableColumn3'];
					}
					$_SESSION['TableTwo'] = $Table2;
					unset($Table2);
				}
			}
			if (isset($_POST['DataTableCenter']) and !empty($_POST['DataTableCenter']))
			{
				$Table3 = new OutputTable();
				if (isset($_SESSION['TableThree']))
				{
					$Table3 = $_SESSION['TableThree'];
					$Table3->Column = $_POST['DataTableCenter'];
					if (isset($_POST['DataTableCenter2']) and !empty($_POST['DataTableCenter2']))
					{
						$Table3->setExpanded(true);
						$Table3->ExpColumn1 = $_POST['DataTableCenter2'];
					}
					if (isset($_POST['DataTableCenter3']) and !empty($_POST['DataTableCenter3']))
					{
						$Table3->setExpanded(true);
						$Table3->ExpColumn2 = $_POST['DataTableCenter3'];
					}
					$_SESSION['TableThree'] = $Table3;
					unset($Table3);
				}
			}
		}

		public
		function actionUnsetExp($class)
		{
			$Table = new OutputTable();
			switch ($class)
			{
				case 'Header':
				{
					if (isset($_SESSION['TableOne']))
						$Table = $_SESSION['TableOne'];
					break;
				}
				case 'Column':
				{
					if (isset($_SESSION['TableTwo']))
						$Table = $_SESSION['TableTwo'];
					break;
				}
				case 'Center':
				{
					if (isset($_SESSION['TableThree']))
						$Table = $_SESSION['TableThree'];
					break;
				}
			}
			$Table->setExpanded(false);
			$Table->unsetExp();
		}

		public
		function actionSampleData()
		{

			$Table = '';

			if (isset($_POST['button']) and $_POST['button'] == 'ViewHeader')
			{
				$Table = new OutputTable();
				$Table = $_SESSION['TableOne'];

			}

			if (isset($_POST['button']) and $_POST['button'] == 'ViewColumn')
			{
				$Table = new OutputTable();
				$Table = $_SESSION['TableTwo'];
			}

			if (isset($_POST['button']) and $_POST['button'] == 'ViewCenter')
			{
				$Table = new OutputTable();
				$Table = $_SESSION['TableThree'];
			}

			$t = OutputTable::GetDataColumn($Table);
			unset($Table);
			echo $t;
		}

		public
		function actionGetDataCondition()
		{
			$Table = new OutputTable();
			$data = $this->getJsonInput();

			if (!empty($data))
			{
				switch ($data[0])
				{
					case 'Header':
					{
						if (isset($_SESSION['TableOne']))
						{
							$Table = $_SESSION['TableOne'];
						}
						break;
					}
					case 'Column':
					{
						if (isset($_SESSION['TableTwo']))
						{
							$Table = $_SESSION['TableTwo'];
						}
						break;
					}
					case 'Center':
					{
						if (isset($_SESSION['TableThree']))
						{
							$Table = $_SESSION['TableThree'];
						}
						break;
					}
				}

				$sql = "SELECT DISTINCT $data[1] FROM DB.$Table->TableName";
				$sql = Yii::app()->db->createCommand($sql)->queryAll();

				$model = array();
				foreach ($sql as $item)
				{
					$model[!empty($item[$data[1]])?$item[$data[1]]:'NULL'] = $item[$data[1]];
				}
				$model = CHtml::dropDownList('Table1', '', $model, array('empty' => 'Выберите данные'));
				$this->sendResponse(201, CJSON::encode($model));
			}
			else
			{
				$this->sendResponse(400, CJSON::encode(array('errors' => 'Заполните поля')));
			}
		}

		/**
		 * Performs the AJAX validation.
		 *
		 * @param OutputTable $model the model to be validated
		 */
		/*protected function performAjaxValidation($model)
		{
			if(isset($_POST['ajax']) && $_POST['ajax']==='vsabiturient-form')
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}
		}*/

		/**
		 * Gets RestFul data and decodes its JSON request
		 * @return mixed
		 */
		public
		function getJsonInput()
		{
			static $rawBody;

			$rawBody = Yii::app()->request->getRawBody();

			$restParams = CJSON::decode($rawBody);
			if (!is_array($restParams))
				return Yii::app()->request->restParams;

			return $restParams;

			// for {name:"ivam",age:21} - Backbone
			// return CJSON::decode(file_get_contents('php://input'));
			// for name="ivam"&age=21
			// return Yii::app()->request->restParams;
		}

		/**
		 * Send raw HTTP response
		 *
		 * @param int    $status      HTTP status code
		 * @param string $body        The body of the HTTP response
		 * @param string $contentType Header content-type
		 *
		 * @return HTTP response
		 */
		protected
		function sendResponse($status = 200, $body = '', $contentType = 'application/json')
		{
			// Set the status
			$statusHeader = 'HTTP/1.1 ' . $status . ' ' . $this->getStatusCodeMessage($status);
			header($statusHeader);
			// Set the content type
			header('Content-type: ' . $contentType);

			if (empty($body))
			{
				$body = CJSON::encode(array());
			}

			echo $body;
			Yii::app()->end();
		}

		/**
		 * Return the http status message based on integer status code
		 *
		 * @param int $status HTTP status code
		 *
		 * @return string status message
		 */
		protected
		function getStatusCodeMessage($status)
		{
			$codes = array(
				100 => 'Continue',
				101 => 'Switching Protocols',
				200 => 'OK',
				201 => 'Created',
				202 => 'Accepted',
				203 => 'Non-Authoritative Information',
				204 => 'No Content',
				205 => 'Reset Content',
				206 => 'Partial Content',
				300 => 'Multiple Choices',
				301 => 'Moved Permanently',
				302 => 'Found',
				303 => 'See Other',
				304 => 'Not Modified',
				305 => 'Use Proxy',
				306 => '(Unused)',
				307 => 'Temporary Redirect',
				400 => 'Bad Request',
				401 => 'Unauthorized',
				402 => 'Payment Required',
				403 => 'Forbidden',
				404 => 'Not Found',
				405 => 'Method Not Allowed',
				406 => 'Not Acceptable',
				407 => 'Proxy Authentication Required',
				408 => 'Request Timeout',
				409 => 'Conflict',
				410 => 'Gone',
				411 => 'Length Required',
				412 => 'Precondition Failed',
				413 => 'Request Entity Too Large',
				414 => 'Request-URI Too Long',
				415 => 'Unsupported Media Type',
				416 => 'Requested Range Not Satisfiable',
				417 => 'Expectation Failed',
				500 => 'Internal Server Error',
				501 => 'Not Implemented',
				502 => 'Bad Gateway',
				503 => 'Service Unavailable',
				504 => 'Gateway Timeout',
				505 => 'HTTP Version Not Supported',

			);

			return (isset($codes[$status])) ? $codes[$status] : '';
		}

		/**
		 * вместо actionListTable
		 */
		public
		function actionTest1(){
			$TableOne = new OutputTable();

			if (isset($_SESSION['TableThree']))
			{
				$TableOne = $_SESSION['TableThree'];
			}

			if (isset($_POST['ThreeTable']))
			{
				$TableOne->TableName = $_POST['ThreeTable'];
				$list = $TableOne->GetRelationsForTable();
//
//				$key = array_search($TableOne->TableName, $list);
//				if ($key !== false)
//				{
//					unset($list[$key]);
//
//				}
				$_SESSION['TableThree'] = $TableOne;
				echo CHtml::dropDownList('Table1', '', $list, array('empty' => 'Выберите таблицу'));
			}
		}
		/**
		 * вместо actionListTableTwo
		 */
		public
		function actionTest2(){
			static $TableTwo;
			static $TableOne;

			if (isset($_SESSION['TableOne']))
			{
				$TableOne = new OutputTable();
				$TableOne = $_SESSION['TableOne'];
			}
			if (isset($_SESSION['TableThree']))
			{
				$TableTwo = new OutputTable();
				$TableTwo = $_SESSION['TableThree'];
			}

			if (isset($_POST['AllTable']) and ($_POST['AllTable'] != '' /*and $_POST['ThreeTable'] == ''*/))
			{
				$TableOne->TableName = $_POST['AllTable'];
				$_SESSION['TableOne'] = $TableOne;

//				$list = $TableOne->GetRelation($TableTwo->TableName);
				$list = $TableTwo->GetRelationsForTable();

				$key = array_search($TableOne->TableName, $list);
				if ($key !== false)
				{
					unset($list[$key]);

				}

				echo CHtml::dropDownList('Table1', '', $list, array('empty' => 'Выберите таблицу'));
			}
		}
		/**
		 * вместо actionListTableThree
		 */
		public
		function actionTest3(){
			$TableOne = '';
			$TableTwo = '';
			$TableThree = '';


			if (isset($_SESSION['TableTwo']))
			{
				$TableTwo = new OutputTable();
				$TableTwo = $_SESSION['TableTwo'];
			}
			if (isset($_POST['TwoTable']) and ($_POST['TwoTable'] != ''))
			{
				$TableTwo->TableName = $_POST['TwoTable'];
				$_SESSION['TableTwo'] = $TableTwo;

			}

			if (isset($_SESSION['TableOne']))
			{
				$TableOne = new OutputTable();
				$TableOne = $_SESSION['TableOne'];
			}
			if (isset($_SESSION['TableThree']))
			{
				$TableThree = new OutputTable();
				$TableThree = $_SESSION['TableThree'];
			}

			$keys = OutputTable::GetKeyRelationsTables($TableOne->TableName, $TableThree->TableName);

			if (!empty($keys))
			{
				if ($TableOne->TableName == $keys['MAIN_TABLE'])
				{
					$TableOne->FkOne = $keys['PK_MAIN'];
					$TableThree->FkOne = $keys['PK_SUB'];
				}
				else
				{
					$TableOne->FkOne = $keys['PK_SUB'];
					$TableThree->FkOne = $keys['PK_MAIN'];
				}
			}

			$keys = OutputTable::GetKeyRelationsTables($TableTwo->TableName, $TableThree->TableName);

			if (!empty($keys))
			{
				if ($TableTwo->TableName == $keys['MAIN_TABLE'])
				{
					$TableTwo->FkOne = $keys['PK_MAIN'];
					$TableThree->FkTwo = $keys['PK_SUB'];
				}
				else
				{
					$TableTwo->FkOne = $keys['PK_SUB'];
					$TableThree->FkTwo = $keys['PK_MAIN'];
				}
			}
			unset($TableOne, $TableTwo, $TableThree, $keys);
			echo '</br>' . CHtml::Button('Далее', array('id'          => 'Next',
			                                            'name'        => 'items',
			                                            'class'       => 'btn btn-primary btn-left'));

		}

		public
		function actionSave()
		{
			if (isset($_POST['NAME']))
			{
				$TableOne = new OutputTable();
				$TableTwo = new OutputTable();
				$TableThree = new OutputTable();

				if (isset($_SESSION['TableOne']) && isset($_SESSION['TableTwo']) && isset($_SESSION['TableThree']))
				{
					$TableOne = $_SESSION['TableOne'];
					$TableTwo = $_SESSION['TableTwo'];
					$TableThree = $_SESSION['TableThree'];
				}

				if ($TableOne->TableName != "" || $TableTwo->TableName != "" || $TableThree->TableName != "")
				{
					$LocationOne = "TableOne";
					$LocationTwo = "TableTwo";
					$LocationThree = "TableThree";
					$name = $_POST['NAME'];
					$id_shablon = 0;
					$id_tableOne = 0;
					$id_tableTwo = 0;
					$id_tableThree = 0;
					$ExpColumn1 = null;
					$ExpColumn2 = null;

					/**
					 * Inserting the name of the template and who created and when.
					 */
					$command = Yii::app()->db_local->createCommand()
					                         ->insert('outputtable_shablon',
					                                  array(
						                                  'Name_Shablon' => $name,
						                                  'Who_Create'   => Yii::app()->user->name,
						                                  'Date_Create'  => date('Y.m.d'),
					                                  ));
					$id_shablon = Yii::app()->db_local->lastInsertID;

					/**
					 * Inserting tables and FK, and their locations
					 */
					$command = Yii::app()->db_local->createCommand()
					                         ->insert('outputtable_stable',
					                                  array(
						                                  'Shablon_ID'      => $id_shablon,
						                                  'Name_Table'      => $TableOne->TableName,
						                                  'FkOne'           => $TableOne->FkOne,
						                                  'FkTwo'           => $TableOne->FkTwo,
						                                  'LocationInTable' => $LocationOne,
					                                  ));
					$id_tableOne = Yii::app()->db_local->lastInsertID;

					$command = Yii::app()->db_local->createCommand()
					                         ->insert('outputtable_stable',
					                                  array(
						                                  'Shablon_ID'      => $id_shablon,
						                                  'Name_Table'      => $TableTwo->TableName,
						                                  'FkOne'           => $TableTwo->FkOne,
						                                  'FkTwo'           => $TableTwo->FkTwo,
						                                  'LocationInTable' => $LocationTwo,
					                                  ));
					$id_tableTwo = Yii::app()->db_local->lastInsertID;

					$command = Yii::app()->db_local->createCommand()
					                         ->insert('outputtable_stable',
					                                  array(
						                                  'Shablon_ID'      => $id_shablon,
						                                  'Name_Table'      => $TableThree->TableName,
						                                  'FkOne'           => $TableThree->FkOne,
						                                  'FkTwo'           => $TableThree->FkTwo,
						                                  'LocationInTable' => $LocationThree,
					                                  ));
					$id_tableThree = Yii::app()->db_local->lastInsertID;

					/**
					 * Inserting fields the tables
					 */

					//One
					$ExpColumn1 = $TableOne->isExpanded() ? $TableOne->ExpColumn1 : null;
					$ExpColumn2 = ($ExpColumn1 != null) ? $TableOne->ExpColumn2 : null;

					$command = Yii::app()->db_local->createCommand()
					                         ->insert('outputtable_sfields_table',
					                                  array(
						                                  'STable_ID'         => $id_tableOne,
						                                  'Name_Fields_Table' => $TableOne->Column,
						                                  'ExpColumn1'        => $ExpColumn1,
						                                  'ExpColumn2'        => $ExpColumn2,
					                                  ));

					//Two
					$ExpColumn1 = $TableTwo->isExpanded() ? $TableTwo->ExpColumn1 : null;
					$ExpColumn2 = ($ExpColumn1 != null) ? $TableTwo->ExpColumn2 : null;

					$command = Yii::app()->db_local->createCommand()
					                         ->insert('outputtable_sfields_table',
					                                  array(
						                                  'STable_ID'         => $id_tableTwo,
						                                  'Name_Fields_Table' => $TableTwo->Column,
						                                  'ExpColumn1'        => $ExpColumn1,
						                                  'ExpColumn2'        => $ExpColumn2,
					                                  ));

					//Three
					$ExpColumn1 = $TableThree->isExpanded() ? $TableThree->ExpColumn1 : null;
					$ExpColumn2 = ($ExpColumn1 != null) ? $TableThree->ExpColumn2 : null;

					$command = Yii::app()->db_local->createCommand()
					                         ->insert('outputtable_sfields_table',
					                                  array(
						                                  'STable_ID'         => $id_tableThree,
						                                  'Name_Fields_Table' => $TableThree->Column,
						                                  'ExpColumn1'        => $ExpColumn1,
						                                  'ExpColumn2'        => $ExpColumn2,
					                                  ));

					/**
					 * Inserting the tables conditions
					 */

					//One
					if ($TableOne->isWhere())
					{
						foreach ($TableOne->GetCondition() as $item)
						{
							$command = Yii::app()->db_local->createCommand()
							                         ->insert('outputtable_sсonditions',
							                                  array(
								                                  'STable_ID' => $id_tableOne,
								                                  'Where_1'   => $item['condition_1'],
								                                  'Operator'  => $item['operator'],
								                                  'Where_2'   => $item['condition_2'],
								                                  'Operand'   => $item['operand'],
							                                  ));
						}
					}

					//Two
					if ($TableTwo->isWhere())
					{
						foreach ($TableTwo->GetCondition() as $item)
						{
							$command = Yii::app()->db_local->createCommand()
							                         ->insert('outputtable_sсonditions',
							                                  array(
								                                  'STable_ID' => $id_tableTwo,
								                                  'Where_1'   => $item['condition_1'],
								                                  'Operator'  => $item['operator'],
								                                  'Where_2'   => $item['condition_2'],
								                                  'Operand'   => $item['operand'],
							                                  ));
						}
					}

					//Three
					if ($TableThree->isWhere())
					{
						foreach ($TableThree->GetCondition() as $item)
						{
							$command = Yii::app()->db_local->createCommand()
							                         ->insert('outputtable_sсonditions',
							                                  array(
								                                  'STable_ID' => $id_tableThree,
								                                  'Where_1'   => $item['condition_1'],
								                                  'Operator'  => $item['operator'],
								                                  'Where_2'   => $item['condition_2'],
								                                  'Operand'   => $item['operand'],
							                                  ));
						}
					}
				}
				else
				{
					$time = <<<TAG
							<div style='color: #7a0000'>Перенаправление через <span id='timer'></span> секунд</div>
								<script type='text/javascript'>
									var t=20; /* Даём 20 секунд */
									function refr_time()
									{
										if (t>0)
										{
											t--;
											document.getElementById('timer').innerHTML=t;
										} 
										else
										{
											clearInterval(tm);
											location.href='index.php?r=OutputTable/index';
										}
									}
									var tm=setInterval('refr_time();',1000);
								</script>
TAG;

					$this->render('save', array(
						'time' => $time
					));
				}
			}
			else
			{
				$this->render('save');
			}
		}

		public
		function actionTemplate(){

			if (isset($_POST['TemplateList']) && !isset($_POST['Download'])){
				echo '</br>' . CHtml::submitButton('Вывести шаблон', array('id'    => 'Download',
				                                                           'name'  => 'Download',
				                                                           'class' => 'btn btn-primary btn-left'
					));
			}elseif (isset($_POST['Download']) && isset($_POST['TemplateList'])){
				if (ctype_digit(strval($_POST['TemplateList']))){
					$idT1 = 0;
					$idT2 = 0;
					$idT3 = 0;
					$id = $_POST['TemplateList'];
					$TableOne   = new OutputTable();
					$TableTwo   = new OutputTable();
					$TableThree = new OutputTable();

					$result = OutputTable::GetTemplateTable($id,"TableOne");
					$result = $result[0];
					if (!empty($result))
					{
						$TableOne->TableName = $result['Name_Table'];
						$TableOne->FkOne = $result['FkOne'];
						$TableOne->FkTwo = $result['FkTwo'];
						$idT1 = $result['ID'];

						$result = OutputTable::GetTemplateTable($id, "TableTwo");
						$result = $result[0];
						$TableTwo->TableName = $result['Name_Table'];
						$TableTwo->FkOne = $result['FkOne'];
						$TableTwo->FkTwo = $result['FkTwo'];
						$idT2 = $result['ID'];

						$result = OutputTable::GetTemplateTable($id, "TableThree");
						$result = $result[0];
						$TableThree->TableName = $result['Name_Table'];
						$TableThree->FkOne = $result['FkOne'];
						$TableThree->FkTwo = $result['FkTwo'];
						$idT3 = $result['ID'];

						unset($result);
						$result['TableOne']     = OutputTable::GetTemplateFieldsTable($idT1);
						$result['TableTwo']     = OutputTable::GetTemplateFieldsTable($idT2);
						$result['TableThree']   = OutputTable::GetTemplateFieldsTable($idT3);

						$result['TableOne']     = $result['TableOne'][0];
						$result['TableTwo']     = $result['TableTwo'][0];
						$result['TableThree']   = $result['TableThree'][0];

						$TableOne->Column = $result['TableOne']['Name_Fields_Table'];
						if ($result['TableOne']['ExpColumn1'] != null || $result['TableOne']['ExpColumn1']!=''){
							$TableOne->setExpanded(true);
							$TableOne->ExpColumn1 = $result['TableOne']['ExpColumn1'];
							$TableOne->ExpColumn2 = $result['TableOne']['ExpColumn2'];
						}

						$TableTwo->Column = $result['TableTwo']['Name_Fields_Table'];
						if ($result['TableTwo']['ExpColumn1'] != null || $result['TableTwo']['ExpColumn1']!=''){
							$TableTwo->setExpanded(true);
							$TableTwo->ExpColumn1 = $result['TableTwo']['ExpColumn1'];
							$TableTwo->ExpColumn2 = $result['TableTwo']['ExpColumn2'];
						}

						$TableThree->Column = $result['TableThree']['Name_Fields_Table'];
						if ($result['TableThree']['ExpColumn1'] != null || $result['TableThree']['ExpColumn1']!=''){
							$TableThree->setExpanded(true);
							$TableThree->ExpColumn1 = $result['TableThree']['ExpColumn1'];
							$TableThree->ExpColumn2 = $result['TableThree']['ExpColumn2'];
						}

						unset($result);
						$result['TableOne']     = OutputTable::GetTemplateConditionTable($idT1);
						$result['TableTwo']     = OutputTable::GetTemplateConditionTable($idT2);
						$result['TableThree']   = OutputTable::GetTemplateConditionTable($idT3);

						$i = 0;

						foreach ($result['TableOne'] as $item)
						{
							$data = array("condition_1" => $item['Where_1'],
							              "operator"    => $item['Operator'],
							              "condition_2" => $item['Where_2'],
							              "operand"     => $item['Operand']);

							$TableOne->SetCondition($i++, $data);
						}
						unset($item);
						$i = 0;

						foreach ($result['TableTwo'] as $item)
						{
							$data = array("condition_1" => $item['Where_1'],
							              "operator"    => $item['Operator'],
							              "condition_2" => $item['Where_2'],
							              "operand"     => $item['Operand']);

							$TableTwo->SetCondition($i++, $data);
						}
						unset($item);
						$i = 0;

						foreach ($result['TableThree'] as $item)
						{
							$data = array("condition_1" => $item['Where_1'],
							              "operator"    => $item['Operator'],
							              "condition_2" => $item['Where_2'],
							              "operand"     => $item['Operand']);

							$TableThree->SetCondition($i++, $data);
						}

						$_SESSION['TableOne']   = $TableOne;
						$_SESSION['TableTwo']   = $TableTwo;
						$_SESSION['TableThree'] = $TableThree;

						unset($TableOne);
						unset($TableTwo);
						unset($TableThree);
						unset($result);
						unset($item);
						unset($data);
						unset($idT1);
						unset($idT2);
						unset($idT3);
						unset($id);
						unset($i);
						$this->redirect(array('OutputTable/view'));
					}
				}
			}else
				$this->redirect(array('OutputTable/index'));
		}
	}
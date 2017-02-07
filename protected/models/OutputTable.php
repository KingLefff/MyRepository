<?php

	/**
	 * Created by PhpStorm.
	 * User: Alexey
	 * Date: 01.03.2016
	 * Time: 18:33
	 */
	class OutputTable
	{

		private $expanded = false;
		private $where = false;
		private $x = array('TableName'=> '','FkOne'=>'','FkTwo'=>'','Column'=>'ID');
		private $exp = array('ExpColumn1'=>'','ExpColumn2'=>'');
		private $operator = array("="=>"=","=<"=>"=<",">="=>">=","<>"=>"<>");
		private $condition = array();


		function __set($name, $value)
		{
			// TODO: Implement __set() method.

			if (isset($this->x[$name]))
			{
				$this->x[$name] = $value;
			}else{
				if ($this->isExpanded() and isset($this->exp[$name])){
					$this->exp[$name] = $value;
				}
			}
		}

		function __get($name)
		{
			// TODO: Implement __get() method.

			if (isset($this->x[$name]))
			{
				$r = $this->x[$name];
				return $r;
			}else{
				if ($this->isExpanded() and isset($this->exp[$name])){
					$r = $this->exp[$name];
					return $r;
				}
			}
		}

		function getOperator()
		{
			return $this->operator;
		}

		function isExpanded()
		{
			return $this->expanded;
		}

		function setExpanded($expanded)
		{
			$this->expanded = $expanded;
		}

		function unsetExp(){
			unset($this->exp);
		}

		function isWhere()
		{
			return $this->where;
		}

		function setWhere($where)
		{
			$this->where = $where;
		}

		function SetCondition($id, $data)
		{
			$this->where = true;
			$this->condition[$id] = $data;
		}

		function GetCondition()
		{
			return $this->condition;
		}

		function UnsetCondition($id)
		{
			unset($this->condition[$id]);

			if(sizeof($this->condition) == 0)
			{
				$this->where = false;
			}
		}

		function UnsetConditionAll(){
			unset($this->condition);
			$this->where = false;
		}

		function getOr()
		{
			return $this->or;
		}

		function setOr($or)
		{
			array_push($this->or,$or);
		}

		function getTable(){
			return $this->TableName;
		}

		function getColumn(){
			return $this->Column;
		}

		function getFk(){
			return $this->FkOne;
		}

		function GetNameColumn()
		{
			$table = strtoupper($this->TableName);
			$delStr = array('WHEN_CHANGED', 'WHEN_CREATED', 'WHO_CHANGED', 'WHO_CREATED','CODE');

			$sql = 'select * from DB.' . $table . ' where rownum = 1';

			$array = Yii::app()->db->createCommand($sql)->queryAll();
			$array = !empty($array)?array_keys($array[0]):null;

			if( $array != null)
			{
				$i = 0;
				do
				{
					$key = array_search($delStr[$i], $array);
					if ($key !== false)
					{
						unset($array[$key]);

					}
					$i++;
				} while ($i < sizeof($delStr));

				unset($key);

				$arr = array();
				foreach ($array as $key)
				{
					$arr[$key] = $key;
				}
				unset($array, $sql, $key, $table);

				return $arr/*array_values($array)*/;
			}else{
				return ["Таблица не заполнена"];
			}
		}

		/**
		 * @return mixed(if there is prefix '_' before a table, then the table dependent)
		 */
		function GetRelationsForTable(){

			$table = strtoupper($this->getTable());

			$sql = 'select q.owner main_scheme, q.table_name main_table,w.COLUMN_NAME pk_main,
 						   qq.owner sub_scheme, qq.table_name sub_table, ww.COLUMN_NAME pk_sub
					from all_constraints q, all_constraints qq, all_cons_columns w, all_cons_columns ww
					where q.constraint_type=\'R\' and
					      (qq.table_name=\''.$table.'\' or q.table_name=\''.$table.'\')and
					      qq.constraint_name=q.r_constraint_name and
					      qq.CONSTRAINT_NAME=ww.CONSTRAINT_NAME and
					      q.CONSTRAINT_NAME=w.CONSTRAINT_NAME and
					      qq.owner=q.r_owner and
					      qq.OWNER = ww.OWNER and
					      q.OWNER = w.OWNER';

			$array = Yii::app()->db->createCommand($sql)->queryAll();
			$list = array();

			if(!empty($array))
			{
				foreach ($array as $key)
				{
					$key['MAIN_TABLE'] != $table ? $list[$key['MAIN_TABLE']] = $key['MAIN_TABLE'] :
						$list[$key['SUB_TABLE']] = /*'_' .*/ $key['SUB_TABLE'];
				}
			}else
				$list[0] = 'Связанных таблиц не найдено';
			return $list;
//			return $array;
		}

		function GetRelation($tableOne){

			$table = strtoupper($this->getTable());

			$sql = 'select q.table_name main_table,
 						   qq.table_name sub_table
					from all_constraints q, all_constraints qq
					where q.constraint_type=\'R\' and
					      qq.table_name=\''.$table.'\'
					      and q.table_name in (select distinct wq.table_name main_table
									          from all_constraints wq, all_constraints wqq
									          where wq.constraint_type=\'R\' and
									                wqq.table_name=\''.$tableOne.'\' and
									                wqq.constraint_name=wq.r_constraint_name and
									                wqq.owner=wq.r_owner)
							and
					      qq.constraint_name=q.r_constraint_name and
					      qq.owner=q.r_owner';

			$array = Yii::app()->db->createCommand($sql)->queryAll();
			$list = array();

			if(!empty($array))
			{
				foreach ($array as $key)
				{
					$key['MAIN_TABLE'] != $table ? $list[$key['MAIN_TABLE']] = $key['MAIN_TABLE'] :
						$list[$key['SUB_TABLE']] = /*'_' .*/ $key['SUB_TABLE'];
				}
			}else
				$list[0] = 'Связанных таблиц не найдено';
			return $list;
		}

		function GetDataForHead($equal = null){
			$table = strtoupper($this->TableName);
			$column = strtoupper($this->Column);
			$fk = strtoupper($this->FkOne);

			return self::GetDataColumn($this,false,$equal);
		}
		
		function GetDataForCenter($tableHeader, $FkHeader, $columnTableHeader, $tableColumn, $FkColumn, $valueColumn){

			$tableCenter = strtoupper($this->TableName);
			$columnCenter = strtoupper($this->Column);
			$FkOneCenter = strtoupper($this->FkOne);
			$FkTwoCenter = strtoupper($this->FkTwo);

			$expCol1 = $this->ExpColumn1;
			$expCol2 = $this->ExpColumn2;
			$countExpColumn = 0;
			$select = '';
			$where = '';
			
			if($this->isExpanded()){
				if(!empty($expCol1)){
					$select = $select.',c.'.$expCol1;
					$countExpColumn++;
				}
				if(!empty($expCol2)){
					$select = $select.',c.'.$expCol2;
					$countExpColumn++;
				}
			}

			if($this->isWhere()){
				$where = ' and ';
				foreach ($this->condition as $item){
					if($item['operand'] != 'no'){
						$where = $where.'"c".'.$item['condition_1'].$item['operator'].'\''.$item['condition_2'].'\' '
						         .$item['operand'];
					}else{
						$where = $where.'"c".'.$item['condition_1'].$item['operator'].'\''.$item['condition_2'].'\'';
					}
				}
			}

			/*$sql = Yii::app()->db->createCommand()
			                                 ->select('c.'.$FkOneCenter.', c.'.$FkTwoCenter.', c.'.$columnCenter.$select)
			                                 ->from('DB.'.$tableCenter .' c, DB.'.$tableHeader .' h, DB.'
			                                                                                           .$tableColumn.' cl')
			                                 ->where('"c".'.$FkOneCenter.' = "h".'.$FkHeader)
			                                 ->andWhere('"c".'.$FkTwoCenter.' = "cl".'.$FkColumn)
			                                 ->andWhere('"c".'.$FkTwoCenter.'='.$valueColumn.$where)
			                                 ->order('h.'.$columnTableHeader.'')
			                                 ->text;*/

			$sql = Yii::app()->db->createCommand()
			                                 ->select('c.'.$FkOneCenter.', c.'.$FkTwoCenter.', c.'.$columnCenter.$select)
			                                 ->from('DB.'.$tableCenter .' c, DB.'.$tableHeader .' h, DB.'
			                                                                                           .$tableColumn.' cl')
			                                 ->where('"c".'.$FkOneCenter.' = "h".'.$FkHeader)
			                                 ->andWhere('"c".'.$FkTwoCenter.' = "cl".'.$FkColumn)
			                                 ->andWhere('"c".'.$FkTwoCenter.'='.$valueColumn.$where)
			                                 ->order('h.'.$columnTableHeader.'')
			                                 ->queryAll();

			$model = array();
			$i = 0;

			if(!empty($sql))
			{
				foreach ($sql as $arr)
				{
					switch ($countExpColumn)
					{
						case 0:
							$model[$i][$FkOneCenter] = $arr[$FkOneCenter];
							$model[$i][$FkTwoCenter] = $arr[$FkTwoCenter];
							$model[$i][$columnCenter] = $arr[$columnCenter];
							break;
						case 1:

							$model[$i][$FkOneCenter] = $arr[$FkOneCenter];
							$model[$i][$FkTwoCenter] = $arr[$FkTwoCenter];
							$model[$i][$columnCenter] = $arr[$columnCenter] . !empty($expCol1) ? ' / ' . $arr[$expCol1]
																								: ' / '.$arr[$expCol2];
							break;
						case 2:

							$model[$i][$FkOneCenter] = $arr[$FkOneCenter];
							$model[$i][$FkTwoCenter] = $arr[$FkTwoCenter];
							$model[$i][$columnCenter] =
								$arr[$columnCenter] . ' / ' . $arr[$expCol1] . ' / ' . $arr[$expCol2];

							break;
					};
					$i++;
				}
			}else return $sql;

			return $model;
		}

		static function GetDataColumn($Table, $lim=true, $equal=null){

//			$Table = new OutputTable();

			$table = $Table->getTable();
			$fk = $Table->getFk();
			$column = $Table->getColumn();
			$expCol1 = $Table->ExpColumn1;
			$expCol2 = $Table->ExpColumn2;
			$select = '';
			$where = '';
			$limit = '1=1';
			$countExpColumn = 0;

			if($lim){
				$limit = 'rownum<=5';
			}

			if($fk != $column)
			{
				$select = $fk.','.$column;
				$equal = $fk;
			}
			else
			{
				$select = $column;
			}

			if($Table->isExpanded()){
				if(!empty($expCol1)){
					$select = $select.','.$expCol1;
					$countExpColumn++;
				}
				if(!empty($expCol2)){
					$select = $select.','.$expCol2;
					$countExpColumn++;
				}
			}

			if($Table->isWhere()){
				$where = ' and ';
				foreach ($Table->condition as $item){
					if($item['operand'] != 'no'){
						$where = $where.$item['condition_1'].$item['operator'].'\''.$item['condition_2'].'\' '
						         .$item['operand'].' ';
					}else{
						$where = $where.$item['condition_1'].$item['operator'].'\''.$item['condition_2'].'\'';
					}
				}
			}
			/*$sql = Yii::app()->db->createCommand()
			                          ->select($select)
			                          ->from('DB.'.$table)
			                          ->where($limit.$where)
			                          ->order($column)
			                          ->text;*/

			$sql = Yii::app()->db->createCommand()
			                          ->select($select)
			                          ->from('DB.'.$table)
			                          ->where($limit.$where)
			                          ->order($column)
			                          ->queryAll();

			$list = '';
			$model = array();
			$i = 0;

			if(!empty($sql))
			{
				foreach ($sql as $arr)
				{
//					foreach ($arr as $key)
					$list =$list.$arr[$column];
					switch ($countExpColumn){
						case 0:
							$model[$i][$equal]= $arr[$fk];
							$model[$i][$column]= $arr[$column];
							break;
						case 1:
							$list = !empty($expCol1)?$list.' / '.$arr[$expCol1]:$list.' / '.$arr[$expCol2];

							$model[$i][$equal]= $arr[$fk];
							$model[$i][$column]= $arr[$column].(!empty($expCol1)?' / '.$arr[$expCol1]:' / '
							                                                                          .$arr[$expCol2]);
							break;
						case 2:
							$list = $list.' / '.$arr[$expCol1];
							$list = $list. ' / '.$arr[$expCol2];

							$model[$i][$equal]= $arr[$fk];
							$model[$i][$column]= $arr[$column].' / '.$arr[$expCol1]. ' / '.$arr[$expCol2];

							break;
					}
					$list = $list.'<br>';
					$i++;
				}
			}else{
				$model = $list = "Данных не найдено";
			}

			return $lim?$list:$model;
		}

		static function GetAllTable(){

			$sql = 'select owner,table_name from all_all_tables
					where owner = \'DB\'
					order by table_name asc';

			$array = Yii::app()->db->createCommand($sql)->queryAll();

			return CHtml::listData($array,'TABLE_NAME','TABLE_NAME');
		}

		static function GetKeyRelationsTables($tb1, $tb2){

			$tb1 = strtoupper($tb1);
			$tb2 = strtoupper($tb2);

			$sql = "select q.table_name main_table,w.COLUMN_NAME pk_main,
       					   qq.table_name sub_table, ww.COLUMN_NAME pk_sub
			          from all_constraints q, all_constraints qq, all_cons_columns w, all_cons_columns ww
			          where q.constraint_type='R' and
		                (qq.table_name='$tb1' or q.table_name='$tb1') and (qq.table_name='$tb2' or q
		                .table_name='$tb2')
		                and qq.constraint_name=q.r_constraint_name and
		                qq.CONSTRAINT_NAME=ww.CONSTRAINT_NAME and
		                q.CONSTRAINT_NAME=w.CONSTRAINT_NAME and
		                qq.owner=q.r_owner and
		                qq.OWNER = ww.OWNER and
		                q.OWNER = w.OWNER";

			$sql = Yii::app()->db->createCommand($sql)->queryAll();

			return $sql[0];
		}

		static function GetTemplate(){

			$sql = "select ID,NAME_Shablon from outputtable_shablon";

			$array = Yii::app()->db_local->createCommand($sql)->queryAll();

			return CHtml::listData($array,'ID','NAME_Shablon');

		}

		static function GetTemplateTable($id, $location){

			$sql = "select * from outputtable_stable where Shablon_ID = $id and LocationInTable = '$location'";

			return Yii::app()->db_local->createCommand($sql)->queryAll();
		}

		static function GetTemplateFieldsTable($id){

			$sql = "select * from outputtable_sfields_table where STable_ID = $id ";

			return Yii::app()->db_local->createCommand($sql)->queryAll();

		}

		static function GetTemplateConditionTable($id){

			$sql = "select * from outputtable_sсonditions where STable_ID = $id ";

			return Yii::app()->db_local->createCommand($sql)->queryAll();
		}
	}
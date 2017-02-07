<?php
	$this->breadcrumbs = array(
		'OutputTable',
	);
	
	$this->menu = array(
		array('label' => 'Просмотр таблицы', 'url' => array('view'), 'linkOptions' => array('target'=>'_blank')/*'visible' => (Yii::app()
			->authManager->isAssigned('Admin', Yii::app()->user->id))*/),
		array('label' => 'Вернуться к выбору таблиц', 'url' => array('index')),
		array('label' => 'Сохранить шаблон', 'url' => array('save'), 'linkOptions' => array('target'=>'_blank')),
	);
?>
<div id="outputTable-success-box" class="alert alert-success in fade" style="display: none; position: fixed; z-index: 101;">
	<a href="#" class="close" data-dismiss="alert" type="button">×</a>Данные сохранены.
</div>

<div id="alert-er" class="alert alert-danger" role="alert" style="display: none; position: fixed; z-index: 101;">
	<h4>Не все поля выбраны!!!</h4>
</div>

<h3>Выберите из каких столбцов таблиц выводить данные*</h3>
<h5 style="color: #9e9e9e">*по умолчанию из ID</h5>
<?php echo CHtml::form(); ?>
<div id="output-container">
	<div class="row">
		<div id="SelectDataHeader" style="width: 50%; float: left; min-height: 75px">
			<h4>Данные в шапке таблицы из <?=$TableHeader->TableName?></h4>
			<div id="dropListHeader">
				<?php
					echo CHtml::dropDownList('DataTableHeader', '', $TableHeader->GetNameColumn(),
					                         array('empty' => 'Выберите из какого столбца выводить данные',
					                               'ajax'  => array(
						                               'type' => 'POST',
						                               'url'  => $this->createUrl('OutputTable/SetColumn'),
					                               ))
					);
					echo '<br>';
				?>
			</div>
			<div id="exp-dropListHeader-1" style="display: none">
				<?php
					echo '<br>';
					echo CHtml::dropDownList('DataTableHeader2', '', $TableHeader->GetNameColumn(),
					                         array('empty' => 'Выберите из какого столбца выводить данные',
					                               'ajax'  => array(
						                               'type' => 'POST',
						                               'url'  => $this->createUrl('OutputTable/SetColumn'),
					                               ))
					);
					echo '<br>';
				?>
			</div>
			<div id="exp-dropListHeader-2" style="display: none">
				<?php
					echo '<br>';
					echo CHtml::dropDownList('DataTableHeader3', '', $TableHeader->GetNameColumn(),
					                         array('empty' => 'Выберите из какого столбца выводить данные',
					                               'ajax'  => array(
						                               'type' => 'POST',
						                               'url'  => $this->createUrl('OutputTable/SetColumn'),
					                               ))
					);
					echo '<br>';
				?>
			</div>
			<a id="AddSlashData" class="Header">Добавить данные через " / "</a>
			<br>
			<a id="DelSlashData" class="Header" style="display: none">Удалить данные через " / "</a>
			<?php
				echo '<br>';
				echo CHtml::submitButton('Предпросмотр данных', array('id'    => 'ViewHeader',
				                                                      'class' => 'btn btn-primary btn-left',
				                                                      'ajax'  => array(
					                                                      'type'   => 'POST',
					                                                      'data'   => array('button' => 'ViewHeader'),
					                                                      'url'    => $this->createUrl('OutputTable/SampleData'),
					                                                      'update' => '#SampleDataHeader'
				                                                      )
				                                              )
				); ?>
			<br>
			<div id="WhereData" class="Header"></div>

			<a id="AddWhere" class="Header">Добавить условие вывода данных</a>

		</div>
		<h4>Пример данных</h4>
		<div id="SampleDataHeader" style="width: 50%; float: right; min-height: 75px">

		</div>
	</div>
	<hr style="border: 1px solid">
	<div class="row">
		<div id="SelectDataColumn" style="width: 50%; float: left; min-height: 75px">
			<h4>Данные по строкам таблицы из <?=$TableColumn->TableName?></h4>
			<div id="dropListColumn">
				<?php
					echo CHtml::dropDownList('DataTableColumn', '', $TableColumn->GetNameColumn(),
					                         array('empty' => 'Выберите из какого столбца выводить данные',
					                               'ajax'  => array(
						                               'type' => 'POST',
						                               'url'  => $this->createUrl('OutputTable/SetColumn'),
					                               ))
					);
					echo '<br>';
				?>
			</div>

			<div id="exp-dropListColumn-1" style="display: none">
				<?php
					echo '<br>';
					echo CHtml::dropDownList('DataTableColumn2', '', $TableColumn->GetNameColumn(),
					                         array('empty' => 'Выберите из какого столбца выводить данные',
					                               'ajax'  => array(
						                               'type' => 'POST',
						                               'url'  => $this->createUrl('OutputTable/SetColumn'),
					                               ))
					);
					echo '<br>';
				?>
			</div>
			<div id="exp-dropListColumn-2" style="display: none">
				<?php
					echo '<br>';
					echo CHtml::dropDownList('DataTableColumn3', '', $TableColumn->GetNameColumn(),
					                         array('empty' => 'Выберите из какого столбца выводить данные',
					                               'ajax'  => array(
						                               'type' => 'POST',
						                               'url'  => $this->createUrl('OutputTable/SetColumn'),
					                               ))
					);
					echo '<br>';
				?>
			</div>

			<a id="AddSlashData" class="Column">Добавить данные через " / "</a>
			<br>
			<a id="DelSlashData" class="Column" style="display: none">Удалить данные через " / "</a>

			<?php
				//			echo CHtm::link();
				echo '<br>';
				echo CHtml::Button('Предпросмотр данных', array('id'    => 'ViewColumn',
				                                                'class' => 'btn btn-primary btn-left',
				                                                'ajax'  => array(
					                                                'type'   => 'POST',
					                                                'data'   => array('button' => 'ViewColumn'),
					                                                'url'    => $this->createUrl('OutputTable/SampleData'),
					                                                'update' => '#SampleDataColumn'
				                                                )
				                                        )
				); ?>

			<br>
			<div id="WhereData" class="Column"></div>

			<a id="AddWhere" class="Column">Добавить условие вывода данных</a>
		</div>
		<h4>Пример данных</h4>
		<div id="SampleDataColumn" style="width: 50%; float: right; min-height: 75px">

		</div>
	</div>

	<hr style="border: 1px solid">
	<div class="row">
		<div id="SelectDataCenter" style="width: 50%; float: left; min-height: 75px">
			<h4>Данные в центр таблицы из <?=$TableCenter->TableName?></h4>
			<div id="dropListCenter">
				<?php
					echo CHtml::dropDownList('DataTableCenter', '', $TableCenter->GetNameColumn(),
					                         array('empty' => 'Выберите из какого столбца выводить данные',
					                               'ajax'  => array(
						                               'type' => 'POST',
						                               'url'  => $this->createUrl('OutputTable/SetColumn'),
					                               ))
					);
					echo '<br>';
				?>
			</div>

			<div id="exp-dropListCenter-1" style="display: none">
				<?php
					echo '<br>';
					echo CHtml::dropDownList('DataTableCenter2', '', $TableCenter->GetNameColumn(),
					                         array('empty' => 'Выберите из какого столбца выводить данные',
					                               'ajax'  => array(
						                               'type' => 'POST',
						                               'url'  => $this->createUrl('OutputTable/SetColumn'),
					                               ))
					);
					echo '<br>';
				?>
			</div>
			<div id="exp-dropListCenter-2" style="display: none">
				<?php
					echo '<br>';
					echo CHtml::dropDownList('DataTableCenter3', '', $TableCenter->GetNameColumn(),
					                         array('empty' => 'Выберите из какого столбца выводить данные',
					                               'ajax'  => array(
						                               'type' => 'POST',
						                               'url'  => $this->createUrl('OutputTable/SetColumn'),
					                               ))
					);
					echo '<br>';
				?>
			</div>

			<a id="AddSlashData" class="Center">Добавить данные через " / "</a>
			<br>
			<a id="DelSlashData" class="Center" style="display: none">Удалить данные через " / "</a>
			<?php
				echo '<br>';
				echo CHtml::Button('Предпросмотр данных', array('id'    => 'ViewCenter',
				                                                'class' => 'btn btn-primary btn-left',
				                                                'ajax'  => array(
					                                                'type'   => 'POST',
					                                                'data'   => array('button' => 'ViewCenter'),
					                                                'url'    => $this->createUrl('OutputTable/SampleData'),
					                                                'update' => '#SampleDataCenter'
				                                                )
				                                        )
				); ?>
			<br>
			<div id="WhereData" class="Center"></div>

			<a id="AddWhere" class="Center">Добавить условие вывода данных</a>
		</div>
		<h4>Пример данных</h4>
		<div id="SampleDataCenter" style="width: 50%; float: right; min-height: 75px">

		</div>
	</div>
</div>
<?php echo CHtml::endForm() ?>


<script type="text/html" id="dropList-template" class="Header">
	<?php
		$this->renderPartial('_emptyWhereAnd', array('model' => $TableHeader,)); ?>
</script>

<script type="text/html" id="dropList-template"  class="Column">
	<?php
		$this->renderPartial('_emptyWhereAnd', array('model' => $TableColumn,)); ?>
</script>

<script type="text/html" id="dropList-template"  class="Center">
	<?php
		$this->renderPartial('_emptyWhereAnd', array('model' => $TableCenter,)); ?>
</script>

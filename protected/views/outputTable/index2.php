<?php
	/**
	 * Created by PhpStorm.
	 * User: Alexey
	 * Date: 23.05.2016
	 * Time: 13:51
	 */

	$this->breadcrumbs = array(
		'OutputTable',
	);
?>

<h1>OutputTable</h1>

<div id="alert-er" class="alert alert-danger" role="alert" style="display: none">
	<h4>Не все таблицы выбраны!!!</h4>
</div>

<div id="template" style="float: left; padding: 0 15px">
	<?php echo CHtml::form('?r=OutputTable/template'); ?>
	<div class="row">
		<br> Выберите шаблон, который хотите вывести<br>
		<?php echo CHtml::dropDownList('TemplateList', '', OutputTable::GetTemplate(),
		                               array('empty' => 'Выберите шаблон',
		                                     'ajax'  => array(
			                                     'type'   => 'POST',
			                                     'url'    => $this->createUrl
			                                     ('outputTable/template'),
			                                     'update' => '#download'
		                                     )
		                               )
		); ?>
	</div>

	<div class="row buttons" id="download">
		<?php /*echo '</br>' . CHtml::submitButton('Вывести шаблон', array('id'    => 'Download',
		                                                                 'name'  => 'items',
		                                                                 'class' => 'btn btn-primary btn-left'
			)); */?>
	</div>
	<?php echo CHtml::endForm() ?>
</div>

<div id="new_create" style="border-left: double; display: flex; padding: 0 15px">
	<?php echo CHtml::form(''); ?>

	<div class="row">
		<br> Выберите таблицу, которая будет выводиться на пересечении сток и столбцов<br>
		<?php echo CHtml::dropDownList('ThreeTable', '', OutputTable::GetAllTable(),
		                               array('empty' => 'Выберите таблицу',
		                                     'ajax'  => array(
			                                     'type'   => 'POST',
			                                     'url'    => $this->createUrl
			                                     ('outputTable/Test1'),
			                                     'update' => '#AllTable'
		                                     )
		                               )
		); ?>
	</div>

	<div class="row">
		<br>Выберите таблицу, которая будет выводиться в шапке по колонкам<br>
		<?php echo CHtml::dropDownList('AllTable', '', array(),
		                               array('empty' => 'Выберите первую таблицу',
		                                     'ajax'  => array(
			                                     'type'   => 'POST',
			                                     'url'    => $this->createUrl('outputTable/Test2'),
			                                     'update' => '#TwoTable'
		                                     )
		                               )
		); ?>
	</div>

	<div class="row">
		<br> Выберите таблицу, которая будет выводиться в стоках<br>
		<?php echo CHtml::dropDownList('TwoTable', '', array(),
		                               array('empty' => 'Выберите вторую таблицу',
		                                     'ajax'  => array(
			                                     'type'   => 'POST',
			                                     'url'    => $this->createUrl('outputTable/Test3'),
			                                     'update' => '#Next'
		                                     )
		                               )
		); ?>
	</div>

	<div class="row buttons" id="Next">
		<?php /*echo '</br>' . CHtml::Button('Далее', array('id'          => 'Next',
	                                                  'name'        => 'items',
	                                                  'class'       => 'btn btn-primary btn-left',
	                                                  'visible'     => 'disabled')); */ ?>
	</div>

	<?php echo CHtml::endForm() ?>
</div>

<?php
	/**
	 * Created by PhpStorm.
	 * User: Alexey
	 * Date: 02.03.2016
	 * Time: 15:21
	 */

	$this->breadcrumbs = array(
		'OutputTable',
	);
?>

<h1>OutputTable</h1>

<div id="alert-er" class="alert alert-danger" role="alert" style="display: none">
	<h4>Не все таблицы выбраны!!!</h4>
</div>

<?php echo CHtml::form(''); ?>

<div class="row">
	Выберите таблицу, которая будет выводиться в шапке по колонкам<br>
	<?php echo CHtml::dropDownList('AllTable', '', OutputTable::GetAllTable(),
	                               array('empty' => 'Выберите таблицу',
	                                     'ajax'  => array(
		                                     'type'   => 'POST',
		                                     'url'    => $this->createUrl('OutputTable/ListTable'),
		                                     'update' => '#TwoTable'
	                                     )
	                               )
	); ?>
</div>

<div class="row">
	<br> Выберите таблицу, которая будет выводиться в стоках<br>
	<?php echo CHtml::dropDownList('TwoTable', '', array(),
	                               array('empty' => 'Выберите первую таблицу',
	                                     'ajax'  => array(
		                                     'type'   => 'POST',
		                                     'url'    => $this->createUrl
		                                     ('OutputTable/ListTableTwo'),
		                                     'update' => '#ThreeTable'
	                                     )
	                               )
	); ?>
</div>

<div class="row">
	<br> Выберите таблицу, которая будет выводиться на пересечении сток и столбцов<br>
	<?php echo CHtml::dropDownList('ThreeTable', '', array(),
	                               array('empty' => 'Выберите вторую таблицу',
	                                     'ajax'  => array(
		                                     'type' => 'POST',
		                                     'url'  => $this->createUrl
		                                     ('OutputTable/ListTableThree')
	                                     )
	                               )
	); ?>
</div>

<div class="row buttons">
	<?php echo '</br>' . CHtml::Button('Далее', array('id'          => 'Next',
	                                                  'name'        => 'items',
	                                                  'class'       => 'btn btn-primary btn-left',
	                                                  'form.target' => '_self')); ?>
</div>

<?php echo CHtml::endForm() ?>
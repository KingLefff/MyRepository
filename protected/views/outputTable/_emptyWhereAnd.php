<?php
	/**
	 * Created by PhpStorm.
	 * User: Alexey
	 * Date: 28.04.2016
	 * Time: 11:49
	 */?>
<div class="where-box" data-id="">

	<br>

	<?php echo CHtml::dropDownList('WhereCondition_1', '', $model->GetNameColumn(),
	                         array('empty' => 'Выберите столбец',)
	);?>

	<?php echo CHtml::dropDownList('WhereOperator', '', $model->getOperator(),
	                               array('empty' => '?'));?>

	<?php echo CHtml::dropDownList('WhereCondition_2', '', array(),
	                         array('empty' => 'Выберите что сравнивать'));
?>
	<br>
	<div id="choice_operator">
		<input type="radio" class="operator_a_o" name="operator_a_o" value="no" checked="checked"/> Нет условия<br />
		<input type="radio" class="operator_a_o" name="operator_a_o" value="and" /> AND<br />
		<input type="radio" class="operator_a_o" name="operator_a_o" value="or" /> OR<br />
	</div>

	<br>
	<a id="WApply" style="color: #953b39">Применить</a>
	<a id="WDel" style="color: #953b39">Удалить</a>
</div>
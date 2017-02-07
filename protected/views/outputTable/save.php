<?php
	/**
	 * Created by PhpStorm.
	 * User: Alexey
	 * Date: 12.09.2016
	 * Time: 10:58
	 */

	if (!isset($time))
	{
		echo CHtml::form('?r=OutputTable/save', 'post'); ?>
		<h3>Введите название шаблона:</h3>
		<?php echo CHtml::textField('NAME', '', array('size' => 60, 'maxlength' => 150)); ?>


		<div class="row buttons">
			<?php echo '</br>' . CHtml::submitButton('Сохранить', array('id'          => 'Save',
			                                                            'name'        => 'items',
			                                                            'class'       => 'btn btn-primary btn-left',
			                                                            'form.target' => '_self')); ?>
		</div>

		<?php echo CHtml::endForm();
	}else{
		echo '<h3>Не было найдено данных, которые необходимо сохранить 
					поэтому Вы будете перенаправлены на главную страницу данного сервиса</h3>';
		echo $time;
	} ?>
<?php
	$this->breadcrumbs = array(
		'OutputTable',
	);

	$this->menu = array(
		array('label' => 'Вернуться к выбору таблиц', 'url' => array('index')/*, 'linkOptions' => array('target'=>'_blank')*/
		      /*'visible'=> (Yii::app()->authManager->isAssigned('Admin', Yii::app()->user->id))*/)
	);
?>
<h2>Вывод таблицы</h2>
<div id="MixingTable">
	<?php

		$tblHr = $TableHeader->TableName;
		$tblCn = $TableColumn->TableName;
		$fkHr = $TableHeader->FkOne;
		$fkCn = $TableColumn->FkOne;
		$fkOneCr = $TableCenter->FkOne;
		$colCn = $TableColumn->Column;
		$colHr = $TableHeader->Column;
		$colCr = $TableCenter->Column;
		$fkCn2 = null;
		$fkHr2 = null;

		if ($fkCn != $colCn){
			$columns = $TableColumn->GetDataForHead();
		}
		else{
			$fkCn2=$fkCn.'_1';
			$columns = $TableColumn->GetDataForHead($fkCn2);
		}

		if ($fkHr != $colHr){
			$headers = $TableHeader->GetDataForHead();
		}
		else{
			$fkHr2=$fkHr.'_1';
			$headers = $TableHeader->GetDataForHead($fkHr2);
		}
		$valueHeader = array();
		$fail = [$fkOneCr=>'fail',$colCr=>'fail']

	?>
	<div id="Mix_Table" class="tableDiv">
		<table id="TableMix12" class="FixedTables">
			<thead>
			<tr>
				<th><?php echo $colCn . '\\ ' . $colHr ?></th>
				<?php

					foreach ($headers as $header)
					{
						echo '<th>' . $header[$colHr] . '</th>';
						$valueHeader[] = $header[empty($fkHr2)?$fkHr:$fkHr2];

					}
				?>
			</tr>
			</thead>
			<tbody>
			<?php

				foreach ($columns as $column)
				{
					echo '<tr>';
					echo '<td>' . $column[$colCn] . '</td>';
					$center = $TableCenter->GetDataForCenter($tblHr, $fkHr, $colHr,
					                                         $tblCn, $fkCn, $column[empty($fkCn2)?$fkCn:$fkCn2]);

					if (!empty($center))
					{
						foreach ($valueHeader as $item)
						{
							$i = 0;
							$count = 0;
							$str = '';
							do
							{
								$key = isset($center[$i]) ? $center[$i] : $fail;
								if ($key[$fkOneCr] == $item)
								{
									if ($count == 0)
									{
										$str = $key[$colCr];
										$count++;
									}
									else
										$str = $str.'/'.$key[$colCr];
								}
								$i++;
							}while($i<sizeof($center));

							if ($str != "")
							{
								echo '<td>' . $str . '</td>';
							}
							else
								echo '<td>----</td>';
						}
					}
					else
					{
						for ($i = 0; $i < sizeof($valueHeader); $i++)
							echo '<td>----</td>';
						continue;
					}
					echo '</tr>';
				}
			?>
			</tbody>
		</table>
	</div>

</div>
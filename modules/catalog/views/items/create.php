<?php
$this->title = Yii::t('easyii2/catalog', 'Create item');
?>
<?= $this->render('_menu', ['category' => $category]) ?>
<?= $this->render('_form', ['model' => $model, 'dataForm' => $dataForm]) ?>
<?php
$this->title = Yii::t('easyii2', 'Create category');
?>
<?= $this->render('_menu') ?>
<?= $this->render('_form', ['model' => $model, 'parent' => $parent]) ?>
<?php
$this->title = Yii::t('easyii2/article', 'Create article');
?>
<?= $this->render('_menu', ['category' => $category]) ?>
<?= $this->render('_form', ['model' => $model]) ?>
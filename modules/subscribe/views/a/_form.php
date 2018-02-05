<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\easyii2\widgets\Redactor;
?>
<?php $form = ActiveForm::begin([
    'enableClientValidation' => true
]); ?>
<?= $form->field($model, 'subject') ?>
<?= $form->field($model, 'body')->widget(Redactor::className(),[
    'options' => [
        'minHeight' => 400,
    ]
]) ?>
<?= Html::submitButton(Yii::t('easyii2', 'Send'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
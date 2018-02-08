<?php
use yii\easyii2\helpers\Image;
use yii\easyii2\widgets\DateTimePicker;
use yii\easyii2\widgets\TagsInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii2\widgets\Redactor;
use yii\easyii2\widgets\SeoForm;

$module = $this->context->module->id;
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>
<?= $form->field($model, 'title') ?>

<?php if($this->context->module->settings['articleThumb']) : ?>
    <?php if($model->image) : ?>
        <img src="<?= Image::thumb($model->image, 240) ?>">
        <a href="<?= Url::to(['/admin/'.$module.'/items/clear-image', 'id' => $model->primaryKey]) ?>" class="text-danger confirm-delete" title="<?= Yii::t('easyii2', 'Clear image')?>"><?= Yii::t('easyii2', 'Clear image')?></a>
    <?php endif; ?>
    <?= $form->field($model, 'image')->fileInput() ?>
<?php endif; ?>

<?php if($this->context->module->settings['enableShort']) : ?>
    <?= $form->field($model, 'short')->textarea() ?>
<?php endif; ?>

<?= $form->field($model, 'text')->widget(Redactor::className(),[
    'options' => [
        'minHeight' => 400,
        'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'article'], true),
        'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'article'], true),
        'plugins' => [
            "alignment",
            "clips",
            "counter",
            "definedlinks",
            "fontcolor",
            "fontfamily",
            "fontsize",
            "fullscreen",
            "filemanager",
            "imagemanager",
            "inlinestyle",
            "limiter",
            "properties",
            //"source",
            "table",
            //"textdirection",
            "textexpander",
            "video",
            "codemirror",
        ],
        'codemirror:' => [
            'lineNumbers' => true,
            'mode' => 'xml',
            'indentUnit' => 4
        ]
    ]
]) ?>

<?= $form->field($model, 'time')->widget(DateTimePicker::className()); ?>

<?php if($this->context->module->settings['enableTags']) : ?>
    <?= $form->field($model, 'tagNames')->widget(TagsInput::className()) ?>
<?php endif; ?>

<?php if(IS_ROOT) : ?>
    <?= $form->field($model, 'slug') ?>
    <?= SeoForm::widget(['model' => $model]) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii2', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
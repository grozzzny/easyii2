<?php
use yii\helpers\Url;

$this->title = Yii::t('easyii2', 'Admins');
?>

<?= $this->render('_menu') ?>

<?php if($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <th width="50">#</th>
            <th><?= Yii::t('easyii2', 'Username') ?></th>
            <th width="30"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($data->models as $admin) : ?>
            <tr>
                <td><?= $admin->admin_id ?></td>
                <td><a href="<?= Url::to(['/admin/admins/edit', 'id' => $admin->admin_id]) ?>"><?= $admin->username ?></a></td>
                <td><a href="<?= Url::to(['/admin/admins/delete', 'id' => $admin->admin_id]) ?>" class="glyphicon glyphicon-remove confirm-delete" title="<?= Yii::t('easyii2', 'Delete item') ?>"></a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <?= yii\widgets\LinkPager::widget([
            'pagination' => $data->pagination
        ]) ?>
    </table>
<?php else : ?>
    <p><?= Yii::t('easyii2', 'No records found') ?></p>
<?php endif; ?>
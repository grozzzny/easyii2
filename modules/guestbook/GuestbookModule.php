<?php
namespace yii\easyii2\modules\guestbook;

class GuestbookModule extends \yii\easyii2\components\Module
{
    public $settings = [
        'enableTitle' => false,
        'enableEmail' => true,
        'preModerate' => false,
        'enableCaptcha' => false,
        'mailAdminOnNewPost' => true,
        'subjectOnNewPost' => 'New message in the guestbook.',
        'templateOnNewPost' => '@easyii2/modules/guestbook/mail/en/new_post',
        'frontendGuestbookRoute' => '/guestbook',
        'subjectNotifyUser' => 'Your post in the guestbook answered',
        'templateNotifyUser' => '@easyii2/modules/guestbook/mail/en/notify_user'
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Guestbook',
            'ru' => 'Гостевая книга',
        ],
        'icon' => 'book',
        'order_num' => 80,
    ];
}
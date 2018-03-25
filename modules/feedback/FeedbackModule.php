<?php
namespace yii\easyii2\modules\feedback;

class FeedbackModule extends \yii\easyii2\components\Module
{

    public $settings = [
        'mailAdminOnNewFeedback' => true,
        'subjectOnNewFeedback' => 'New feedback',
        'templateOnNewFeedback' => '@app/mail/new_feedback',

        'answerTemplate' => '@easyii2/modules/feedback/mail/en/answer',
        'answerSubject' => 'Answer on your feedback message',
        'answerHeader' => 'Hello,',
        'answerFooter' => 'Best regards.',

        'enableTitle' => false,
        'enablePhone' => true,
        'enableCaptcha' => false,

        'modelFeedback' => '\yii\easyii2\modules\feedback\models\Feedback',
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Feedback',
            'ru' => 'Обратная связь',
        ],
        'icon' => 'earphone',
        'order_num' => 60,
    ];
}
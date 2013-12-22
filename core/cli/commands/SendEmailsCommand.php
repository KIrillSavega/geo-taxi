<?php

class SendEmailsCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $counter = 0;
        while ($counter < Yii::app()->notification->numberOfEmailsSentAtATime) {
            if ($emails = Yii::app()->notification->getEmailsPortion()) {
                Yii::app()->notification->sendEmails($emails);
                $counter += Yii::app()->notification->numberOfEmailsInPortion;
                if(count($emails) < Yii::app()->notification->numberOfEmailsInPortion) {
                    break;
                }
            } else {
                break;
            }
        }
    }
    
}

<?php
namespace app\controllers;

use app\models\getGraf;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;


class BalanceController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionUpload()
    {
        $file = UploadedFile::getInstanceByName('file');
        if ($file) {
            $model = new getGraf();
            $balanceData = $model->actionUpload($file);
            try {
                return $this->render('chart', ['balanceData' => $balanceData]);
            } catch (\Throwable $th) {
                throw new NotFoundHttpException('Не удалось сохранить файл.');
            }
        } else {
            return $this->render('index');
        }
    }
}

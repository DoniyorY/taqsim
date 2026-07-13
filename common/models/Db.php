<?php
namespace common\models;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
class Db extends Model{
    public function getFiles($files){
        //кол-во файлов сохраняем для использования в виджете
        Yii::$app->params['count_db'] = count($files);
        $arr = array();
        foreach($files as $key => $file){
            $arr[] = array('dump' =>$file);
        }
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $arr,
            'sort' => [
                'attributes' => ['dump'],
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $dataProvider;
    }
    public function import($path) {
        if (file_exists($path)) {
            $path = \yii\helpers\Html::encode($path);
            $db = Yii::$app->getDb();
            if (!$db) {
                Yii::$app->session->setFlash('error', 'Нет подключения к базе данных.');
            }
            //Экранируем скобку которая есть в пароле
            $db->password = str_replace("(","\(",$db->password);
            exec('mysql --host=' . $this->getDsnAttribute('host', $db->dsn) . ' --user=' . $db->username . ' --password=' . $db->password . ' ' . $this->getDsnAttribute('dbname', $db->dsn) . ' < ' . $path);
            Yii::$app->session->setFlash('success', 'Дамп ' . $path . ' успешно импортирован.');
        } else {
            Yii::$app->session->setFlash('error', 'Указанный путь не существует.');
        }
        return Yii::$app->response->redirect(['db/index']);
    }
    public function export($path = null) {
        $path = FileHelper::normalizePath(Yii::getAlias($path));
        if (file_exists($path)) {
            if (is_dir($path)) {
                if (!is_writable($path)) {
                    Yii::$app->session->setFlash('error', 'Дирректория не доступна для записи.');
                    return Yii::$app->response->redirect(['db/index']);
                }
                $fileName = 'dump_' . date('j.m.Y-H:i:s') . '.sql';
                $filePath = $path . DIRECTORY_SEPARATOR . $fileName;
                $db = Yii::$app->getDb();
                if (!$db) {
                    Yii::$app->session->setFlash('error', 'Нет подключения к базе данных.');
                    return Yii::$app->response->redirect(['db/index']);
                }
                //Экранируем скобку которая есть в пароле
                $db->password = str_replace("(","\(",$db->password);
                exec('mysqldump --host=' . $this->getDsnAttribute('host', $db->dsn) . ' --user=' . $db->username . ' --password=' . $db->password . ' ' . $this->getDsnAttribute('dbname', $db->dsn) . ' --skip-add-locks > ' . $filePath);
                Yii::$app->session->setFlash('success', 'Экспорт успешно завершен. Файл "'.$fileName.'" в папке ' . $path);
            } else {
                Yii::$app->session->setFlash('error', 'Путь должен быть папкой.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Указанный путь не существует.');
        }
        return Yii::$app->response->redirect(['db/index']);
    }
    //Возвращает название хоста (например localhost)
    private function getDsnAttribute($name, $dsn) {
        if (preg_match('/' . $name . '=([^;]*)/', $dsn, $match)) {
            return $match[1];
        } else {
            return null;
        }
    }
    public function delete($path) {
        if (file_exists($path)) {
            $path = \yii\helpers\Html::encode($path);
            unlink($path);
            Yii::$app->session->setFlash('success', 'Дамп БД удален.');
        } else {
            Yii::$app->session->setFlash('error', 'Указанный путь не существует.');
        }
        return Yii::$app->response->redirect(['db/index']);
    }
}
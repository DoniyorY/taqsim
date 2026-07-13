<?php
namespace backend\controllers;
use Yii;
use yii\web\Controller;
use yii\helpers\FileHelper;
use common\models\Db;
class DbController extends Controller{
    //Путь к файлам БД по-умолчанию
    public $dumpPath = '@frontend/web/db/';
    public function actionIndex($path = null){
        //Получаем массива путей к файлам с дампом БД (.sql)
        $path = FileHelper::normalizePath(Yii::getAlias($this->dumpPath));
        $files = FileHelper::findFiles($path, ['only' => ['*.sql'], 'recursive' => FALSE]);
        $model = new Db();
        //Метод формирует массив в нужный для виджета GridView формат с пагинацией
        $dataProvider = $model->getFiles($files);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionImport($path) {
        $model = new Db();
        //Метод делает импорт дампа БД
        $model->import($path);
    }
    public function actionExport($path = null) {
        $path = $path ? : $this->dumpPath;
        $model = new Db();
        //Метод экспортирует данные из БД в указанную папку
        $model->export($path);
        // $this->deleteOldDumps($path);
    }
    /**
     * Удаляет все файлы дампов, оставляя только $keep последних
     *
     * @param string $path путь к папке
     * @param int $keep сколько файлов хранить
     */
    protected function deleteOldDumps($path, $keep = 3)
    {
        $dir = Yii::getAlias($path);
        if (!is_dir($dir)) {
            return;
        }

        // ищем только dump_*.sql
        $files = glob($dir . '/dump_*.sql');

        if (!$files) {
            return;
        }

        // сортируем по времени модификации файла
        usort($files, function($a, $b) {
            return filemtime($b) <=> filemtime($a);
        });

        // оставляем только $keep файлов
        $toDelete = array_slice($files, $keep);

        foreach ($toDelete as $file) {
            @unlink($file);
        }
    }

    public function actionDelete($path) {
        $model = new Db();
        //Метод удаляет дамп БД
        $model->delete($path);
    }
}
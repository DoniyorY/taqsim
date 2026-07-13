<?php

namespace common\components;

class AlgenixLogger
{
    private static string $dir = '@runtime/logs/algenix';

    public static function log($message, $data = null)
    {
        $dirPath = \Yii::getAlias(self::$dir);

        // Создаём папку, если нет
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        $date = date('Y-m-d');
        $file = "$dirPath/algenix-$date.log";

        // Проверяем, есть ли вчерашний лог — архивируем
        self::rotateLogs($dirPath);

        $text = '[' . date('Y-m-d H:i:s') . '] ' . $message;

        if ($data !== null) {
            $text .= ' | DATA: ' . json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        file_put_contents($file, $text . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    private static function rotateLogs($dirPath)
    {
        $files = glob("$dirPath/algenix-*.log");

        $currentDate = date('Y-m-d');

        foreach ($files as $file) {
            if (strpos($file, $currentDate) !== false) {
                // сегодня — не архивируем
                continue;
            }

            // Архивируем
            $zipName = $file . '.zip';

            $zip = new \ZipArchive();
            if ($zip->open($zipName, \ZipArchive::CREATE)) {
                $zip->addFile($file, basename($file));
                $zip->close();

                // После архивации — удаляем оригинал
                unlink($file);
            }
        }
    }
}

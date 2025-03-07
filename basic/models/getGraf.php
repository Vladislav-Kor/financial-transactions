<?php
/**
 * @author Vladislav-Kor
 * @email corchagin.vlad2005@yandex.ru
 * @create date 2024-07-12
 * @modify date 2024-07-12
 * @desc [description]
 */

namespace app\models;

use DOMDocument;
use yii\web\NotFoundHttpException;

class getGraf
{    
    /**
     * Method actionUpload
     *
     * @param object $file 
     *
     * @return array
     */
    public function actionUpload($file): array
    {
        $balanceData = [];
       
        // Убедитесь, что папка существует
        $uploadDir = \Yii::getAlias('@webroot/uploads');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Генерируем уникальное имя для файла
        $uniqueName = uniqid() . '.' . $file->extension;

        // Путь для сохранения файла
        $filePath = $uploadDir . '/' . $uniqueName;

        // Сохраняем файл
        if ($file->saveAs($filePath)) {
            // проверяю сохранился ли
            if (file_exists($filePath)) {
            // Обрабатываем файл (например, парсим его)
            $data = $this->parseFile($filePath);
            // var_dump($data);
            $balanceData = $this->processData($data);
            // удаляем после парсинга
                unlink($filePath);
            }
            
        } else {
            throw new NotFoundHttpException('Не удалось сохранить файл.');
        }
       
        return $balanceData;
    }

    private function parseFile($filePath): array
    {
        $html = file_get_contents($filePath);
        $dom = new DOMDocument();

        // Укажите кодировку
        @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);

        $tables = $dom->getElementsByTagName('table');

        if ($tables->length === 0) {
            throw new NotFoundHttpException('Таблица не найдена в файле');
        }

        // Найти таблицу Closed Transactions.
        $targetTable = null;
        foreach ($tables as $table) {
            $rows = $table->getElementsByTagName('tr');
            if ($rows->length > 0) {
                $firstRow = $rows->item(0);
                $cells = $firstRow->getElementsByTagName('td');
                foreach ($cells as $cell) {
                    if (strpos(strtolower($cell->nodeValue), 'closed transactions') !== null) {
                        $targetTable = $table;
                        break 2; // выходим из обоих циклов
                    }
                }
            }
        }

        if (!$targetTable) {
            throw new NotFoundHttpException('Таблица с "Closed Transactions" не найдена');
        }

        $data = [];
        $headers = [];
        $rows = $targetTable->getElementsByTagName('tr');

        // Получаем заголовки
        $headerRow = $rows->item(2); // Третья строка (индекс 2) содержит заголовки
        if ($headerRow) {
            $ths = $headerRow->getElementsByTagName('td'); // В данном случае, используются TD, а не TH
            foreach ($ths as $th) {
                $headers[] = trim($th->nodeValue);
            }
        }

        // Извлечение данных
        for ($i = 3; $i < $rows->length; $i++) { // начинаем с четвертой строки (индекс 3)
            $row = $rows->item($i);
            $rowData = [];
            $cells = $row->getElementsByTagName('td');

            // Используем количество заголовков для создания rowData
            for ($j = 0; $j < count($headers); $j++) {
                if ($j < $cells->length) {
                    $cell = $cells->item($j);
                    $value = trim($cell->nodeValue);
                    // Удаляем форматирование "mso-number-format"
                    $value = preg_replace('/style="mso-number-format:[^"]*;"/', '', $value);

                    $rowData[$headers[$j]] = $value;
                } else {
                    $rowData[$headers[$j]] = ''; // Если ячейка отсутствует, ставим пустую строку
                }
            }

            $data[] = $rowData;
        }

        return $data;
    }

    
    
    /**
     * Method processData
     *
     * @param array $data
     *
     * @return array
     */
    private function processData($data)
    {

        $balanceData = [];
        $balance = 0;

        if (empty($data)) {
            throw new NotFoundHttpException('Данные пусты');
        }

        foreach ($data as $item) {
            // var_dump($item);
            if (isset($item['Profit']) && is_numeric(abs((float)$item['Profit']))) {
                $balance = (float) $item['Price'];
                if ($balance < 0) {
                    $balance = 0; // Баланс не может быть отрицательным
                }
                $balanceData[] = $balance;
            }
        }

        return $balanceData;
    }

}
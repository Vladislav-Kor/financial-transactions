тестовое задание
# Анализ и визуализация финансовых операций

Исходные данные:
Есть отчет в виде таблицы. Строка таблицы - одна сделка. Каждая сделка приносит либо прибыль, либо убыток и влияет на сумму баланса. Изменение баланса после проведения сделки указано в столбце profit.

Задача:
Написать скрипт на php (на yii2, если вы его знаете), который парсит отчет со сделками (см. вложение) и показывает в виде графика значение баланса в зависимости от каждой операции. Предусмотреть возможность загрузки любого подобного файла и построения по загруженным данным нового графика.

Обратите внимание - нужно учитывать все строки, в которых в столбце profit число, тип операции учитывать не нужно. profit - это изменение баланса, а не баланс. Баланс не может быть отрицательным.

Хотя это тестовое задание, проверять его мы будем, как полноценное приложение. 
Логика приложения несложная, поэтому сделайте его максимально продуманным и интерактивным с вашей точки зрения.

Критерии, по которым будет проверяться тестовое задание:
1. Использованное решение для построения графика
2. Достоверность графика по исходному файлу и по похожим файлам
3. Устойчивость к негативным тестам (другие файлы)
5. Валидность верстки начальной и конечной страниц
6. Соответствие интерфейса поставленной задаче

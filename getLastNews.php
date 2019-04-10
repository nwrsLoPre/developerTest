<?php
/**
 * Created on 08.04.19
 *
 * Новостной сайт Lenta.ru отдает список новостей в формате rss по адресу https://lenta.ru/rss.
 * Скрипт при запуске из командной строки выведет последние 5 новостей в формате
 *     Название
 *     Ссылка на новость
 *     Анонс
 */

$url = 'https://lenta.ru/rss';
$elementsForViewCount = 5;
$arArticles = null;
$arArticlesSlice = null;

// Интерпретирует XML-файл в объект SimpleXMLElement
$rss = simplexml_load_file($url);

if (is_object($rss->channel)) {
    foreach ($rss->channel->item as $item) {
        $arArticles[] = [
            'title' => $item->title,
            'link' => $item->link,
            'description' => $item->description
        ];
    }
}

$arArticlesSlice = array_slice($arArticles, count($arArticles) - $elementsForViewCount);
if (is_array($arArticlesSlice)) {
    foreach ($arArticlesSlice as $key => $article) {

        if ($key == 0) {
            echo PHP_EOL;
        }

        echo "{$article['title']} ({$article['link']})" . PHP_EOL;
        echo wordwrap($article['description'], 220, PHP_EOL) . PHP_EOL;
    }
}

<?php

use App\Website;
use Illuminate\Log\Writer;
use Monolog\Logger;

function getArticleStatusClass ($status) {
    switch ($status) {
        case "0": $r = 'active'; break;
        case "1": $r = 'success'; break;
        case "2": $r = 'warning'; break;
        case "3": $r = 'danger'; break;
        default: $r = 'active';
    }
    return $r;
}

function getArticleStatusText ($status) {
    switch ($status) {
        case "0": $r = '隐藏'; break;
        case "1": $r = '显示'; break;
        case "2": $r = 'warning'; break;
        case "3": $r = 'danger'; break;
        default: $r = 'active';
    }
    return $r;
}

function getArticleIsTopClass ($isTop) {
    switch ($isTop) {
        case "0": $r = 'active'; break;
        case "1": $r = 'success'; break;
        default: $r = 'active';
    }
    return $r;
}

function getArticleIsTopText ($isTop) {
    switch ($isTop) {
        case "0": $r = '未置顶'; break;
        case "1": $r = '已置顶'; break;
        default: $r = 'active';
    }
    return $r;
}

function getWebsiteByWsid($wsid) {
    return Website::getGameNameByWsid($wsid);
}

function timeToFormatTime($time, $format) {
    return date($format, strtotime($time));
}

function createLog($message) {
    $log = new Writer(new Logger('signin'));
    $log->useDailyFiles(storage_path().'/logs/info.log',30);
    $log->write('info', $message);
}
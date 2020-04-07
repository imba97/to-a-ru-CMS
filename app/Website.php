<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    /**
     * 获取网站配置信息
     */
    static public function getInfoByWsid($wsid) {
        return self::find($wsid)->toArray();
    }

    /**
     * 根据网站ID获取默认作者
     */
    static public function getDefaultAuthorByWsid ($wsid) {
        $d_author = self::where('id', $wsid)->value('d_author');
        return !empty($d_author) ? $d_author : '未知';
    }

    /**
     * 根据网站ID获取需要复制的文件
     */
    static public function getCopyfileByWsid($wsid) {
        $copyfile = self::where('id', $wsid)->value('copyfile');
        return $copyfile;
    }

    /**
     * 根据网站ID获取手机端tag
     */
    static public function getMobileTagByWsid($wsid) {
        return self::where('id', $wsid)->value('m_tag');
    }

    static public function getGameNameByWsid($wsid) {
        return self::where('id', $wsid)->value('gamename');
    }
    static public function getGameNamesByWsids($wsids) {
        return self::whereIn('id', $wsids)->get(['id','gamename'])->toArray();
    }
}

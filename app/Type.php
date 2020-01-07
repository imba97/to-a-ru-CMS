<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    //
    static public function getTypeIDByTag($tag) {
        return self::where('tag', $tag)->value('id');
    }

    static public function getTypeTDescByTypeID ($typeID) {
        return self::where('id', $typeID)->value('t_desc');
    }

    static public function getTagByTypeID($typeID) {
        return self::where('id', $typeID)->value('tag');
    }
}

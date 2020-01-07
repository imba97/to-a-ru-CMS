<?php

namespace App\Http\Controllers\Home;

use App\Article;
use App\Type;
use App\Website;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class BuildController extends Controller
{

    // 网站类型
    const SITE_TYPE_PC = 0;
    const SITE_TYPE_MOBILE = 1;

    //
    public $template_path;
    public $dist_path;

    // array 里面存放文章ID 在里面的文章不管有没有生成文件，都会再次重新生成
    public $wipeDist;

    public function __construct()
    {
        $this->middleware('auth');
        $this->wipeDist = array();

        $this->template_path = public_path('cms_template');
        $this->dist_path = public_path('cms_dist');
    }

    public function runAll(Request $request) {

        $wsid = $request->get('wsid');

        // 复制文件
        $this->_copyFile($wsid);

        $req_id = $request->get('id');

        $runType = $request->get('runType');

        $runTypeList = explode(',', $runType);

        if($runType === null || empty($runTypeList)) {
            $runTypeList = array('index', 'list');
        }

        // 如果有id传参
        if($req_id !== NULL) {
            // 以逗号分隔，如果只有一个值，说明只有一个ID
            $req_id_arr = explode($req_id);
            foreach($req_id_arr as $req_id_value) {
                if(empty($req_id_value)) continue;
                array_push($this->wipeDist, $req_id_value);
            }
        }

        foreach($runTypeList as $type) {
            switch($type) {
                case 'index': $this->runIndex($wsid); break;
                case 'list': $this->runList($wsid); break;
            }
        }

        return response()->json($request);
    }

    /**
     * 生成首页
     */
    public function runIndex($wsid) {

        $path = $this->_initDir($wsid);

        $buildData = array(
            'dist_path'  => $path['dist_path'] . '/' . $this->_getTemplateFileName('index'),
            'tpl_path'  =>  $path['tpl_path'] . '/' . $this->_getDistFileName('index'),
            'mobile_dist_path'  =>  $path['mobile_dist_path'] . '/' . $this->_getTemplateFileName('index'),
            'mobile_tpl_path'  =>  $path['mobile_tpl_path'] . '/' . $this->_getDistFileName('index'),
            'wsid'      =>  $wsid
        );

        // web版

        $repeaters = $this->_getRepeatersByTemplatePath($buildData['tpl_path']);

        // 数据库读取的文章信息
        $replaceData = array();

        // 根据页面的 repeater 获取数据库文章信息
        foreach($repeaters as $repeaterKey => $repeaterValue) {
            /**
             * 根据不同需要获取相应的文章信息
             * ZX：最新，自动列出最新文章，不受cms_type数据库控制
             * TT：头条，查询最后一条头条文章，不受cms_type数据库控制
             * 其他：此类文章会根据模板对应的 <repeater id="XX"> 来查询 XX是cms_type数据库的tag字段
             */

            switch ($repeaterKey) {
                // 获取最新文章
                case 'ZX':
                    $replaceData['ZX'] = $this->_getZX($repeaterValue['max'], $buildData['wsid']);
                    break;
                // 获取头条文章
                case 'TT':
                    $replaceData['TT'] = $this->_getTT($buildData['wsid']);
                    break;
                default:
                    // 根据 RepeaterXX 后面的Tag获取文章
                    $replaceData[$repeaterValue['tag']] = $this->_getArticleByTag($repeaterValue['max'], $buildData['wsid'], $repeaterValue['tag']);
            }
        }

        // 已生成的文章ID
        foreach($replaceData as $repDataArticles) {
            foreach($repDataArticles as $articleInfo) {
                $this->runArticle($wsid, $articleInfo['id']);
            }
        }

        $this->_build($buildData, $repeaters, $replaceData, self::SITE_TYPE_PC);


        // mobile 版，如果存在mobile版则继续执行下面的代码
        if(!$this->_isMobileSite($wsid)) return false;

        $mobileRepeaters = $this->_getRepeatersByTemplatePath($buildData['mobile_tpl_path']);

        // 数据库读取的文章信息
        $mobileReplaceData = array();

        // 根据页面的 repeater 获取数据库文章信息
        foreach($mobileRepeaters as $mobileRepeaterKey => $mobileRepeaterValue) {
            /**
             * 根据不同需要获取相应的文章信息
             * ZX：最新，自动列出最新文章，不受cms_type数据库控制
             * TT：头条，查询最后一条头条文章，不受cms_type数据库控制
             * 其他：此类文章会根据模板对应的 <repeater id="XX"> 来查询 XX是cms_type数据库的tag字段
             */

            switch ($mobileRepeaterKey) {
                // 获取最新文章
                case 'ZX':
                    $mobileReplaceData['ZX'] = $this->_getZX($mobileRepeaterValue['max'], $buildData['wsid']);
                    break;
                // 获取头条文章
                case 'TT':
                    $mobileReplaceData['TT'] = $this->_getTT($buildData['wsid']);
                    break;
                default:
                    // 根据 RepeaterXX 后面的Tag获取文章
                    $mobileReplaceData[$mobileRepeaterValue['tag']] = $this->_getArticleByTag($mobileRepeaterValue['max'], $buildData['wsid'], $mobileRepeaterValue['tag']);
            }
        }

        // 已生成的文章ID
        foreach($mobileReplaceData as $mobileRepDataArticles) {
            foreach($mobileRepDataArticles as $mobileArticleInfo) {
                $this->runArticle($wsid, $mobileArticleInfo['id']);
            }
        }

        $this->_build($buildData, $repeaters, $replaceData, self::SITE_TYPE_MOBILE);
    }

    /**
     * 生成列表页
     * @param $wsid
     */
    public function runList($wsid) {

        $path = $this->_initDir($wsid);

        $distFilePath = $path['dist_path'] . '/list/';

        $buildData = array(
            'dist_path'  => '',
            'tpl_path'  =>  $path['tpl_path'] . '/' . $this->_getDistFileName('list'),
            'wsid'      =>  $wsid
        );

        $repeaters = $this->_getRepeatersByTemplatePath($buildData['tpl_path']);

        $articlesData = $this->_getListArticles($buildData['wsid'],$repeaters['list']['tags']);

        foreach($articlesData as $articleTypeTag => $articles) {

            // 文章数
            $articlesCount = count($articles) > 0 ? count($articles) : 1;

            // 每页的数量
            $listPageMax = !empty($repeaters['list']['pageMax']) ? $repeaters['list']['pageMax'] : 10;

            // 根据文章数计算总页数
            $pageMax = intval(ceil($articlesCount / $listPageMax));

            // 根据页数循环生成列表页
            for($i = 1; $i <= $pageMax; $i++) {

                // 每次循环重新构造生成文件的名称 格式：tag-page
                $buildData['dist_path'] = $distFilePath . $this->_getTemplateFileName($articleTypeTag. '-' . $i);
                // 替换数据，选取 $i 页开始 最多 $listPageMax 条数据
                $replaceData = array('list' => array_slice($articles,($i - 1) * $listPageMax, $listPageMax));

                // 分页构造
                $nonePage = 'javascript:void(0);';
                $firstPage = $i != 1 ? $this->_getTemplateFileName($articleTypeTag . '-1') : $nonePage;
                $previousPage = ($i - 1) !== 0 ? $this->_getTemplateFileName($articleTypeTag. '-' . ($i - 1)) : $nonePage;
                $nextPage = ($i + 1) <= $pageMax ? $this->_getTemplateFileName($articleTypeTag. '-' . ($i + 1)) : $nonePage;
                $lastPage = $i != $pageMax ? $this->_getTemplateFileName($articleTypeTag. '-' . $pageMax) : $nonePage;

                // 页数大于一，生成分页按钮
                $replaceData['paginate'] = $pageMax === 1 ? '' : "
                    <a href='$firstPage' class='pageOne'>首页</a>
                    <a href='$previousPage'>上一页</a>
                    <a href='$nextPage'>下一页</a>
                    <a href='$lastPage' class='pageOne'>尾页</a>
                    &nbsp; 第 $i / $pageMax 页
                ";

                // biu <(￣︶￣)>
                $this->_build($buildData, $repeaters, $replaceData, self::SITE_TYPE_PC);
            }

            // 接下来再循环文章目录
            foreach($articles as $articleValue) {
                $this->runArticle($wsid, $articleValue['id']);
            }

        }
    }

    /**
     * 发布文章
     * @param $wsid
     * @param $articleID
     * @return bool
     */
    public function runArticle($wsid, $articleID) {

        $path = $this->_initDir($wsid);

        $buildData = array(
            'dist_path'  => $path['dist_path'] . '/articles/' . $this->_getTemplateFileName($articleID),
            'tpl_path'  =>  $path['tpl_path'] . '/' . $this->_getDistFileName('article'),
            'mobile_dist_path'  => $path['mobile_dist_path'] . '/articles/' . $this->_getTemplateFileName($articleID),
            'mobile_tpl_path'  =>  $path['mobile_tpl_path'] . '/' . $this->_getDistFileName('article'),
            'wsid'      =>  $wsid
        );

        // 如果在 $this->wipeDist 中 或者 没有这篇文章 则会生成
        if(in_array($articleID, $this->wipeDist) || !file_exists($buildData['dist_path'])) {
            // 文章页
            $replaceData['article'] = $this->_getArticleByID($articleID);

            $repeaters = $this->_getRepeatersByTemplatePath($buildData['tpl_path']);

            $this->_build($buildData, $repeaters, $replaceData, self::SITE_TYPE_PC);
        }

        if(!$this->_isMobileSite($wsid)) return false;

        // 如果在 $this->wipeDist 中 或者 没有这篇文章 则会生成
        if(in_array($articleID, $this->wipeDist) || !file_exists($buildData['mobile_dist_path'])) {
            // 文章页
            $replaceData['article'] = $this->_getArticleByID($articleID);

            $repeaters = $this->_getRepeatersByTemplatePath($buildData['mobile_tpl_path']);

            $this->_build($buildData, $repeaters, $replaceData, self::SITE_TYPE_MOBILE);
        }

    }

    private function runSide($wsid) {
        $path = $this->_initDir($wsid);
    }

    /**
     * 根据模板页面自动查询数据库文章并替换
     * @param $buildData array 构建信息
     * - tpl_path   模板路径
     * - dist_path  输出路径
     * - wsid       网站ID
     * @param $repeaters array 页面上的 <repeater></repeater>
     * @param $replaceData array 替换的数据
     * @param $siteType int 网站类型 PC|MOBILE
     * @return bool
     */
    private function _build($buildData, $repeaters, $replaceData, $siteType) {

        switch($siteType) {

            // 手机站
            case self::SITE_TYPE_MOBILE :
                $tpl_path = $buildData['mobile_tpl_path'];
                $dist_path = $buildData['mobile_dist_path'];
                break;

            // PC站
            case self::SITE_TYPE_PC :
            default :
                $tpl_path = $buildData['tpl_path'];
                $dist_path = $buildData['dist_path'];
        }

        // 模板文章内容
        if(!file_exists($tpl_path)) return false;
        $content = file_get_contents($tpl_path);

        // 第一层循环，循环 页面上的每个 repeater
        foreach($replaceData as $repeaterTag => $articleContent) {

            // 每次循环清空 $newContent
            $newContent = '';

            // 如果是数组则解析后替换模板中相应的 {XX}
            if(gettype($articleContent) === 'array') {

                // 第二层循环，循环 repeater 替换内容 repeaterContent
                foreach($articleContent as $key => $rc) {

                    // 每次循环让模板内容变为初始未替换过的模板内容
                    $tplContent = $repeaters[$repeaterTag]['html'];

                    // 添加自定义属性
                    $rc['tagIcon'] = Type::getTagByTypeID($rc['type']);
                    $rc['created_at_format_m_d'] = timeToFormatTime($rc['created_at'], 'm-d');
                    $rc['created_at_format_Y_m_d'] = timeToFormatTime($rc['created_at'], 'Y-m-d');

                    // 第三层循环，循环 模板页面上 {XX} 对应的替换内容
                    foreach($rc as $rcKey => $rcValue) {

                        // 重新构造变量值
                        switch ($rcKey) {
                            case 'type':
                                $rcValue = Type::getTypeTDescByTypeID($rcValue);
                                break;
                        }

                        // 替换模板变量
                        $tplKey = '/{' . $rcKey . '}/';
                        $tplContent = preg_replace($tplKey, $rcValue, $tplContent);
                    }
                    // 第三层循环结束
                    $newContent .= $tplContent;
                }
                // 第二层循环结束

            } else if(gettype($articleContent) === 'string') {
                // 如果是字符串则直接替换
                $newContent = $articleContent;
            }

            $content = preg_replace('/<repeater.*?id="'.$repeaterTag.'".*?>(?:.|\n)*?<\/repeater>/', $newContent, $content);
        }
        // 第一层循环结束

        // 生成文件
        $html_file = fopen($dist_path, 'w') or die('Unable to open file!');
        fwrite($html_file, $this->compress_html($content));
        fclose($html_file);
        chmod($dist_path, 0777);
    }

    /**
     * 初始化文件夹，如果没有生成模板存放目录则创建
     * @param $wsid integer 网站ID
     * @return array 返回文件夹路径
     */
    private function _initDir($wsid) {
        // 模板目录
        $tpl_path = $this->template_path . '/' . $wsid;

        // 生成HTML的文件目录
        $dist_path = $this->dist_path . '/' . $wsid;

        // 生成mobile的主目录路径
        $webSiteInfo = Website::getInfoByWsid($wsid);
        $mobile_tpl_path = $this->template_path . '/' . $wsid . '/' . $webSiteInfo['m_tag'];
        $mobile_dist_path = $this->dist_path . '/' . $wsid . '/' . $webSiteInfo['m_tag'];

        // 文章目录
        $articles_path = $dist_path . '/articles';
        $mobile_articles_path = $mobile_dist_path . '/articles';

        // 列表目录
        $list_path = $dist_path . '/list';
        $mobile_list_path = $mobile_dist_path . '/list';


        // 检查路径列表
        $createDir = array(
            $dist_path,
            $mobile_dist_path,

            $articles_path,
            $mobile_articles_path,

            $list_path,
            $mobile_list_path
        );

        // 循环检查，没有则生成
        foreach($createDir as $path) {
            is_dir($path) or mkdir($path, 0777, true);
        }

        return array(
            'tpl_path'      =>  $tpl_path,
            'dist_path'     =>  $dist_path,
            'mobile_tpl_path'  =>  $mobile_tpl_path,
            'mobile_dist_path' =>  $mobile_dist_path,
            'articles_path' =>  $articles_path
        );
    }

    /**
     * 复制文件
     * @param $wsid
     * @return bool
     */
    private function _copyFile($wsid) {

        $path = $this->_initDir($wsid);

        $copyfile = Website::getCopyfileByWsid($wsid);

        // 没设置则停止运行
        if(empty($copyfile)) return false;

        // 可能有多个，转数组
        $copyfileList = explode(',', $copyfile);

        foreach($copyfileList as $copyfilePath) {
            // 获取模板目录下原文件
            $tpl_copyfile = $path['tpl_path'] . '/' . $copyfilePath;
            $dist_copyfile = $path['dist_path'] . '/' . $copyfilePath;
            // 如果没有则跳过
            if(!file_exists($tpl_copyfile)) break;

            // 复制文件
            copy($tpl_copyfile, $dist_copyfile);
        }
    }

    private function _isMobileSite($wsid) {
        $mobileTag = Website::getMobileTagByWsid($wsid);
        return !empty($mobileTag);
    }

    /**
     * 根据网站ID获取状态为显示的最新文章
     * @param $max integer 获取文章数
     * @param $wsid integer 网站ID
     * @return mixed
     */
    private function _getZX($max, $wsid) {

        $where = array(
            'status' => 1,
            'wsid' => $wsid
        );
        $articlesZX = Article::where($where)->orderBy('created_at', 'desc')->take($max)->get();

        if(!$articlesZX) {
            return false;
        }

        return $articlesZX->toArray();

    }

    /**
     * 获取1条头条文章
     * @param $wsid integer 网站ID
     * @return mixed
     */
    private function _getTT($wsid) {

        $where = array(
            'status' => 1,
            'istop' => 1,
            'wsid' => $wsid
        );
        $articlesTT = Article::where($where)->orderBy('created_at', 'desc')->first();

        // 如果没有置顶文章就返回最新一篇
        if(!$articlesTT) {
            unset($where['istop']);
            return array(
                Article::where($where)->orderBy('created_at', 'desc')->first()->toArray()
            );
        }

        return array(
            $articlesTT->toArray()
        );

    }

    /**
     * 根据TypeID获取文章
     * @param $max integer 文章数
     * @param $wsid integer 网站ID
     * @param $typeID integer 类型ID
     * @return mixed
     */
    private function _getArticleByTag ($max, $wsid, $tag) {
        $where = array(
            'status' => 1,
            'wsid' => $wsid,
            'type' => Type::getTypeIDByTag($tag)
        );

        $articlesXX = Article::where($where)->orderBy('created_at', 'desc')->take($max)->get();

        if(!$articlesXX) {
            return false;
        }

        return $articlesXX->toArray();
    }

    private function _getArticleByID ($articleID) {
        $article = Article::find($articleID);
        if(!$article) {
            return false;
        }
        return array(
            $article->toArray()
        );
    }

    /**
     * 根据模板文件路径获取模板中的 <repeater> 标签
     * @param $tpl_path
     * @return array|bool 返回格式： array( 'html' 模板 <repeater> 标签中的 HTML, 'param' 数组，返回所有标签属性 )
     */
    private function _getRepeatersByTemplatePath ($tpl_path) {

        // 模板文章中的 Repeater
        $repeaters = array();

        if(!file_exists($tpl_path)) return false;
        $templateContent = file_get_contents($tpl_path);

        // 正则表达式提取所有 repeater 标签
        preg_match_all('/<repeater(.*)?>((?:.|\n)*?)<\/repeater>/', $templateContent, $getRepeater);

        // 转为 array ( 'XX' => 'repeater模板内容' ) 格式的数组
        foreach($getRepeater[1] as $repeaterKey => $RepeaterValue) {

            if(empty($RepeaterValue)) continue;

            $paramString = explode(' ', trim($RepeaterValue));

            $param = array();

            foreach($paramString as $paramKey => $paramValue) {
                $paramArr = explode('=', $paramValue);

                if($paramArr[0] !== 'html') {
                    $param[$paramArr[0]] = str_replace('"', '', $paramArr[1]);
                }
            }

            $param['max'] = isset($param['max']) ? intval($param['max']) : 1;
            $param['tag'] = isset($param['tag']) ? $param['tag'] : $param['id'];

            $param['html'] = $getRepeater[2][$repeaterKey];

            $repeaters[$param['tag']] = $param;

        }

        return $repeaters;
    }

    private function _getListArticles($wsid, $tags) {

        $result = array();

        // 查询所有文章
        $articles = Article::where(['wsid' => $wsid, 'status' => 1])
            ->orderBy('created_at', 'desc')->get()->toArray();

        // 获取需要的列
        $tags_arr = explode(',', $tags);

        // 最新文章等于按时间排序的所有文章
        $result['ZX'] = $articles;

        // 其他文章根据
        foreach($tags_arr as $tag) {
            if($tag !== 'ZX')  $result[$tag] = array();
            $type = Type::getTypeIDByTag($tag);
            foreach($articles as $articleIndex => $article) {
                if($article['type'] === $type) {
                    array_push($result[$tag], $article);
                    unset($articles[$articleIndex]);
                }
            }
        }

        return $result;
    }

    private function _getTemplateFileName($name) {
        return $name . '.' . config('build.template.ext');
    }

    private function _getDistFileName($name) {
        return $name . '.' . config('build.dist.ext');
    }

    // 去掉HTML的空格、换行
    public function compress_html($string) {
        $string = str_replace("\r\n", '', $string); //清除换行符
        $string = str_replace("\n", '', $string); //清除换行符
        $string = str_replace("\t", '', $string); //清除制表符
        $pattern = array (
            "/> *([^ ]*) *</", //去掉注释标记
            "/[\s]+/",
            "/<!--[^!]*-->/",
            "/\" /",
            "/ \"/",
            "'/\*[^*]*\*/'"
        );
        $replace = array (
            ">\\1<",
            " ",
            "",
            "\"",
            "\"",
            ""
        );
        return preg_replace($pattern, $replace, $string);
    }
}
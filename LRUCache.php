<?php

class LRUCache {

    const DEFAULT_LIMIT = 2;
    const DEFAULT_EXPIRE = 100;

    private $list = array();
    // 限界保持数
    private $limit;
    // キャッシュ最大保持数
    private $maxCacheSize;

    private $createTime;

    function __construct( $limit = self::DEFAULT_LIMIT )
    {

        $this->setCacheSize($limit);
        $this->modCacheExpire( time() );

    }

    function setCacheSize($limit = self::DEFAULT_LIMIT) {

        if( ! is_int( $limit ) ){
            $this->maxCacheSize = self::DEFAULT_LIMIT;
        }else{
            $this->maxCacheSize = $limit;
        }

    }

    /**
     * キャッシュ最大保持数を返す
     **/

    function getCacheSize() {

        $currentCacheSize = count( $this->list );

        if ( $this->maxCacheSize <  $currentCacheSize ){
            return $this->maxCacheSize;
        }

        return $currentCacheSize ;

    }

    /**
     * 内部保持時間を返す
     **/
    function getCacheExpire( $key ){
        return $this->expire;
    }

    function getOldest() {
        reset( $this->list);
        $value = key($this->list);
        return $value;
    }

    /**
     * キャッシュ作成時間を修正する
     **/
    function modCacheExpire( $unixtime ){

        $this->createTime = $unixtime;

    }
    
    function put( $key, $value , $expire = self::DEFAULT_EXPIRE ){

        unset($this->list[$key]);

        $this->list[$key]["value"] =  $value ;
        $this->list[$key]["expire"] = time() + $expire - $this->createTime;
        
        $this->refreshCache();
    }

    function get( $key ){
        
        $this->refreshCache();

        if (!array_key_exists($key, $this->list)) {
            return null;
        }

        $value = $this->list[$key]["value"];
        $expire = $this->list[$key]["expire"] + $this->createTime - time();

        $this->put($key, $value, $expire);

        return $value;
    }

    function refreshCache( ){

        while( count($this->list) > $this->maxCacheSize ) {
            unset( $this->list[$this->getOldest()]);
        }

        // 保持終了対象時間
        $checkEndTime = time() - $this->createTime;

        foreach( $this->list as $key => $cacheItem ){

            if( $checkEndTime > $cacheItem["expire"] ){
                unset( $this->list[$key] );
            } 

        }
    
    }

    function showCache () {
        var_dump( $this->list );
    }

}


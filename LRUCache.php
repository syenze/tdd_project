<?php

class LRUCache {
    const DEFAULT_LIMIT = 2;

    private $list = array();
    // 限界保持数
    private $limit;

    function __construct ( $limit = self::DEFAULT_LIMIT ){
        $this->changeLimit($limit);
    }

    function changeLimit($limit = self::DEFAULT_LIMIT) {
        $this->limit = $limit;
        $tmp = $this->list;
        foreach($tmp as $key => $val) {
            $this->put($key, $val);
        }
    }

    function put( $key, $value ){
        unset($this->list[$key]);
        $this->list[$key] = $value;
        // if( count($this->list) > $this->limit ){
        while( count($this->list) > $this->limit ) {
            unset( $this->list[$this->getOldest()]);
        }
    }

    function get( $key ){
        if (!array_key_exists($key, $this->list)) {
            return null;
        }
        $value = $this->list[$key];
        $this->put($key, $value);
        return $value;
    }

    function getOldest() {
//        reset($this->list);
        return key($this->list);
    }

    function getLimit() {
        return $this->limit;
    }
}


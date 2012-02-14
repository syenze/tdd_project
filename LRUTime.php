<?php

class LRUTime {

    // 管理時刻
    private $unixtime;
    
    function __construct (){
        $this->unixtime = mktime();
    }

    public function getTime(){
        return $this->unixtime;
    }

    public function getFmtTime( $format ){
        return strftime( $format , $this->unixtime );
    }

    public function setTime( $time ){
        // 引数の型、 validation 
        $this->unixtime = $time;
    }

}

?>

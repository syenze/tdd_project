<?php

require_once "LRUTime.php";

class LRUTimeTest extends PHPUnit_Framework_TestCase
{

    /**
     * コンストラクで生成すると 現状の時間を取得する。
     * @test
     **/
     function LRUTimeを生成するとgetTimeで現時点の時間を返す() {
        $time = new LRUTime();
        $this->assertEquals( mktime(), $time->getTime() );
     }

    /**
     * フォーマット指定で、現状の時間を取得する。
     * @test
     **/
     function フォーマットを指定して現時点の時間を返す() {
        $time = new LRUTime();
        $this->assertEquals( strftime("Yms",mktime() ), $time->getFmtTime("Yms") );
     }

    /**
     * コンストラクで生成すると 現状の時間を取得する。
     * @test
     **/
     function 時刻を設置すると管理時間が設定される(){
         $time = new LRUTime();
         sleep(0.5);
         $time->setTime( mktime() );
         $this->assertEquals( mktime(), $time->getTime() );
     }
}

?>

<?php

require_once "LRUCache.php";
# require_once "LRUTime.php";

class LRUCacheTest extends PHPUnit_Framework_TestCase
{
    /**
     * put()すると追加される
     * get()すると追加したものが取得できる
     *
     * @test
     */
    function putすると追加される() {
        $lru = new LRUCache();
        $lru->put(123, "hogehoge");
        $this->assertEquals("hogehoge", $lru->get(123));
    }

    /**
     * 次消す対象を取得する
     * @test
     */
    function 一番使われていないものが削除対象になる() {
        $lru = new LRUCache();
        $lru->put(1, "hoge");
        $lru->put(2, "moge");
        $lru->put(3, "fuga");

        $hoge = $lru->get(1);
        $hoge = $lru->get(2);

        $this->assertEquals(3, $lru->getOldest());
    }

    /**
     * 同じキーが二回 put されたときに、正常に確認する
     * @test
     */
    function 二回プットされたときに最新になる() {
        $lru = new LRUCache();
        $lru->put(1, "hoge");
        $lru->put(2, "moge");
        $lru->put(1, "hoge");

        $this->assertEquals(2, $lru->getOldest());
        $this->assertEquals("hoge", $lru->get(1));
        $this->assertEquals("moge", $lru->get(2));
    }

    /**
     * 存在しないkeyをgetするとnullが返ってくる
     * @test
     */
    function 存在しないkeyをgetするとnullが返ってくる() {
        $lru = new LRUCache();
        $this->assertEquals(null, $lru->get(1));
    }

    /**
     * リミットを超えたら、一番古いやつが消える。
     * @test
     **/
    function put_リミットを越えたら一番古いやつが消える(){
        $lru = new LRUCache(2);
        $lru->put(1, "hoge");
        $lru->put(2, "moge");
        $lru->put(3, "fuga");

        $this->assertEquals(null , $lru->get(1));
        $this->assertEquals("moge" , $lru->get(2));
        $this->assertEquals("fuga" , $lru->get(3));
    }

    /**
     * リミットに文字列をいれた場合
     * @test
     **/
//    function リミットが文字列でおかしくなる(){
//        $lru = new LRUCache( "gege" );
//        $this->assertEquals(null, $lru);
//    }

    /**
     * @test
     */
    function getLimitでlimitの値を取れること() {
        $lru = new LRUCache(5);
        $this->assertEquals(5, $lru->getLimit());
    }

    /**
     * @test
     */
    function changeLimitでlimitの値を変更できること() {
        $lru = new LRUCache(3);
        $lru->changeLimit(5);
        $this->assertEquals(5, $lru->getLimit());
    }

    /**
     * @test
     */
    function changeLimitでサイズを減らした時に、データが正しく帰ってくること() {
        $lru  = new LRUCache(5);
        $lru->put(1, 1);
        $lru->put(2, 2);
        $lru->put(3, 3);
        $lru->put(4, 4);
        $lru->put(5, 5);
        $lru->changeLimit(3);
        $this->assertNull($lru->get(1));
        $this->assertNull($lru->get(2));
        $this->assertEquals(3, $lru->get(3));
        $this->assertEquals(4, $lru->get(4));
        $this->assertEquals(5, $lru->get(5));
    }

    /**
     * @test
     */
    function 一定時間経ったデータを削除する()
    {

    }
}

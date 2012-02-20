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
        $lru->put(1, "gege");

        $this->assertEquals(2, $lru->getOldest());
        $this->assertEquals("gege", $lru->get(1));
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
    function キャッシュサイズに文字列を設定した場合、デフォルト値が設定されること(){
        $lru = new LRUCache( "gege" );
        $this->assertEquals(0, $lru->getCacheSize() );
        $lru->put(1, "hoge");
        $lru->put(2, "moge");
        $lru->put(3, "fuga");
        $this->assertEquals(2, $lru->getCacheSize() );
    }

    /**
     * @test
     */
    function getCacheSizeでlimitの値を取れること() {
        $lru = new LRUCache(5);
        $this->assertEquals(0, $lru->getCacheSize());
    }

    /**
     * @test
     */
    function キャッシュ保持数が変更できること() {
        $lru = new LRUCache(3);
        $lru->put(1, 'one');
        $lru->put(2, 'two');
        $lru->put(3, 'three');
        $lru->put(4, 'four');

        $this->assertNull($lru->get(1));

        $lru->setCacheSize(5);

        $lru->put(5, 'five');
        $lru->put(6, 'six');

        $this->assertEquals(5, $lru->getCacheSize());
        $this->assertEquals('five', $lru->get(5));
        $this->assertEquals('six', $lru->get(6));


    }

    /**
     * @test
     */
    function キャッシュのサイズを減らした時に、データが正しく帰ってくること() {
        $lru  = new LRUCache(5);
        $lru->put(1, 'one');
        $lru->put(2, 'two');
        $lru->put(3, 'three');
        $lru->put(4, 'four');
        $lru->put(5, 'five');

        $lru->setCacheSize( 3 );
        $this->assertNull($lru->get(1));
        $this->assertNull($lru->get(2));
        $this->assertEquals('three', $lru->get(3));
        $this->assertEquals('four', $lru->get(4));
        $this->assertEquals('five', $lru->get(5));
    }

    /**
     * @test
     */
    function 保持期限を過ぎたデータは消去される()
    {
        $lru  = new LRUCache(5);
        $lru->put( 1 , 'one' );

        $this->assertEquals('one', $lru->get(1));
        $lru->modCacheExpire( time() - 1000 );
        $this->assertNull($lru->get(1));

        $lru->put( 2 , 'two' );
        $this->assertEquals('two', $lru->get(2));

    }

    /**
     * @test
     */
    function 保持期限を過ぎていないデータは残される()
    {
        $lru  = new LRUCache(5);
        $lru->put( 1 , 'one', 100 );
        $lru->put( 2 , 'two', 2000 );

        $this->assertEquals('one', $lru->get(1));
        $this->assertEquals('two', $lru->get(2));
        $lru->modCacheExpire( time() - 200 );
        $this->assertNull($lru->get(1));
        $this->assertEquals('two', $lru->get(2));

    }

}

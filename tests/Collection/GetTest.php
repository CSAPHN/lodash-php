<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use function _\get;

class GetTest extends TestCase
{

    public function testGet()
    {
        $barney  = ['age' => 36, 'active' => true, 'likes' => ['bowling', 'fishing']];
        $fred    = ['age' => 40, 'false' => false, 'null' => null, 'zero' => 0, 'negative' => -1];
        $pebbles = [
            'age' => 1,
            'active' => true,
            'cart' => [['item' => 'item_1', 'qty' => 1]]
        ];

        $users = [
            'barney' => $barney,
            'fred' => $fred,
            'pebbles' => $pebbles,
        ];


        /**
         * Check empty paths
         */
        $this->assertSame($users, get($users, null));
        $this->assertSame($users, get($users, false));
        $this->assertSame($users, get($users, ""));


        /**
         * Check basic use cases
         */
        $this->assertSame($pebbles, get($users, 'pebbles'));
        $this->assertSame($pebbles, get($users, ['pebbles']));
        $this->assertSame(36, get($users, 'barney.age'));
        $this->assertSame(['bowling', 'fishing'], get($users, 'barney.likes'));
        $this->assertSame('bowling', get($users, 'barney.likes[0]'));
        $this->assertSame('item_1', get($users, 'pebbles.cart[0].item'));
        $this->assertSame('item_1', get($users, 'pebbles.{cart}[0].item'));
        $this->assertSame('item_1', get($users, ['pebbles', 'cart', 0, 'item']));

        /**
         * Check property not found behavior
         */
        $this->assertSame(null, get($users, 'george'));
        $this->assertSame(null, get($users, 'george'), null);
        $this->assertSame('default-value',
            get($users, 'george', 'default-value'));
        $this->assertSame('default-value',
            get($users, 'george',
                function() {
                return 'default-value';
            }));


        /**
         * Check what happens when found property is false / null / empty
         */
        $this->assertSame(false, get($users, 'fred.false'), 'default-value');
        $this->assertSame(null, get($users, 'fred.null'), 'default-value');
        $this->assertSame(0, get($users, 'fred.zero'), 'default-value');
        $this->assertSame(-1, get($users, 'fred.negative'), 'default-value');
    }
}
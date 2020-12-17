<?php

namespace UnitTests\Euroauto\Delivery\Type;

use Builder\Euroauto\Product;

use Euroauto\Delivery\Type;
use Euroauto\Delivery\Type\PickupPoint;
use Euroauto\Product\Base;

use PHPUnit\Framework\TestCase;

class PickupPointTest extends TestCase
{
    /**
     * При пустых продуктах должно вернуться false
     */
    public function testEmptyProducts()
    {
        /** @var PickupPoint $deliveryType */
        $deliveryType = Type::factory('PickupPoint');

        $products = new Base\Collection();
        $productCounts = [];

        $result = $deliveryType->isAvailable($products, $productCounts);

        $this->assertFalse($result);
    }

    /**
     * При продуктах в наличии и заказных новых не должно возвращаться false
     */
    public function testEANewProducts()
    {
        /** @var PickupPoint $deliveryType */
        $deliveryType = Type::factory('PickupPoint');

        $products = new Base\Collection();
        $productCounts = [];

        $products->add(Product\BaseBuilder::factoryEANew()->build());
        $products->add(Product\BaseBuilder::factoryEAUsed()->build()->add_department_count(rand(1, 99), rand(1, 99)));
        $products->add(Product\BaseBuilder::factoryOrderableUnique()->build());

        $result = $deliveryType->isAvailable($products, $productCounts);

        $this->assertNotFalse($result);
    }

    /**
     * На заказной бухе должно быть false
     */
    public function testOrderableUsedProduct()
    {
        /** @var PickupPoint $deliveryType */
        $deliveryType = Type::factory('PickupPoint');

        $products = new Base\Collection();
        $productCounts = [];

        $products->add(Product\BaseBuilder::factoryOrderableUnique()->build()->set_condition_name('used'));

        $result = $deliveryType->isAvailable($products, $productCounts);

        $this->assertFalse($result);
    }
}
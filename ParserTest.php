<?php

use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testCreateProductObject()
    {
        $make = 'Apple';
        $model = 'iPhone 11';
        $colour = 'Black';
        $capacity = '256GB';
        $network = 'Unlocked';
        $grade = 'Grade A';
        $condition = 'Working';

        $product = new Product($make, $model, $colour, $capacity, $network, $grade, $condition);

        $this->assertEquals($make, $product->make);
        $this->assertEquals($model, $product->model);
        $this->assertEquals($colour, $product->colour);
        $this->assertEquals($capacity, $product->capacity);
        $this->assertEquals($network, $product->network);
        $this->assertEquals($grade, $product->grade);
        $this->assertEquals($condition, $product->condition);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Required field 'make' not found in file.
     */
    public function testCreateProductObjectMissingMake()
    {
        $model = 'iPhone 11';
        new Product(null, $model, null, null, null, null, null);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Required field 'model' not found in file.
     */
    public function testCreateProductObjectMissingModel()
    {
        $make = 'Apple';
        new Product($make, null, null, null, null, null, null);
    }
}

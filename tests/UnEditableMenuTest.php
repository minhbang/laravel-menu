<?php

/**
 * @author: Minh Bang <contact@minhbang.com>
 */
class UnEditableMenuTest extends TestCase
{
    /**
     * @var \Minhbang\Menu\Roots\UneditableRoot;
     */
    protected $root;

    public function setUp()
    {
        parent::setUp();
        $this->root = new \Minhbang\Menu\Roots\UneditableRoot('test', \Minhbang\Menu\Presenters\Metis::class, []);
    }

    public function testAddItem()
    {
        $this->root->addItem('group1', ['label' => 'Label1', 'ison' => 'fa-file']);
        $this->assertTrue(array_get($this->root->items(), 'group1.label') == 'Label1');

        $this->root->addItem('group1.item1', ['label' => 'Label1.1', 'ison' => 'fa-file']);
        $this->assertTrue(array_get($this->root->items(), 'group1.items.item1.label') == 'Label1.1');

        $this->root->addItem('group2', ['label' => 'Label2', 'ison' => 'fa-file']);
        $this->assertTrue(array_has($this->root->items(), 'group2'));

        $this->root->addItem('group2.item1.item1', ['label' => 'Label2.1.1', 'ison' => 'fa-file']);
        $this->assertFalse(array_has($this->root->items(), 'group2.items.item1.items.item1'));

        $this->root->addItem('group1.item1.item1', ['label' => 'Label1.1.1', 'ison' => 'fa-file']);
        $this->assertTrue(array_has($this->root->items(), 'group1.items.item1.items.item1'));
    }
}
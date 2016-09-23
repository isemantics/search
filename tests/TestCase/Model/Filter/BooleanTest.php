<?php
namespace Search\Test\TestCase\Model\Filter;

use Cake\Core\Configure;
use Cake\ORM\Entity;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Utility\Hash;
use Search\Manager;
use Search\Model\Filter\Base;
use Search\Model\Filter\Boolean;

class BooleanTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.Search.Articles'
    ];

    public function testProcessWithFlagOn()
    {
        $articles = TableRegistry::get('Articles');
        $manager = new Manager($articles);
        $filter = new Boolean('is_active', $manager);
        $filter->args(['is_active' => 'on']);
        $filter->query($articles->find());
        $filter->process();

        $this->assertRegExp(
            '/WHERE Articles\.is_active = \:c0$/',
            $filter->query()->sql()
        );
        $this->assertEquals(
            [true],
            Hash::extract($filter->query()->valueBinder()->bindings(), '{s}.value')
        );
    }

    public function testProcessWithFlagOff()
    {
        $articles = TableRegistry::get('Articles');
        $manager = new Manager($articles);
        $filter = new Boolean('is_active', $manager);
        $filter->args(['is_active' => 'off']);
        $filter->query($articles->find());
        $filter->process();

        $this->assertRegExp(
            '/WHERE Articles\.is_active = \:c0$/',
            $filter->query()->sql()
        );
        $this->assertEquals(
            [false],
            Hash::extract($filter->query()->valueBinder()->bindings(), '{s}.value')
        );
    }

    public function testProcessWithStringFlagTrue()
    {
        $articles = TableRegistry::get('Articles');
        $manager = new Manager($articles);
        $filter = new Boolean('is_active', $manager);
        $filter->args(['is_active' => 'true']);
        $filter->query($articles->find());
        $filter->process();

        $this->assertRegExp(
            '/WHERE Articles\.is_active = \:c0$/',
            $filter->query()->sql()
        );
        $this->assertEquals(
            [true],
            Hash::extract($filter->query()->valueBinder()->bindings(), '{s}.value')
        );
    }

    public function testProcessWithStringFlagFalse()
    {
        $articles = TableRegistry::get('Articles');
        $manager = new Manager($articles);
        $filter = new Boolean('is_active', $manager);
        $filter->args(['is_active' => 'false']);
        $filter->query($articles->find());
        $filter->process();

        $this->assertRegExp(
            '/WHERE Articles\.is_active = \:c0$/',
            $filter->query()->sql()
        );
        $this->assertEquals(
            [false],
            Hash::extract($filter->query()->valueBinder()->bindings(), '{s}.value')
        );
    }

    public function testProcessWithBooleanFlagTrue()
    {
        $articles = TableRegistry::get('Articles');
        $manager = new Manager($articles);
        $filter = new Boolean('is_active', $manager);
        $filter->args(['is_active' => true]);
        $filter->query($articles->find());
        $filter->process();

        $this->assertRegExp(
            '/WHERE Articles\.is_active = \:c0$/',
            $filter->query()->sql()
        );
        $this->assertEquals(
            [true],
            Hash::extract($filter->query()->valueBinder()->bindings(), '{s}.value')
        );
    }

    public function testProcessWithBooleanFlagFalse()
    {
        $articles = TableRegistry::get('Articles');
        $manager = new Manager($articles);
        $filter = new Boolean('is_active', $manager);
        $filter->args(['is_active' => false]);
        $filter->query($articles->find());
        $filter->process();

        $this->assertRegExp(
            '/WHERE Articles\.is_active = \:c0$/',
            $filter->query()->sql()
        );
        $this->assertEquals(
            [false],
            Hash::extract($filter->query()->valueBinder()->bindings(), '{s}.value')
        );
    }

    public function testProcessWithStringFlag1()
    {
        $articles = TableRegistry::get('Articles');
        $manager = new Manager($articles);
        $filter = new Boolean('is_active', $manager);
        $filter->args(['is_active' => '1']);
        $filter->query($articles->find());
        $filter->process();

        $this->assertRegExp(
            '/WHERE Articles\.is_active = \:c0$/',
            $filter->query()->sql()
        );
        $this->assertEquals(
            [true],
            Hash::extract($filter->query()->valueBinder()->bindings(), '{s}.value')
        );
    }

    public function testProcessWithStringFlag0()
    {
        $articles = TableRegistry::get('Articles');
        $manager = new Manager($articles);
        $filter = new Boolean('is_active', $manager);
        $filter->args(['is_active' => '0']);
        $filter->query($articles->find());
        $filter->process();

        $this->assertRegExp(
            '/WHERE Articles\.is_active = \:c0$/',
            $filter->query()->sql()
        );
        $this->assertEquals(
            [false],
            Hash::extract($filter->query()->valueBinder()->bindings(), '{s}.value')
        );
    }

    public function testProcessWithIntegerFlag1()
    {
        $articles = TableRegistry::get('Articles');
        $manager = new Manager($articles);
        $filter = new Boolean('is_active', $manager);
        $filter->args(['is_active' => 1]);
        $filter->query($articles->find());
        $filter->process();

        $this->assertRegExp(
            '/WHERE Articles\.is_active = \:c0$/',
            $filter->query()->sql()
        );
        $this->assertEquals(
            [true],
            Hash::extract($filter->query()->valueBinder()->bindings(), '{s}.value')
        );
    }

    public function testProcessWithIntegerFlag0()
    {
        $articles = TableRegistry::get('Articles');
        $manager = new Manager($articles);
        $filter = new Boolean('is_active', $manager);
        $filter->args(['is_active' => 0]);
        $filter->query($articles->find());
        $filter->process();

        $this->assertRegExp(
            '/WHERE Articles\.is_active = \:c0$/',
            $filter->query()->sql()
        );
        $this->assertEquals(
            [false],
            Hash::extract($filter->query()->valueBinder()->bindings(), '{s}.value')
        );
    }

    public function testProcessWithFlagInvalid()
    {
        $articles = TableRegistry::get('Articles');
        $manager = new Manager($articles);
        $filter = new Boolean('is_active', $manager);
        $filter->args(['is_active' => 'neitherTruthyNorFalsy']);
        $filter->query($articles->find());
        $filter->process();

        $this->assertEmpty($filter->query()->clause('where'));
        $filter->query()->sql();
        $this->assertEmpty($filter->query()->valueBinder()->bindings());
    }

    /**
     * @return void
     */
    public function testProcessMultiValueSafe()
    {
        $articles = TableRegistry::get('Articles');
        $manager = new Manager($articles);
        $filter = new Boolean('is_active', $manager, ['multiValue' => true]);
        $filter->args(['is_active' => [0, 1]]);
        $filter->query($articles->find());
        $filter->process();

        $this->assertEmpty($filter->query()->clause('where'));
        $filter->query()->sql();
        $this->assertEmpty($filter->query()->valueBinder()->bindings());
    }

    /**
     * @return void
     */
    public function testProcessDefaultFallbackForDisallowedMultiValue()
    {
        $articles = TableRegistry::get('Articles');
        $manager = new Manager($articles);
        $filter = new Boolean('is_active', $manager, ['defaultValue' => true]);
        $filter->args(['is_active' => ['foo', 'bar']]);
        $filter->query($articles->find());
        $filter->process();

        $this->assertRegExp(
            '/WHERE Articles\.is_active = :c0$/',
            $filter->query()->sql()
        );
        $this->assertEquals(
            [true],
            Hash::extract($filter->query()->valueBinder()->bindings(), '{s}.value')
        );
    }

    /**
     * @return void
     */
    public function testProcessNoDefaultFallbackForDisallowedMultiValue()
    {
        $articles = TableRegistry::get('Articles');
        $manager = new Manager($articles);
        $filter = new Boolean('is_active', $manager);
        $filter->args(['is_active' => ['foo', 'bar']]);
        $filter->query($articles->find());
        $filter->process();

        $this->assertEmpty($filter->query()->clause('where'));
        $filter->query()->sql();
        $this->assertEmpty($filter->query()->valueBinder()->bindings());
    }
}

<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Patterns\Behavioral\AuthMiddleware;
use Patterns\Behavioral\ChainOfResponsibility;
use Patterns\Behavioral\Command;
use Patterns\Behavioral\Observer;
use Patterns\Behavioral\State;
use Patterns\Behavioral\Strategy;
use Patterns\Behavioral\MovingAverageStrategy;
use Patterns\Behavioral\LinearTrendRegressionStrategy;
use Patterns\Behavioral\PredictionContext;
use Patterns\Creational\Builder;
use Patterns\Creational\Factory;
use Patterns\Creational\EmergencyOrder;
use Patterns\Creational\RoutineRestockOrder;
use Patterns\Creational\DepartmentDispenseOrder;
use Patterns\Creational\Prototype;
use Patterns\Creational\Singleton;
use Patterns\Structural\Adapter;
use Patterns\Structural\Decorator;
use Patterns\Structural\Proxy;

final class PatternsTest extends TestCase
{
    public function testSingletonReturnsSameInstance(): void
    {
        $a = Singleton::getInstance();
        $b = Singleton::getInstance();

        $this->assertSame($a, $b);
    }

    public function testFactoryCreatesRealOrderTypes(): void
    {
        $this->assertInstanceOf(RoutineRestockOrder::class, Factory::createOrder('routine'));
        $this->assertInstanceOf(EmergencyOrder::class, Factory::createOrder('emergency'));
        $this->assertInstanceOf(DepartmentDispenseOrder::class, Factory::createOrder('department'));
    }

    public function testFactoryDefaultIsRoutine(): void
    {
        $order = Factory::createOrder('unknown');
        $this->assertInstanceOf(RoutineRestockOrder::class, $order);
    }

    public function testBuilderBuildsRealOrderStructure(): void
    {
        $order = (new Builder())
            ->setUserId(12)
            ->addItem(7, 2, 15.5)
            ->addItem(8, 1, 20.0)
            ->build();

        $this->assertSame(12, $order['user_id']);
        $this->assertCount(2, $order['items']);
        $this->assertSame(7, $order['items'][0]['medicine_id']);
        $this->assertSame(2, $order['items'][0]['quantity']);
        $this->assertSame(15.5, $order['items'][0]['unit_price']);
    }

    public function testPrototypeClonesIndependently(): void
    {
        $original = new Prototype('Medicine A', 10.0, 'Painkiller');
        $clone = $original->cloneItem();

        $this->assertNotSame($original, $clone);
        $this->assertSame($original->name, $clone->name);
        $clone->price = 20.0;
        $this->assertSame(10.0, $original->price);
        $this->assertSame(20.0, $clone->price);
    }

    public function testCommandReturnsExecutedInventoryCommand(): void
    {
        $result = (new Command('increase_stock', 4, 10))->execute();

        $this->assertSame('increase_stock', $result['action']);
        $this->assertSame(4, $result['medicine_id']);
        $this->assertSame(10, $result['quantity']);
        $this->assertSame('EXECUTED', $result['status']);
    }

    public function testChainOfResponsibilityRejectsMissingToken(): void
    {
        $chain = new ChainOfResponsibility(new AuthMiddleware());
        $this->assertFalse($chain->process([]));
        $this->assertTrue($chain->process(['token' => 'x']));
    }

    public function testStateUsesRealAllowedTransitions(): void
    {
        $state = new State('pending');
        $state->transitionTo('processing');
        $this->assertSame('processing', $state->getState());

        $state->transitionTo('completed');
        $this->assertSame('completed', $state->getState());

        // completed cannot directly move to pending in the real implementation.
        $state->transitionTo('pending');
        $this->assertSame('completed', $state->getState());

        $state->transitionTo('refunded');
        $this->assertSame('refunded', $state->getState());
    }

    public function testObserverThresholdDoesNotFail(): void
    {
        $subject = Observer::createStockNotifier();

        $this->assertFalse($subject->checkStockThreshold('Medicine A', 20, 10));
        $this->assertTrue($subject->checkStockThreshold('Medicine A', 10, 10));
        $this->assertTrue($subject->checkStockThreshold('Medicine A', 5, 10));
    }

    public function testStrategyFactoryReturnsRealStrategies(): void
    {
        $moving = Strategy::getStrategy('moving_average');
        $trend = Strategy::getStrategy('trend');

        $this->assertInstanceOf(MovingAverageStrategy::class, $moving);
        $this->assertInstanceOf(LinearTrendRegressionStrategy::class, $trend);
    }

    public function testMovingAverageStrategyCalculation(): void
    {
        $result = (new MovingAverageStrategy())->predict(10, 2.0);

        $this->assertSame(5.0, $result['days_remaining']);
        $this->assertSame(50, $result['recommended_purchase_quantity']);
        $this->assertSame('حرج (Critical)', $result['risk_level']);
    }

    public function testTrendStrategyCalculation(): void
    {
        $result = (new LinearTrendRegressionStrategy())->predict(10, 2.0);

        $this->assertEqualsWithDelta(4.3, $result['days_remaining'], 0.1);
        $this->assertSame(59, $result['recommended_purchase_quantity']);
    }

    public function testPredictionContextCanSwitchStrategy(): void
    {
        $context = new PredictionContext(new MovingAverageStrategy());
        $first = $context->executePrediction(30, 2.0);

        $context->setStrategy(new LinearTrendRegressionStrategy());
        $second = $context->executePrediction(30, 2.0);

        $this->assertNotSame($first['strategy'], $second['strategy']);
    }

    public function testAdapterConvertsXmlToArrayWhenSimpleXmlIsAvailable(): void
    {
        if (!function_exists('simplexml_load_string')) {
            $this->markTestSkipped('SimpleXML extension is not enabled in the PHP test environment.');
        }

        $result = Adapter::xmlToJsonArray('<medicine><name>Panadol</name><price>15</price></medicine>');

        $this->assertSame('Panadol', $result['name']);
        $this->assertSame('15', $result['price']);
    }

    public function testDecoratorDiscountMatchesRealImplementation(): void
    {
        $this->assertSame(80.0, Decorator::applyDiscount(100.0, 20.0));
        $this->assertSame(100.0, Decorator::applyDiscount(100.0, 0.0));
    }

    public function testProxyRejectsMissingOrInvalidToken(): void
    {
        $this->assertFalse(Proxy::checkAccess(null, ['manager']));
        $this->assertFalse(Proxy::checkAccess('invalid-token', ['manager']));
    }

    public function testOrderFactoryProcessContract(): void
    {
        $result = Factory::createOrder('emergency')->processOrder(9, [
            ['medicine_id' => 1, 'quantity' => 2]
        ]);

        $this->assertSame('emergency_order', $result['type']);
        $this->assertSame(9, $result['user_id']);
        $this->assertSame(1, $result['items_count']);
    }
}

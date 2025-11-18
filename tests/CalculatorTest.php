<?php
use PHPUnit\Framework\TestCase;
use App\Calculator;

class CalculatorTest extends TestCase {
    private Calculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new Calculator();
    }

    public function testAdd() {
        $this->assertEquals(4, $this->calculator->add(2, 2));
        $this->assertEquals(0, $this->calculator->add(-2, 2));
    }

    public function testSubtract() {
        $this->assertEquals(2, $this->calculator->subtract(5, 3));
        $this->assertEquals(-5, $this->calculator->subtract(0, 5));
    }

    public function testMultiply() {
        $this->assertEquals(10, $this->calculator->multiply(2, 5));
        $this->assertEquals(0, $this->calculator->multiply(10, 0));
    }

    public function testDivide() {
        $this->assertEquals(2.5, $this->calculator->divide(5, 2));
        $this->assertEquals(5, $this->calculator->divide(10, 2));
    }

    public function testDivideByZeroThrowsException() {
        $this->expectException(\InvalidArgumentException::class);
        $this->calculator->divide(10, 0);
    }

    // --- Intentionally failing tests ---

    public function testFailingAdd() {
        $this->assertEquals(10, $this->calculator->add(2, 2), "This test is designed to fail.");
    }

    public function testFailingSubtract() {
        $this->assertEquals(1, $this->calculator->subtract(5, 3), "This test is also designed to fail.");
    }
}

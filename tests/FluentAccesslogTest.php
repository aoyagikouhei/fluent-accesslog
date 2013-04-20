<?php
class FluentAccesslogTest extends PHPUnit_Framework_TestCase
{
    public function testDefault()
    {
        $callable = function($logger, $entity, $error) {
            $this->assertEquals('accesslog', $entity->getTag());
            $data = $entity->getData();
            $this->assertEquals(true, array_key_exists('r', $data));
        };
        $logger = new \Fluent\Accesslog(array('error_handler' => $callable));
        $logger->add();
    }

    public function testTag()
    {
        $callable = function($logger, $entity, $error) {
            $this->assertEquals('xxx', $entity->getTag());
        };
        $logger = new \Fluent\Accesslog(array(
            'error_handler' => $callable,
            'tag' => 'xxx'
        ));
        $logger->add();
    }

    public function testTagWithDate()
    {
        $callable = function($logger, $entity, $error) {
            $ts = new \DateTime();
            $this->assertEquals('accesslog' . $ts->format('Ym'), $entity->getTag());
        };
        $logger = new \Fluent\Accesslog(array(
            'error_handler' => $callable,
            'tag_with_date' => 'Ym'
        ));
        $logger->add();
    }

    public function testRequestKey()
    {
        $callable = function($logger, $entity, $error) {
            $ts = new \DateTime();
            $data = $entity->getData();
            $this->assertEquals(true, array_key_exists('x', $data));
        };
        $logger = new \Fluent\Accesslog(array(
            'error_handler' => $callable,
            'request_key' => 'x'
        ));
        $logger->add();
    }

    public function testServer()
    {
        $callable = function($logger, $entity, $error) {
            $data = $entity->getData();
            $this->assertEquals(true, 10 < $data['time']);
        };
        $logger = new \Fluent\Accesslog(array(
            'error_handler' => $callable,
            'server' => array('REQUEST_TIME' => 'time')
        ));
        $logger->add();
    }

    public function testOption()
    {
        $callable = function($logger, $entity, $error) {
            $data = $entity->getData();
            $this->assertEquals('y', $data['x']);
            $this->assertEquals('w', $data['z']);
        };
        $logger = new \Fluent\Accesslog(array('error_handler' => $callable));
        $logger->add(array('x' => 'y', 'z' => 'w'));
    }

    public function testFix()
    {
        $callable = function($logger, $entity, $error) {
            $ts = new \DateTime();
            $this->assertEquals('abc_accesslog_efg', $entity->getTag());
        };
        $logger = new \Fluent\Accesslog(array('error_handler' => $callable));
        $logger->add(null, 'abc_', '_efg');
    }
}

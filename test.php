<?php


trait itemA{
    use itemB;

    public function testA()
    {
        $this->set('aaa');
    }
}

trait itemB{
    private $item = null;

    public function set($item)
    {
        $this->item = $item;
    }
}

class items{
    use itemB;

    public function testB()
    {
        $this->set('bbb');
    }

    public function build()
    {
        return $this->item;
    }
}

class run{
    use itemA;

    public function test()
    {
        $this->testA();

        $class = new items();
        //$class->testB();

        if(!empty($class->build())){
            $this->item = $class->build();
        }

        print($this->item);
    }
}

$test = new run();
$test->test();

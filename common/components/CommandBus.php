<?php

namespace common\components;

class CommandBus
{
    /**
     * @param $command
     * @return object
     */
    public function dispatch($command)
    {
        $handlerClass = $this->getHandler($command);

        return \Yii::$container->get($handlerClass)->handle($command);
    }

    /**
     * @param $command
     * @return string
     */
    private function getHandler($command)
    {
        return str_replace(['Command', 'commands'], ['Handler', 'commands\handlers'], get_class($command));
    }
}
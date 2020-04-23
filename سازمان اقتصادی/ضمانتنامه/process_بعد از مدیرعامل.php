<?php


class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        $newRefer = $execution->getVariable('signRefer');
        if (intval($newRefer)) {
            Letter::Sign($newRefer);
        }
    }
}

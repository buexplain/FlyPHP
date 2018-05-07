<?php namespace app\console\commands;

use fly\contracts\console\Command;


class Example extends Command
{
    const NAME = 'example';
    const DESCRIPTION = 'example command.';

    public function run()
    {
        $this->response->info(100);
        return $this->response->error($this->request->all());
    }
}
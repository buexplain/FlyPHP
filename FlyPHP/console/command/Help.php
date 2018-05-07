<?php namespace fly\console\command;

use fly\contracts\console\Command;
use Exception;

class Help extends Command
{
    const NAME = 'help';
    const DESCRIPTION = 'help notes.';

    public function run()
    {
        $router = $this->app->get(\fly\contracts\console\Router::class);

        foreach($router->getCommands() as $commandName) {
            try {
                $route = $router->match($commandName, $router::METHOD);
                if (is_null($route)) {
                    throw new Exception('Command "' . $commandName . '" is not defined.');
                }
                $commandClass = $route->getController();
                $commandController = $this->app->get($commandClass);
                $this->response->info($commandController->help().PHP_EOL);
            }catch (Exception $e) {
                $this->response->error($e->getMessage());
            }
        }

        return $this->response;
    }
}
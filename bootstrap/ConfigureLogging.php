<?php namespace Bootstrap;

use Monolog\Logger as Monolog;
use Monolog\Formatter\LineFormatter;
use Illuminate\Log\Writer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Bootstrap\ConfigureLogging as BaseConfigureLogging;
use Monolog\Handler\StreamHandler;


class ConfigureLogging extends BaseConfigureLogging
{
    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Log\Writer  $log
     * @return void
     */
    protected function configureSingleHandler(Application $app, Writer $log)
    {
        // Stream handlers
        $logPath = '/Applications/MAMP/htdocs/vp2/DatLT/2015_edu01_ems/src/bootstrap/app_datlt.log';
        $logLevel = Monolog::DEBUG;
        $logStreamHandler = new StreamHandler($logPath, $logLevel);

        // Formatting
        // the default output format is "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
        $logFormat = "%datetime% [%level_name%] (%channel%): %message%\n";
        $formatter = new LineFormatter($logFormat);
        $logStreamHandler->setFormatter($formatter);

        // push handlers
        $logger = $log->getMonolog();
        $logger->pushHandler($logStreamHandler);
    }
}
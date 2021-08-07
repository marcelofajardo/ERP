<?php

declare(strict_types=1);
namespace App\Console\Commands\Bots;


use Closure;
use Laravel\Dusk\Chrome\SupportsChrome;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use NunoMaduro\LaravelConsoleDusk\Contracts\Drivers\DriverContract;

class Chrome implements DriverContract
{

    use SupportsChrome;

    private $proxy;

    /**
     * Chrome constructor.
     * @param $proxy
     */
    public function __construct($proxy)
    {
        $this->proxy = $proxy;
    }

    public function open(): void
    {
        static::startChromeDriver();
    }

    public function close(): void
    {
        static::stopChromeDriver();
    }

    public static function afterClass(Closure $callback): void
    {
        // ..
    }

    public function getDriver()
    {
        $options = (new ChromeOptions)->addArguments(
            [
                '--disable-gpu',
//                '--headless',
                '--enable-file-cookies',
//                '--no-sandbox'
            ]
        );

        $proxy = $this->proxy;
        if (null !== $proxy) {

            $options->addArguments([
                '--proxy-server=' . $proxy['ip'] . ':' . $proxy['port']
            ]);
        }

        return RemoteWebDriver::create(
            'http://localhost:9515',
            DesiredCapabilities::chrome()
                ->setCapability(
                    ChromeOptions::CAPABILITY,
                    $options
                )
        );
    }

    public function __destruct()
    {
        $this->close();
    }
}

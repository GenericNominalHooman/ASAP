<?php

namespace Tests;

use Facebook\WebDriver\Firefox\FirefoxOptions;
use Facebook\WebDriver\Firefox\FirefoxProfile;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Collection;
use Laravel\Dusk\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\BeforeClass;

abstract class DuskTestCase extends BaseTestCase
{
    /**
     * The WebDriver instance.
     */
    protected static $driver = null;


    /**
     * Prepare for Dusk test execution.
     */
    #[BeforeClass]
    public static function prepare(): void
    {
        if (!static::runningInSail()) {
            static::startChromeDriver(['--port=9515']);
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    // Chrome
    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions)->addArguments(collect([
            $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
            '--disable-search-engine-choice-screen',
            '--disable-smooth-scrolling',
        ])->unless($this->hasHeadlessDisabled(), function (Collection $items) {
            return $items->merge([
                '--disable-gpu',
                '--headless=new',
            ]);
        })->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? env('DUSK_DRIVER_URL') ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY,
                $options
            )->setCapability('goog:loggingPrefs', ['browser' => 'ALL'])
        );
    }

    // // Firefox - selenium
    // protected function driver()
    // {
    //     $options = (new FirefoxOptions)->addArguments([
    //         '--headless',
    //         '--disable-gpu',
    //         '--window-size=1920,1080',
    //     ]);

    //     return RemoteWebDriver::create(
    //         'http://localhost:4444/wd/hub',
    //         DesiredCapabilities::firefox()
    //             ->setCapability(FirefoxOptions::CAPABILITY, $options)
    //     );
    // }

    //     protected function driver() - Gecko direct v1
// {
//     $options = (new FirefoxOptions)->addArguments([
//         '--headless',
//         '--disable-gpu',
//         '--window-size=1920,1080',
//     ]);

    //     // Use geckodriver directly on port 9515 (default for geckodriver)
//     return RemoteWebDriver::create(
//         'http://localhost:9515', // Default geckodriver port
//         DesiredCapabilities::firefox()
//             ->setCapability(FirefoxOptions::CAPABILITY, $options)
//     );
// }

    /*
    protected function driver()
    {
        $options = (new FirefoxOptions)->addArguments([
            '--headless',
            '--disable-gpu',
            '--window-size=1920,1080',
        ]);

        // Ensure any existing session is properly closed
        if (self::$driver !== null) {
            try {
                self::$driver->quit();
            } catch (\Exception $e) {
                // Log error if needed
            }
            self::$driver = null;
        }

        // Create new session
        self::$driver = RemoteWebDriver::create(
            'http://localhost:9515',
            DesiredCapabilities::firefox()
                ->setCapability(FirefoxOptions::CAPABILITY, $options)
        );

        return self::$driver;
    }
    */

    /**
     * Quit the browser instance.
     */
    public function quit()
    {
        if (self::$driver !== null) {
            try {
                self::$driver->quit();
            } catch (\Exception $e) {
                if (function_exists('info')) {
                    info('Error quitting WebDriver: ' . $e->getMessage());
                }
            } finally {
                self::$driver = null;
            }
        }
    }

    // Add this method to properly close the browser when done
    public static function tearDownAfterClass(): void
    {
        static::closeAll();
        parent::tearDownAfterClass();
    }
}
<?php

namespace ThinFrame\Karma\Command\Assets;

use ThinFrame\CommandLine\Command\AbstractCommand;
use ThinFrame\CommandLine\IO\InputDriverInterface;
use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Events\DispatcherAwareTrait;
use ThinFrame\Events\SimpleEvent;
use ThinFrame\Karma\Events;
use ThinFrame\Karma\Manager\AssetsManager;

/**
 * Class Install
 * @package ThinFrame\Karma\Command\Assets
 * @since   0.3
 */
class Install extends AbstractCommand
{
    use DispatcherAwareTrait;

    /**
     * @var AssetsManager
     */
    private $assetsManager;

    /**
     * @var string
     */
    private $projectRoot;

    /**
     * @var string
     */
    private $assetsRoot;

    /**
     * Constructor
     *
     * @param AssetsManager $assetsManager
     * @param string        $projectRoot
     * @param string        $assetsRoot
     */
    public function __construct(AssetsManager $assetsManager, $projectRoot, $assetsRoot)
    {
        $this->assetsManager = $assetsManager;
        $this->projectRoot   = realpath($projectRoot);
        $this->assetsRoot    = str_replace($this->projectRoot, '', realpath($assetsRoot));
    }

    /**
     * Get command argument
     *
     * @return string
     */
    public function getArgument()
    {
        return 'install';
    }

    /**
     * Get command descriptions
     *
     * @return array
     */
    public function getDescriptions()
    {
        return [
            'assets install' => 'Install assets'
        ];
    }

    /**
     * Code that will be executed when command is triggered
     *
     * @param InputDriverInterface  $inputDriver
     * @param OutputDriverInterface $outputDriver
     *
     * @return bool
     */
    public function execute(InputDriverInterface $inputDriver, OutputDriverInterface $outputDriver)
    {
        $outputDriver->writeLine('[info]Discovering assets ... [/info]');
        $this->dispatcher->trigger(new SimpleEvent(Events::ASSETS_MAP));

        $outputDriver->writeLine('[info]Installing assets ... [/info]');
        foreach ($this->assetsManager->getAssets() as $rootName => $assets) {
            if (is_null($this->assetsManager->getRoot($rootName))) {
                continue;
            }
            //TODO: empty assets dir first
            $target = $this->projectRoot . $this->assetsRoot . DIRECTORY_SEPARATOR . $rootName;
            if (!is_dir($target)) {
                mkdir($target, 0777, true);
            }
            $outputDriver->writeLine('');
            foreach ($assets as $asset) {
                $assetRoot = dirname($asset);
                if (!is_dir($target . DIRECTORY_SEPARATOR . $assetRoot)) {
                    mkdir($target . DIRECTORY_SEPARATOR . $assetRoot, 0777, true);
                }
                $outputDriver->writeLine(
                    " => Installing [format effects='bold']" . $this->assetsManager->getRoot(
                        $rootName
                    ) . $asset . '[/format]'
                );
                copy(
                    $this->projectRoot . $this->assetsManager->getRoot($rootName) . $asset,
                    $target . DIRECTORY_SEPARATOR . $asset
                );

            }
        }
        //TODO: trigger post install event
        $outputDriver->writeLine('');
        $outputDriver->writeLine('[success]Done[/success]');

        return true;
    }
}

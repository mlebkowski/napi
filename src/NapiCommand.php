<?php

namespace Nassau\Napi;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class NapiCommand extends Command
{
    const NAME = 'napi';

    const ARGUMENT_FILE = 'file';
    const OPTION_LANGUAGE = 'language';
    const OPTION_FPS = 'fps';

    /**
     * @var NapiService
     */
    private $napiService;

    /**
     * NapiCommand constructor.
     * @param NapiService $napiService
     */
    public function __construct(NapiService $napiService)
    {
        parent::__construct();
        $this->napiService = $napiService;
    }


    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName(self::NAME);

        $this->addOption(self::OPTION_LANGUAGE, substr(self::OPTION_LANGUAGE, 0, 1), InputOption::VALUE_REQUIRED, "", "pl");
        $this->addOption(self::OPTION_FPS, null, InputOption::VALUE_REQUIRED, "", 23.976);

        $this->addArgument(self::ARGUMENT_FILE, InputArgument::IS_ARRAY | InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = $input->getArgument(self::ARGUMENT_FILE);
        $language = $input->getOption(self::OPTION_LANGUAGE);
        $defaultFps = floatval($input->getOption(self::OPTION_FPS));

        foreach ($files as $file) {
            $file = new \SplFileInfo($file);

            $output->write(sprintf('Downloading subtitles for <comment>%s</comment>... ', $file->getFilename()));

            if (false === $file->isReadable() || false === $file->isFile()) {
                $output->writeln('<error>file is not readable</error>');

                continue;
            }

            $result = $this->napiService->getSubtitles($file, $language, $defaultFps);

            if ($result) {
                $output->writeln('<question>Success</question>');
            } else {
                $output->writeln('<error>No subtitles found</error>');
            }
        }
    }



}
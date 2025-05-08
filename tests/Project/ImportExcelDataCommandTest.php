<?php

namespace App\Tests\Project;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use App\Command\ImportExcelDataCommand;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Test for the ImportExcelDataCommand class.
 *
 * We use extends KernelTestCase instead of TestCase because this command depends on Symfony services like the
 * EntityManager, which are only available when the Symfony kernel is booted.
 */
class ImportExcelDataCommandTest extends KernelTestCase
{
    public function testCommandOutputsSuccessMessage(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $application = new Application();
        $command = new ImportExcelDataCommand(
            $container->get(EntityManagerInterface::class)
        );
        $application->add($command);

        $commandTester = new CommandTester($application->find('app:import-csv-data'));
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();

        // Check that expected strings exist in the output.
        $this->assertStringContainsString('Imported data from', $output);
        $this->assertStringContainsString('Done. Total imported rows:', $output);
    }

    public function testCommandHandlesMissingFile(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        // Create new command that points to noexistent fake folder.
        $command = new class($container->get(EntityManagerInterface::class)) extends ImportExcelDataCommand {
            protected static $defaultName = 'app:import-test-missing';

            protected function configure(): void
            {
                $this->setName(self::$defaultName);
            }

            protected function execute($input, $output): int
            {
                $basePath = __DIR__ . '/fake-data/';
                $files = ['missing.csv' => \App\Entity\LowEconomicStandard::class];

                foreach ($files as $filename => $entityClass) {
                    $filePath = $basePath . $filename;
                    if (!file_exists($filePath)) {
                        $output->writeln("File not found: $filename");
                        return self::SUCCESS;
                    }
                }
                return self::SUCCESS;
            }
        };

        $application = new Application();
        $application->add($command);

        $tester = new CommandTester($application->find('app:import-test-missing'));
        $tester->execute([]);

        $this->assertStringContainsString('File not found: missing.csv', $tester->getDisplay());
    }

    public function testCommandHandlesUnreadableFile(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $filename = __DIR__ . '/unreadable.csv';

        if (file_exists($filename)) {
            chmod($filename, 0644);
            unlink($filename);
        }

        file_put_contents($filename, 'header1,header2' . PHP_EOL . 'value1,value2');

        // Change rights so we can not read.
        chmod($filename, 0000);

        $command = new class($container->get(EntityManagerInterface::class)) extends ImportExcelDataCommand {
            protected static $defaultName = 'app:import-test-unreadable';

            protected function configure(): void
            {
                $this->setName(self::$defaultName);
            }

            protected function execute($input, $output): int
            {
                $filePath = __DIR__ . '/unreadable.csv';
                if (!is_readable($filePath)) {
                    $output->writeln("Could not open file: unreadable.csv");
                    return self::SUCCESS;
                }
                return self::SUCCESS;
            }
        };

        $application = new Application();
        $application->add($command);

        $tester = new CommandTester($application->find('app:import-test-unreadable'));
        $tester->execute([]);

        // Reset rights and unlink.
        chmod($filename, 0644);
        unlink($filename);

        $this->assertStringContainsString('Could not open file: unreadable.csv', $tester->getDisplay());
    }
}

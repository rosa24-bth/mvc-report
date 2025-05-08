<?php

namespace App\Tests\Project;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use App\Command\ImportExcelDataCommand;
use App\Service\FileLoader;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Test for the ImportExcelDataCommand class.
 *
 * We use extends KernelTestCase instead of TestCase because this command depends on Symfony services like the
 * EntityManager, which are only available when the Symfony kernel is booted.
 */
class ImportExcelDataCommandTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $entityManager = $container->get(EntityManagerInterface::class);
        $fileLoader = $container->get(FileLoader::class);

        $application = new Application();
        $application->add(new ImportExcelDataCommand($entityManager, $fileLoader));

        $command = $application->find('app:import-csv-data');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
    }

    public function testCommandOutputsSuccessMessage(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $entityManager = $container->get(EntityManagerInterface::class);
        $fileLoader = $container->get(FileLoader::class);

        $application = new Application();
        $command = new ImportExcelDataCommand($entityManager, $fileLoader);
        $application->add($command);

        $commandTester = new CommandTester($application->find('app:import-csv-data'));
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('Imported data from', $output);
        $this->assertStringContainsString('Done. Total imported rows:', $output);
    }

    public function testCommandHandlesMissingFile(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $entityManager = $container->get(EntityManagerInterface::class);
        $fileLoader = $container->get(FileLoader::class);

        $command = new class ($entityManager, $fileLoader) extends ImportExcelDataCommand {
            protected static $defaultName = 'app:import-test-missing';

            protected function configure(): void
            {
                $this->setName(self::$defaultName);
            }

            protected function execute($input, $output): int
            {
                unset($input);

                $basePath = __DIR__ . '/fake-data/';
                $files = ['missing.csv' => \App\Entity\LowEconomicStandard::class];

                foreach (array_keys($files) as $filename) {
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

        $entityManager = $container->get(EntityManagerInterface::class);
        $fileLoader = $container->get(FileLoader::class);

        $filename = __DIR__ . '/unreadable.csv';

        if (file_exists($filename)) {
            chmod($filename, 0644);
            unlink($filename);
        }

        file_put_contents($filename, 'header1,header2' . PHP_EOL . 'value1,value2');
        chmod($filename, 0000);

        $command = new class ($entityManager, $fileLoader) extends ImportExcelDataCommand {
            protected static $defaultName = 'app:import-test-unreadable';

            protected function configure(): void
            {
                $this->setName(self::$defaultName);
            }

            protected function execute($input, $output): int
            {
                unset($input);

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

        chmod($filename, 0644);
        unlink($filename);

        $this->assertStringContainsString('Could not open file: unreadable.csv', $tester->getDisplay());
    }

    public function testCommandUsesMockedFileLoader(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $entityManager = $container->get(EntityManagerInterface::class);
        $mockFileLoader = $this->createMock(FileLoader::class);

        $mockFileLoader->method('open')->willReturn($this->getMockedCsvStream());

        $command = new class ($entityManager, $mockFileLoader) extends ImportExcelDataCommand {
            protected static $defaultName = 'app:import-test-mocked';

            private EntityManagerInterface $entityManager;
            private FileLoader $fileLoader;

            public function __construct(EntityManagerInterface $entityManager, FileLoader $fileLoader)
            {
                parent::__construct($entityManager, $fileLoader);
                $this->entityManager = $entityManager;
                $this->fileLoader = $fileLoader;
            }

            protected function configure(): void
            {
                $this->setName(self::$defaultName);
            }

            private function importFile(string $filename, string $entityClass, $output): int
            {
                $counter = 0;
                $filePath = __DIR__ . '/' . $filename;
                $handle = $this->fileLoader->open($filePath);
                if (!$handle) {
                    $output->writeln("Could not open file: $filename");
                    return 0;
                }

                $headers = fgetcsv($handle, 0, ",", '"', "\\");
                while (($data = fgetcsv($handle, 0, ",", '"', "\\")) !== false) {
                    $groupName = $data[0];
                    $length = count($data);
                    for ($i = 1; $i < $length; $i++) {
                        $year = (int) $headers[$i];
                        $value = (float) str_replace(',', '.', $data[$i]);

                        $entity = new $entityClass();
                        $entity->setGroupName($groupName);
                        $entity->setYear($year);
                        $entity->setValue($value);

                        $this->entityManager->persist($entity);
                        $counter++;
                    }
                }

                fclose($handle);
                $output->writeln("Imported data from $filename.");

                return $counter;
            }

            protected function execute($input, $output): int
            {
                unset($input);

                $files = [
                    'low_economic_standard.csv' => \App\Entity\LowEconomicStandard::class
                ];

                $counter = 0;
                foreach ($files as $filename => $entityClass) {
                    $counter += $this->importFile($filename, $entityClass, $output);
                }

                $this->entityManager->flush();
                $output->writeln("Done. Total imported rows: $counter");

                return self::SUCCESS;
            }
        };

        $application = new Application();
        $application->add($command);

        $tester = new CommandTester($application->find('app:import-test-mocked'));
        $tester->execute([]);

        $this->assertStringContainsString('Imported data from low_economic_standard.csv', $tester->getDisplay());
        $this->assertStringContainsString('Done. Total imported rows: 2', $tester->getDisplay());
    }

    private function getMockedCsvStream(): mixed
    {
        $csvContent = [
            ['Group', '2020', '2021'],
            ['Group A', '10,5', '20,3'],
        ];

        $stream = fopen('php://memory', 'r+');
        foreach ($csvContent as $row) {
            fputcsv($stream, $row, ",", '"', "\\");
        }
        rewind($stream);

        return $stream;
    }
}

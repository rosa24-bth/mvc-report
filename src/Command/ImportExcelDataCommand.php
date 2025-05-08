<?php

namespace App\Command;

use App\Entity\LowEconomicStandard;
use App\Entity\LongtermEconomicSupport;
use App\Service\FileLoader; // 🆕 Import för nya tjänsten
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to import CSV files into the database.
 *
 * This command reads two CSV files from the var/data/ directory and imports their content into the corresponding
 * database tables using Doctrine.
 *
 * One file contains values for the indicator "Low economic standard" and the other for "Longterm economic support".
 *
 * To use it run this in the terminal:
 * php bin/console app:import-csv-data
 */
#[AsCommand(
    name: 'app:import-csv-data',
    description: 'Import economic indicators from two CSV files',
)]
class ImportExcelDataCommand extends Command
{
    private EntityManagerInterface $entityManager;

    private FileLoader $fileLoader;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface $entityManager Injected Doctrine entity manager.
     * @param FileLoader $fileLoader Service to open files instead of directly using fopne().
     */
    public function __construct(EntityManagerInterface $entityManager, FileLoader $fileLoader)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->fileLoader = $fileLoader;
    }

    /**
     * Executes the import command.
     *
     * @param InputInterface  $input  Console input (not used).
     * @param OutputInterface $output Console output (used for messages).
     * @return int Command::SUCCESS or Command::FAILURE.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Directory where the CSV files are located.
        $basePath = __DIR__ . '/../../var/data/';

        // Each file is mapped to its target entity class.
        $files = [
            'low_economic_standard.csv' => LowEconomicStandard::class,
            'long_term_economic_support.csv' => LongtermEconomicSupport::class,
        ];

        $counter = 0;

        foreach ($files as $filename => $entityClass) {
            $filePath = $basePath . $filename;

            // Check if the file exists.
            if (!file_exists($filePath)) {
                $output->writeln("File not found: $filename");
                continue;
            }

            // Use fileLoader() to open.
            $handle = $this->fileLoader->open($filePath);
            if (!$handle) {
                $output->writeln("Could not open file: $filename");
                continue;
            }

            // First row in the CSV is the header and contains years.
            $headers = fgetcsv($handle, 0, ",", '"', "\\");

            // Read each row (group name + values per year).
            while (($data = fgetcsv($handle, 0, ",", '"', "\\")) !== false) {
                // First column has the group names.
                $groupName = $data[0];

                // Loop through each year value in the row.
                for ($i = 1; $i < count($data); $i++) {
                    $year = (int) $headers[$i];
                    $value = (float) str_replace(',', '.', $data[$i]);

                    // Create and fill up the entity.
                    /** @var LowEconomicStandard|LongtermEconomicSupport $entity */
                    $entity = new $entityClass();
                    $entity->setGroupName($groupName);
                    $entity->setYear($year);
                    $entity->setValue($value);

                    // Saving.
                    $this->entityManager->persist($entity);
                    $counter++;
                }
            }

            fclose($handle);
            $output->writeln("Imported data from $filename.");
        }

        // Then write everything to the database.
        $this->entityManager->flush();
        $output->writeln("Done. Total imported rows: $counter");

        return Command::SUCCESS;
    }
}

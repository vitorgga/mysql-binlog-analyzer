<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\ProgressBar;

class MysqlBinlogAnalyzer extends Command
{
    protected static $defaultName = 'analyzer';

    /**
     * Symfony command configure options
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('analyzer')
            ->setDescription('MySQL Binlog Analyzer')
            ->setHelp('This command show binlog usage')
            ->addArgument('file', InputArgument::OPTIONAL, 'The name of the file to analyze');
    }

    /**
     * Symfony run function
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // CLEAR CONSOLE
        $output->write(sprintf("\033\143"));

        // IO START
        $io = new SymfonyStyle($input, $output);
        $io->title('MySQL Binlog Analyzer');

        // FILE
        $file = $input->getArgument('file') ?? 'example.csv';
        $file = $io->ask('File to analyzer?', $file) ?? $file;

        $io->note("File: {$file}");

        // EXTRACT
        $a_total = self::extract($io, $file);

        // PRINTER
        self::printer($io, $output, $a_total);

        return Command::SUCCESS;
    }

    /**
     * Printer binlog analyzer informations
     *
     * @param SymfonyStyle $io
     * @param OutputInterface $output
     * @param array $a_total
     * @return void
     */
    private function printer($io, $output, $a_total = [])
    {
        $total = array_sum($a_total);

        $io->note("Total: {$total} transactions");

        foreach ($a_total as $key => $value) {

            $max = $max ?? $value;

            $progressBar = new ProgressBar($output, $total);
            $progressBar->setEmptyBarCharacter(' ');
            $progressBar->setProgressCharacter('|');            
            $progressBar->advance($value);

            $output->write("  {$key}");
            $io->newLine();
        }

        $io->newLine();
    }

    /**
     * Extract binlog informations and send to a array to count usage
     *
     * @param SymfonyStyle $io
     * @param string $file filename to binlog extracted file
     * @return array key = sql function, value = usage count
     */
    private function extract($io, $file = '')
    {
        if (!file_exists($file)) {
            $io->error("File not found");
            return [];
        }

        $content = file_get_contents($file);

        $a_lines = explode("\n", $content);

        $a_total = [];

        foreach ($a_lines as $line) {

            $a_expressions = [
                "(INSERT INTO)(.*?)(\()",
                "(REPLACE INTO)(.*?)(\()",
                "(UPDATE)(.*?)(SET)",
                "(DELETE FROM)(.*?)(WHERE)",
            ];

            foreach ($a_expressions as $e) {
                preg_match("/{$e}/", $line, $matches);

                if ($matches) {
                    $id = trim(trim($matches[1]) . " " . trim($matches[2]));
                    @$a_total[$id]++;
                } 
            }
        }

        arsort($a_total);

        $a_total = array_slice($a_total, 0, 30);
        
        return $a_total;
    }
}
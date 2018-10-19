<?php

namespace App\Command;

use Exception;
use SplFileObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ReplaceCommand
 */
class ReplaceCommand extends Command
{
    /**
     * @var array
     */
    protected $replaceChars = [];

    /**
     * Constructor
     *
     * @param string|null $name
     */
    public function __construct(?string $name = null)
    {
        parent::__construct($name);

        $this->initReplaceChars();
    }

    /**
     * Configuratie
     */
    protected function configure()
    {
        $this
            ->setName('pronexus:replace:diacritics')
            ->setDescription('Foutief geformatteerde diacrieten in een UTF-8 bestand vervangen')
            ->addArgument('bestand', InputArgument::REQUIRED, 'Bestand waarin de foutieve diacrieten worden vervangen');
    }

    /**
     * Execute
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('bestand');
        if (!file_exists($filename)) {
            $output->writeln('<error>Bestand niet gevonden</error>');

            return;
        }

        $output->writeln(sprintf('<comment>Bestand gevonden: %s</comment>', $filename));
        $this->replaceFile($filename);
        $output->writeln('<info>Bestand verwerkt</info>');
    }

    /**
     * Replace diacritics in file
     *
     * @param string $filename
     */
    protected function replaceFile($filename)
    {
        $file = new SplFileObject($filename);
        $write = fopen($filename . '.tmp', "w");

        while (!$file->eof()) {
            $line = $file->fgets();
            $fixed = $this->replaceDiacritics($line);

            fwrite($write, $fixed);
        }

        fclose($write);
        $file = null;

        rename($filename . '.tmp', $filename);
    }

    /**
     * Replace diacritics in string
     *
     * @param   string $text
     * @return  string
     */
    protected function replaceDiacritics($text)
    {
        return str_replace($this->replaceChars['search'], $this->replaceChars['replace'], $text);
    }

    /**
     * Initialize replacement characters
     */
    protected function initReplaceChars()
    {
        $replaceChars = [
            'Èe' => 'ë',
            'Èu' => 'ü',
            'Èi' => 'ï',
            'Èo' => 'ö',
            'Èa' => 'ä',
            'ÈE' => 'Ë',
            'ÈU' => 'Ü',
            'ÈI' => 'Ï',
            'ÈO' => 'Ö',
            'ÈA' => 'Ä',
            'Ës' => 's',
            'Ën' => 'n',
            'Ëg' => 'g',
            'Ëc' => 'ç',
            'ËS' => 'S',
            'ËN' => 'N',
            'ËG' => 'G',
            'ËC' => 'Ç',
            'Êa' => 'â',
            'ÊA' => 'Â',
            'Æe' => 'e',
            'Æu' => 'u',
            'Æi' => 'i',
            'Æo' => 'o',
            'Æa' => 'a',
            'ÆE' => 'E',
            'ÆU' => 'U',
            'ÆI' => 'I',
            'ÆO' => 'O',
            'ÆA' => 'A',
            'Æs' => 's',
            'Æn' => 'n',
            'Æg' => 'g',
            'Æc' => 'c',
            'ÆS' => 'S',
            'ÆN' => 'N',
            'ÆG' => 'G',
            'ÆC' => 'C',
            'Ãs' => 's',
            'Ãn' => 'n',
            'Ãg' => 'g',
            'Ãc' => 'c',
            'ÃS' => 'S',
            'ÃN' => 'N',
            'ÃG' => 'G',
            'ÃC' => 'C',
            'Ãe' => 'ê',
            'Ãu' => 'û',
            'Ãi' => 'î',
            'Ão' => 'ô',
            'Ãa' => 'â',
            'ÃE' => 'Ê',
            'ÃU' => 'Û',
            'ÃI' => 'Î',
            'ÃO' => 'Ô',
            'ÃA' => 'Â',
            'Âe' => 'é',
            'Âu' => 'ú',
            'Âi' => 'í',
            'Âo' => 'ó',
            'Âa' => 'á',
            'ÂE' => 'É',
            'ÂU' => 'Ú',
            'Âs' => 's',
            'Ân' => 'n',
            'Âg' => 'g',
            'Âc' => 'c',
            'ÂS' => 'S',
            'ÂN' => 'N',
            'ÂG' => 'G',
            'ÂC' => 'C',
            'Áe' => 'è',
            'Áu' => 'ù',
            'Ái' => 'ì',
            'Áo' => 'ò',
            'Áa' => 'à',
            'ÁE' => 'È',
            'ÁU' => 'Ù',
            'ÁI' => 'Ì',
            'ÁO' => 'Ò',
            'ÁA' => 'À',
            'Ïc' => 'c',
            'Ïs' => 's',
            'Ïz' => 'z',
            'ÏC' => 'C',
            'ÏS' => 'S',
            'ÏZ' => 'Z',
            '?G' => 'G',
        ];

        $this->replaceChars = [
            'search'  => array_keys($replaceChars),
            'replace' => array_values($replaceChars),
        ];
    }
}

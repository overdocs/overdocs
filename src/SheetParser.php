<?php
namespace OverDocs;

class SheetParser
{
    protected $debug;
    protected $filename;
    protected $id;
    protected $config;

    public function __construct($id, $filename, $config, $debug)
    {
        if (!file_exists($filename)) {
            throw new \Exception("Requested file with a path $filename could not be found");
        }

        $this->debug = $debug;
        $this->config = $config;
        $this->id = $id;
        $this->filename = $filename;
    }

    public function parseMeta()
    {
        $xml = new \SimpleXmlElement(file_get_contents($this->filename));

        $meta = new \stdClass;
        $meta->title = (string) $xml->title;
        $meta->summary = (string) $xml->summary;
        $meta->keywords = array_map(function ($keyword) {
            return (string) $keyword;
        }, (array) $xml->keywords[0]->keyword);

        return $meta;
    }

    public function parseContent()
    {
        $xml = new \DOMDocument;
        $xml->load($this->filename);

        $xsl = new \DOMDocument;
        $xsl->load('./sheet.xsl');

        $proc = new \XSLTProcessor;
        $proc->importStyleSheet($xsl);
        $proc->setParameter('', 'base_url', $this->config['base_url']);
        $proc->setParameter('', 'images_url', $this->config['base_url'] . '/images/sheets/' . $this->id . '/');
        $proc->registerPHPFunctions([
            'OverDocs\\SheetParser::canonicalize',
            'OverDocs\\SheetParser::parseAbbreviation'
        ]);

        $output =  $proc->transformToXML($xml);

        if (!$this->debug) {
            $output = $this->minify($output);
        }

        return $output;
    }

    public function minify($string)
    {
        // Original code from https://gist.github.com/aarongustafson/862452
        $chunks = preg_split('/(<pre.*?\/pre>)/ms', $string, -1, PREG_SPLIT_DELIM_CAPTURE);

        $buffer = '';

        foreach ($chunks as $chunk) {
            if (strpos($chunk, '<pre') !== 0) {
                $chunk = preg_replace('/[\\n\\r\\t]+/', ' ', $chunk); // new lines & tabs
                $chunk = preg_replace('/\\s{2,}/', ' ', $chunk); // extra whitespaces
                $chunk = preg_replace('/>\\s</', '><', $chunk); // inner-tags whitespaces
            }

            $buffer .= $chunk;
        }

        return $buffer;
    }

    public static function canonicalize($address)
    {
        $address = explode('/', $address);
        $keys = array_keys($address, '..');

        foreach($keys as $keyPos => $key) {
            array_splice($address, $key - ($keyPos * 2 + 1), 2);
        }

        $address = implode('/', $address);
        $address = str_replace('./', '', $address);

        return $address;
    }

    public static function parseAbbreviation($abbreviation) {
        $abbreviations = require './config/abbreviations.php';
        $abbreviation = $abbreviation[0]->nodeValue;

        if (isset($abbreviations[$abbreviation])) {
            return $abbreviations[$abbreviation];
        }

        throw new \Exception("No value specified for $abbreviation abbreviation");
    }
}

<?php
namespace OverDocs;

class SheetLinter
{
    private $hasErrors = false;
    private $messages;
    protected $lines;
    protected $filename;
    protected $content;
    protected $dom;

    public $linters = [
        'lintUTF8BOM',
        'lintXmlProlog',
        'lintLineEndings',
        'lintLineLength',
        'lintNewLineAtEnd',
        'lintTrailingBlanks',
        'lintSummary',
    ];
    public $maxMessages = 50;

    const WARNING = 'Warning';
    const ERROR   = 'Error';

    public function lint($filename)
    {
        $this->messages = [];
        $this->lines    = file($filename);
        $this->content  = file_get_contents($filename);
        $this->dom      = new \DomDocument;

        $this->dom->load($filename);

        foreach ($this->linters as $linter) {
            $this->$linter();
        }

        return [
            'messages' => $this->messages,
            'passed'   => count($this->messages) == 0
        ];
    }

    public function hasErrors() {
        return $this->hasErrors;
    }

    protected function lintLineEndings()
    {
        if ((strpos($this->content, "\r\n")) !== false) {
            $this->error(null,
                    "File must not use Windows-style line endings");
        }
    }

    protected function lintLineLength()
    {
        foreach ($this->lines as $line => $lineContent) {
            if (strlen($lineContent) > 110) {
                $this->warn($line + 1,
                        "Line length limit of 110 chars exceeded");
            }
            $line++;
        }
    }

    protected function lintTrailingBlanks()
    {
        foreach ($this->lines as $line => $lineContent) {
            if (preg_match('/[ \t]+$/', $lineContent)) {
                $this->warn($line + 1,
                        "Redundant trailing blanks");
            }
            $line++;
        }
    }

    protected function lintNewLineAtEnd()
    {
        if ($this->content[strlen($this->content) - 1] != "\n") {
            $this->warn(count($this->lines),
                    "Missing newline at end of file");
        }
    }

    protected function lintXmlProlog()
    {
        if (!preg_match('#<\?\s?xml version="1.0"\s?\?>#', $this->lines[0])) {
            $this->error(1,
                    "Missing or invalid XML prolog");
        }
    }

    protected function lintUTF8BOM()
    {
        $bom = pack('H*', 'EFBBBF');
        if (preg_match("/^$bom/", $this->lines[0])) {
            $this->error(1,
                    "Files should not contain UTF-8 BOM");
        }
    }

    protected function lintSummary()
    {
        $xpath = new \DOMXPath($this->dom);
        $xpath->registerNamespace('o', 'http://overdocs.net/overdocs');
        $summary = $xpath->query('/o:sheet/o:summary/text()')->item(0);

        if (strlen($summary->nodeValue) > 80) {
            $this->warn($summary->getLineNo(),
                    "Summary should not be longer than 80 chars");
        }
        if (substr($summary->nodeValue, -1) != '.') {
            $this->warn($summary->getLineNo(),
                    "Summary should end with a dot");
        }
    }

    protected function warn($line, $message)
    {
        if (count($this->messages) < $this->maxMessages) {
            $this->messages[] = [
                'level'   => self::WARNING,
                'line'    => $line,
                'message' => $message
            ];
        }
    }

    protected function error($line, $message)
    {
        $this->hasErrors = true;

        $this->messages[] = [
            'level'   => self::ERROR,
            'line'    => $line,
            'message' => $message
        ];
    }
}

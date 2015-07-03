<?php
namespace OverDocs;

class Template
{
    protected $data;
    protected $filename;
    protected $parent;
    protected $currentBlock;
    protected $segments;
    protected $blocks;

    public static $globals = [];

    public function __construct($filename, $data = [])
    {
        $this->filename = $filename;
        $this->data     = $data;
    }

    /**
     * Renders the template and returns a string.
     */
    public function render()
    {
        $executed = $this->execute();

        return implode('', $executed['segments']);
    }

    /**
     * Collects segments from the current template, and if it inherits from the
     * pattern, then ignores the text content and renders the parent template
     * (but replaces overriden blocks).
     */
    protected function execute()
    {
        $collected = $this->collect();

        if ($this->parent) {
            $parent = $this->parent->execute();

            foreach ($parent['segments'] as $segment) {
                if ($segment instanceof TemplateBlock &&
                        isset($collected['blocks'][$segment->name])) {
                    $segment->content = $collected['blocks'][$segment->name]->content;
                }
            }

            return $parent;
        }

        return $collected;
    }

    /**
     * Collects the segment from the template. A segment may be either free text
     * content or a block declaration. A block declaration, delimited by
     * block() and end() calls, specifies content that is bound to a name and
     * can be overriden in child templates (using inherits()).
     */
    protected function collect()
    {
        // Start output buffering in order to grab the first block of data.
        ob_start();
        extract(self::$globals, EXTR_SKIP);
        extract($this->data, EXTR_SKIP);

        require $this->locate($this->filename);

        if ($this->currentBlock) {
            throw new LogicError('Block ' + $this->currentBlock->name + 'is never closed.');
        }

        // Collect the last block of data.
        $this->segments[] = ob_get_clean();

        return [
            'segments' => $this->segments,
            'blocks'   => $this->blocks
        ];
    }

    public function __toString()
    {
        return $this->render();
    }

    protected function inherits($filename, $shared = [])
    {
        $this->parent = new Template($filename, $shared);
    }

    /**
     * Starts the definition of a block. Free content is collected into segment,
     * and the output buffering is started in order to grab the contents of the
     * block.
     */
    protected function block($name)
    {
        $this->segments[] = ob_get_clean();

        $this->currentBlock = new TemplateBlock($name);
        ob_start();
    }

    /**
     * Ends the definition of a block and enters free content mode.
     */
    protected function end()
    {
        if (!$this->currentBlock) {
            throw new LogicError('Called end() while not in block context.');
        }
        $this->currentBlock->content = ob_get_clean();
        $this->blocks[$this->currentBlock->name] = $this->currentBlock;
        $this->segments[] = $this->currentBlock;
        $this->currentBlock = null;

        ob_start();
    }

    private function locate($filename)
    {
        return 'templates/' . $filename . '.php';
    }

    protected function escape($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

class TemplateBlock
{
    public $name;
    public $content;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->content;
    }
}

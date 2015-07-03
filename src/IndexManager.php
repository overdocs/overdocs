<?php
namespace OverDocs;

class IndexManager
{
    private $cache;

    public function read()
    {
        if ($this->cache) {
            return $this->cache;
        }
        return $this->cache = json_decode(file_get_contents('public/index.json'), true);
    }

    public function write($data, $jsonFlags = null)
    {
        $this->cache = $data;
        return file_put_contents('public/index.json', json_encode($data, $jsonFlags));
    }

    public function getSheetsInCategory($category)
    {
        $result = [];
        $sheets = $this->read()['sheets'];

        foreach ($sheets as $sheet) {
            if ($sheet['category'] === $category) {
                $result[] = $sheet;
            }
        }

        // Sort results by the title.
        usort($result, function ($a, $b) {
            return $a['title'] > $b['title'];
        });

        return $result;
    }

    public function getNonEmptyCategoryNames()
    {
        $result = [];
        $index = $this->read();

        foreach ($index['category_names'] as $category => $name) {
            if ($this->categoryContainsSheets($category)) {
                $result[$category] = $index['category_names'][$category];
            }
        }

        return $result;
    }

    public function categoryContainsSheets($category)
    {
        $sheets = $this->read()['sheets'];

        foreach ($sheets as $sheet) {
            if ($sheet['category'] === $category) {
                return true;
            }
        }

        return false;
    }

    public function getSheetsWithKeyword($name)
    {
        $result = [];

        $sheets = $this->read()['sheets'];
        $categories = require './config/categories.php';

        foreach ($sheets as $sheet) {
            if (in_array($name, $sheet['keywords'])) {
                $sheet['title'] = $categories[$sheet['category']] . ' - ' . $sheet['title'];
                $result[] = $sheet;
            }
        }

        // Sort results by the title.
        uasort($result, function ($a, $b) {
            return $a['title'] > $b['title'];
        });

        return $result;
    }

    public function getSheetMeta($sheetKey)
    {
        return $this->read()['sheets'][$sheetKey];
    }
}

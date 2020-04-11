<?php


namespace App\Reader;


class FileReader implements Reader
{

    public function read($filePath)
    {
        $content = [];
        if (file_exists($filePath)) {
            $fileContent = file_get_contents($filePath);

            foreach (explode("\n", $fileContent) as $eachLine) {
                if (!empty($eachLine)) $content[] = $eachLine;
            }
        }

        return $content;
    }
}
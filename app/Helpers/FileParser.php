<?php
namespace App\Helpers;

use SebastianBergmann\Diff\Output\StrictUnifiedDiffOutputBuilder;

Class FileParser
{

    public static function getFileDiff($oldContent, $newContent)
    {
        $oldLines = explode("\n", $oldContent);
        $newLines = explode("\n", $newContent);

        $builder = new StrictUnifiedDiffOutputBuilder([
            'contextLines' => 0,
            'fromFile'     => 'Original',
            'toFile'       => 'New',
        ]);

        $differ = new \SebastianBergmann\Diff\Differ($builder);
        $diff = $differ->diff($oldLines, $newLines);

        $lines = explode("\n", $diff);
        $added = [];
        $removed = [];

        foreach ($lines as $line) {
            if (strpos($line, '+') === 0 && !str_starts_with($line, '+++')) {
                $added[] = substr($line, 1);
            } elseif (strpos($line, '-') === 0 && !str_starts_with($line, '---')) {
                $removed[] = substr($line, 1);
            }
        }

        return [
            'added' => $added,
            'old' => $oldContent,
            'removed' => $removed,
        ];
    }
}

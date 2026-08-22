<?php

namespace Lennord\FloridaySdk\Tests;

use PHPUnit\Framework\TestCase;

class ArchTest extends TestCase
{
    public function test_project_does_not_use_debugging_functions(): void
    {
        $forbidden = ['dd(', 'dump(', 'ray(']; // scan only src to avoid self-detection
        $paths = [
            realpath(__DIR__ . '/../src') ?: null,
        ];

        $checked = 0;
        foreach (array_filter($paths) as $base) {
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($base));
            foreach ($iterator as $file) {
                if (!$file->isFile()) {
                    continue;
                }
                $ext = strtolower($file->getExtension());
                if ($ext !== 'php') {
                    continue;
                }
                $contents = file_get_contents($file->getPathname());
                foreach ($forbidden as $needle) {
                    $this->assertStringNotContainsString(
                        $needle,
                        $contents,
                        sprintf('Forbidden debug function %s found in %s', $needle, $file->getPathname())
                    );
                }
                $checked++;
            }
        }

        $this->assertGreaterThan(0, $checked, 'No files were checked for architecture rules.');
    }
}

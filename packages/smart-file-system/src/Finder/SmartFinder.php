<?php

declare(strict_types=1);

namespace Symplify\SmartFileSystem\Finder;

use Symfony\Component\Finder\Finder;
use Symplify\SmartFileSystem\FileSystemFilter;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @see \Symplify\SmartFileSystem\Tests\Finder\SmartFinder\SmartFinderTest
 */
final class SmartFinder
{
    /**
     * @var FinderSanitizer
     */
    private $finderSanitizer;

    /**
     * @var FileSystemFilter
     */
    private $fileSystemFilter;

    public function __construct(FinderSanitizer $finderSanitizer, FileSystemFilter $fileSystemFilter)
    {
        $this->finderSanitizer = $finderSanitizer;
        $this->fileSystemFilter = $fileSystemFilter;
    }

    /**
     * @return SmartFileInfo[]
     */
    public function findPaths(array $directoriesOrFiles, string $path): array
    {
        $directories = $this->fileSystemFilter->filterDirectories($directoriesOrFiles);

        $fileInfos = [];

        if (count($directories) > 0) {
            $finder = new Finder();
            $finder->name('*')
                ->in($directories)
                ->path($path)
                ->files()
                ->sortByName();

            $fileInfos = $this->finderSanitizer->sanitize($finder);
        }

        return $fileInfos;
    }

    /**
     * @param string[] $excludedDirectories
     * @return SmartFileInfo[]
     */
    public function find(array $directoriesOrFiles, string $name, array $excludedDirectories = []): array
    {
        $directories = $this->fileSystemFilter->filterDirectories($directoriesOrFiles);

        $fileInfos = [];

        if (count($directories) > 0) {
            $finder = new Finder();
            $finder->name($name)
                ->in($directories)
                ->files()
                ->sortByName();

            if ($excludedDirectories !== []) {
                $finder->exclude($excludedDirectories);
            }

            $fileInfos = $this->finderSanitizer->sanitize($finder);
        }

        $files = $this->fileSystemFilter->filterFiles($directoriesOrFiles);
        foreach ($files as $file) {
            $fileInfos[] = new SmartFileInfo($file);
        }

        return $fileInfos;
    }
}

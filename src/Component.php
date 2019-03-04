<?php

declare(strict_types=1);

namespace ExtractorMpsv;

use Keboola\Component\BaseComponent;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class Component extends BaseComponent
{
    public function run(): void
    {
        $crawler = new Crawler($this->getContent('https://portal.mpsv.cz/sz/stat/nz/mes'));
        $files = $crawler->filterXPath('//a[contains(.,"stat")]');
        $fs = new Filesystem();
        $path = sys_get_temp_dir() . '/zips';
        $unzippedPath = sys_get_temp_dir() . '/unzipped';
        $fs->mkdir($path);
        /** @var Config $config */
        $config = $this->getConfig();
        foreach ($files as $file) {
            if (!$config->isFileInTimeInterval($file->textContent)) {
                $this->getLogger()->info("Skipping file {$file->textContent}");
                continue;
            }
            $url = sprintf(
                "https://portal.mpsv.cz/portalssz/download/getfile.do?filename=%s&_lang=cs_CZ",
                $file->textContent
            );
            $this->getLogger()->info("Downloading {$file->textContent}");
            $dest = $path . '/' . $file->textContent;
            $fs->copy($url, $path . '/' . $file->textContent);
            try {
                $this->unzipFile($dest, $unzippedPath, $file);
            } catch (\Throwable $e) {
                $this->getLogger()->warning("Cannot extract file {$e->getMessage()}");
            }
        }

        $finder = new Finder();

        foreach ($finder->in($unzippedPath)->files()->name('/nez[^\ ].*h\./i') as $file) {
            $fs->copy($file->getPathname(), '/data/out/files/' . $file->getFilename());
        }
    }

    private function unzipFile(string $dest, string $unzippedPath, \DOMElement $file): void
    {
        $zip = new \ZipArchive();
        $zip->open($dest);
        $zip->extractTo($unzippedPath . '/' . $file->textContent);
        $zip->close();
    }

    private function getContent(string $url): string
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'Codular Sample cURL Request'));
        $resp = curl_exec($curl);
        curl_close($curl);
        return $resp;
    }

    protected function getConfigClass(): string
    {
        return Config::class;
    }

    protected function getConfigDefinitionClass(): string
    {
        return ConfigDefinition::class;
    }
}

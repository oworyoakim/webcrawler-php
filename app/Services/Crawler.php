<?php

namespace App\Services;

use DOMDocument;
use DOMXPath;

class Crawler
{
    private $basePath = null;
    /**
     * @var Page[]
     */
    private $pages;
    private $internalLinks = [];
    private $externalLinks = [];
    private $images = [];

    public function __construct($basePath)
    {
        $this->basePath = strtolower($basePath);
        $this->pages = [];
    }

    public function getInternalLinks()
    {
        return $this->internalLinks;
    }

    public function getExternalLinks()
    {
        return $this->externalLinks;
    }

    public function getPages()
    {
        return $this->pages;
    }

    public function getImages()
    {
        return $this->images;
    }

    public function visitedPages()
    {
        return array_map(function (Page $page) {
            return $page->link;
        }, $this->pages);
    }

    public function avgPageLoad()
    {
        $totalLoad = array_reduce($this->pages, function ($load, Page $page) {
            return $load + $page->load;
        }, 0);
        $totalPages = count($this->pages);
        return $totalLoad / $totalPages;
    }

    public function avgTitleLength()
    {
        $totalTitleLength = array_reduce($this->pages, function ($length, Page $page) {
            return $length + strlen($page->title);
        }, 0);
        $totalPages = count($this->pages);
        return round($totalTitleLength / $totalPages);
    }

    public function avgWordCount()
    {
        $totalWords = array_reduce($this->pages, function ($words, Page $page) {
            return $words + $page->uniqueWords;
        }, 0);
        $totalPages = count($this->pages);
        return round($totalWords / $totalPages);
    }

    public function crawl($uri = "/")
    {
        // if we have already visited this page, we will skip it
        $visitedPages = $this->visitedPages();
        if (in_array($uri, $visitedPages))
        {
            return false; // we skip this page
        }
        // if we have an absolute path to the same site, we don't prepend the base
        if (substr($uri, 0, strlen($this->basePath)) === $this->basePath)
        {
            $path = $uri;
        } else
        {
            $path = "{$this->basePath}{$uri}";
        }
        $startTime = microtime(true);
        $html = file_get_contents($path);
        $endTime = microtime(true);
        // If we don't get any content back
        if (empty($html))
        {
            return false;
        }
        $page = new Page();
        $page->link = $path;
        $pageLoad = $endTime - $startTime;
        $page->load = $pageLoad;
        $statusCode = $this->getStatusCode($path);
        $page->status = $statusCode ?: 200;

        $dom = new DOMDocument();
        $dom->loadHTML($html, LIBXML_NOERROR);

        $titles = $dom->getElementsByTagName('title');
        // we only have one title tag per page
        if ($title = $titles->item(0))
        {
            $page->title = $title->nodeValue;
        }
        // count words in the page
        // remove everything between the style tags
        $str = preg_replace('/<style\\b[^>]*>(.*?)<\\/style>/s', '', $html);
        // remove everything between the script tags
        $str = preg_replace('/<script\\b[^>]*>(.*?)<\\/script>/s', '', $str);
        // remove html tags
        $str = strip_tags(strtolower($str));
        // we get all the words in the string including duplicates
        $words = str_word_count($str, 1);
        $page->totalWords = count($words);
        // get unique words and their counts
        $words = array_count_values($words);
        // count the unique words
        $page->uniqueWords = count($words);
        $this->pages[] = $page;
        // search for images
        $this->processImages($dom);
        // search for links
        $this->processLinksUsingXpath($dom);
        //var_dump($page);die;
        return true;
    }

    private function processLinksUsingXpath(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);
        // get the href attribute of all a tags whose href attributes start with / (internal links)
        $hrefs = $xpath->query('//a[starts-with(@href,"/")]/@href');
        foreach ($hrefs as $href)
        {
            $val = strtolower($href->nodeValue);
            if (!in_array($val, array_values($this->internalLinks)))
            {
                $this->internalLinks = array_merge($this->internalLinks, [$val]);
            }
        }
        // get the href attribute of all a tags whose href attributes start with http (both internal and external links)
        $hrefs = $xpath->query('//a[starts-with(@href,"http")]/@href');
        foreach ($hrefs as $href)
        {
            $val = strtolower($href->nodeValue);
            // Internal links start with the basePath or subdomain, otherwise they are external since they already start with http
            if (substr($val, 0, strlen($this->basePath)) === $this->basePath)
            {
                if (!in_array($val, array_values($this->internalLinks)))
                {
                    $this->internalLinks = array_merge($this->internalLinks, [$val]);
                }
            } elseif (!in_array($val, array_values($this->externalLinks)))
            {
                $this->externalLinks = array_merge($this->externalLinks, [$val]);
            }
        }
    }

    private function processLinksUsingTagName(DOMDocument $dom)
    {
        $links = $dom->getElementsByTagName('a');
        foreach ($links as $link)
        {
            $href = $link->attributes->getNamedItem('href')->nodeValue;
            // All external links start with http/https and do not start with the basePath
            if (substr($href, 0, 4) === "http" && substr($href, 0, strlen($this->basePath)) !== $this->basePath)
            {
                if(!in_array($href, array_values($this->externalLinks))){
                    $this->externalLinks = array_merge($this->externalLinks, [$href]);
                }
            } else
            {
                //check for # and javascript: links
                if(substr($href, 0, 1) !== "#" && substr($href, 0, 10) !== "javascript" && !in_array($href, array_values($this->internalLinks)))
                {
                    $this->internalLinks = array_merge($this->internalLinks, [$href]);
                }
            }
        }
    }

    private function processImages(DOMDocument $dom)
    {
        $images = $dom->getElementsByTagName('img');
        foreach ($images as $image)
        {
            $src = $image->attributes->getNamedItem('src')->nodeValue;
            if(!in_array($src, array_values($this->images))){
                $this->images = array_merge([$src], $this->images);
            }
        }
    }

    private function getStatusCode($url){
        $headers = get_headers($url);
        if(!isset($headers[0])){
            return null;
        }
        return substr($headers[0], 9, 3);
    }
}

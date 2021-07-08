<?php

namespace App\Http\Controllers;

use App\Services\Crawler;
use App\Services\Page;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $path = "https://agencyanalytics.com";
        $crawler = new Crawler($path);
        $pageCount = 5;
        $i = $visitedPages = 0;
        do {
            $links = $crawler->getInternalLinks();
            $uri = !empty($links[$i]) ? $links[$i] : "";
            $crawler->crawl($uri);
            $i++;
            $visitedPages = count($crawler->visitedPages());
        } while($visitedPages < $pageCount);

        return view("welcome", compact('path','visitedPages', 'crawler'));
    }
}

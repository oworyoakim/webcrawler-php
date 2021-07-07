<?php
require_once "./Page.php";
require_once "./Crawler.php";
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>As Simple Web Crawler built on PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
    <body>
        <div class="container">
            <div class="card mt-5">
                <div class="card-header">
                    <h1 class="card-title">Site Crawled: <strong><?php echo $path; ?></strong></h1>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-condensed table-stripped table-hover">
                        <thead class="bg-secondary text-white">
                        <tr>
                            <th>#</th>
                            <th>Page</th>
                            <th>Title</th>
                            <th>Load</th>
                            <th>Status</th>
                            <th>Unique Words</th>
                            <th>All Words</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($crawler->getPages() as $index => $page): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo $page->link; ?></td>
                                <td><?php echo $page->title; ?></td>
                                <td><?php echo $page->load; ?></td>
                                <td><?php echo $page->status; ?></td>
                                <td><?php echo $page->uniqueWords; ?></td>
                                <td><?php echo $page->totalWords; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">

                </div>
            </div>
            <ul class="list-group my-5">
                <li class="list-group-item bg-secondary text-white">Statistics</li>
                <li class="list-group-item">
                    <span>Number of pages crawled: </span>
                    <span class="badge bg-secondary float-end">
                        <?php echo $visitedPages; ?>
                    </span>
                </li>
                <li class="list-group-item">
                    <span>Number of a unique images: </span>
                    <span class="badge bg-secondary float-end">
                        <?php echo count($crawler->getImages()); ?>
                    </span>
                </li>
                <li class="list-group-item">
                    <span>Number of unique internal links: </span>
                    <span class="badge bg-secondary float-end">
                        <?php echo count($crawler->getInternalLinks()); ?>
                    </span>
                </li>
                <li class="list-group-item">
                    <span>Number of unique external links: </span>
                    <span class="badge bg-secondary float-end">
                        <?php echo count($crawler->getExternalLinks()); ?>
                    </span>
                </li>
                <li class="list-group-item">
                    <span>Avg page load (in seconds): </span>
                    <span class="badge bg-secondary float-end">
                        <?php echo $crawler->avgPageLoad(); ?>
                    </span>
                </li>
                <li class="list-group-item">
                    <span>Avg word count: </span>
                    <span class="badge bg-secondary float-end">
                        <?php echo $crawler->avgWordCount(); ?>
                    </span>
                </li>
                <li class="list-group-item">
                    <span>Avg Title length: </span>
                    <span class="badge bg-secondary float-end">
                        <?php echo $crawler->avgTitleLength(); ?>
                    </span>
                </li>
            </ul>
        </div>
    </body>
</html>

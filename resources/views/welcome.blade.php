@extends('layout')
@section('content')
    <div class="card mt-5">
        <div class="card-header">
            <h1 class="card-title">Site Crawled: <strong>{{$path}}</strong></h1>
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
                @foreach ($crawler->getPages() as $index => $page)
                <tr>
                    <td>{{$index + 1}}</td>
                    <td>{{$page->link}}</td>
                    <td>{{$page->title}}</td>
                    <td>{{$page->load}}</td>
                    <td>{{$page->status}}</td>
                    <td>{{$page->uniqueWords}}</td>
                    <td>{{$page->totalWords}}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <ul class="list-group my-5">
        <li class="list-group-item bg-secondary text-white">Statistics</li>
        <li class="list-group-item">
            <span>Number of pages crawled: </span>
            <span class="badge bg-secondary float-end">
                {{$visitedPages}}
            </span>
        </li>
        <li class="list-group-item">
            <span>Number of a unique images: </span>
            <span class="badge bg-secondary float-end">
                {{count($crawler->getImages())}}
            </span>
        </li>
        <li class="list-group-item">
            <span>Number of unique internal links: </span>
            <span class="badge bg-secondary float-end">
                {{count($crawler->getInternalLinks())}}
            </span>
        </li>
        <li class="list-group-item">
            <span>Number of unique external links: </span>
            <span class="badge bg-secondary float-end">
                {{count($crawler->getExternalLinks())}}
            </span>
        </li>
        <li class="list-group-item">
            <span>Avg page load (in seconds): </span>
            <span class="badge bg-secondary float-end">
                {{$crawler->avgPageLoad()}}
            </span>
        </li>
        <li class="list-group-item">
            <span>Avg word count: </span>
            <span class="badge bg-secondary float-end">
                {{$crawler->avgWordCount()}}
            </span>
        </li>
        <li class="list-group-item">
            <span>Avg Title length: </span>
            <span class="badge bg-secondary float-end">
                {{$crawler->avgTitleLength()}}
            </span>
        </li>
    </ul>
@endsection

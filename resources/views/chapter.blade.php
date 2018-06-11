@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title" style="font-size: 20px;">
                            <a href="{{route('home.book', ['id' => $book_chapter->book->id])}}">{{$book_chapter->book->title or ''}}</a> >
                            <small>{{$book_chapter->title or ''}}</small>
                        </h2>
                    </div>
                    <div class="panel-body">
                        {!! $book_chapter->content !!}
                    </div>
                    <div class="panel-footer">
                        <span><a href="{{route('home.desc', ['id' => $book_chapter->getPrevArticleId($book_chapter->book->id, $book_chapter->bxwx_id) ?? $book_chapter->id])}}">上一章</a></span>
                        <span style="float: right"><a href="{{route('home.desc', ['id' => $book_chapter->getNextArticleId($book_chapter->book->id, $book_chapter->bxwx_id) ?? $book_chapter->id])}}">下一章</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
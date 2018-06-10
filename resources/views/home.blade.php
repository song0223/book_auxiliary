@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form action="/search">
                            <div class="input-group">
                                <input type="text" class="form-control h50" name="query" placeholder="关键字..."
                                       value="{{$q or ''}}">
                                <a href="/search" class="glyphicon glyphicon-remove btn form-control-feedback"
                                   style="pointer-events:auto;z-index: 1000;margin-right: 45px;line-height: 35px;"></a>
                                <span class="input-group-btn"><button class="btn btn-default h50"
                                                                      type="submit"><span
                                                class="glyphicon glyphicon-search"></span></button></span>
                            </div>
                        </form>
                    </div>
                    <div class="panel-footer">关键词搜索（支持作者名，小说标题，内容关键字）</div>
                </div>
            </div>
            @if(!$q)
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">热门小说</div>
                        <ul class="list-group">
                            @foreach(($paginator ?? []) as $item)
                                <li class="list-group-item">
                                    <div class="media">
                                        <a class="media-left" href="#">
                                            <img style="width: 124px;height: 154px" class="media-object"
                                                 src="{{$item->image or ''}}">
                                        </a>
                                        <div class="media-body">
                                            <h4 class="media-heading">{{$item->title or ''}}
                                                <small>{{$item->author or ''}}</small>
                                            </h4>
                                            {!! $item->introduction !!}
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    {{--分页--}}
                    {{ $paginator->links() }}
                </div>
        </div>
    </div>
    @else
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default list-panel search-results">
                    <div class="panel-heading">
                        <h3 class="panel-title ">
                            <i class="fa fa-search"></i> 关于 “<span class="highlight">{{ $q }}</span>” 的搜索结果,
                            共 {{ $paginator->total() }} 条
                        </h3>
                    </div>
                    <div class="panel-body ">
                        @foreach($paginator as $post)
                            <div class="result">
                                <h3 class="title">{{ $post->book->title or ''}}</h3>
                                <div class="info">
                                    <a href="{{ $post->url }}" target="_blank">
                                        @if (isset($post->highlight['title']))
                                            @foreach($post->highlight['title'] as $item)
                                                {!! $item !!}
                                            @endforeach
                                        @else
                                            {{ $post->title }}
                                        @endif
                                    </a>
                                </div>
                                <div class="desc">
                                    @if (isset($post->highlight['content']))
                                        @foreach($post->highlight['content'] as $item)
                                            ......{!! $item !!}......
                                        @endforeach
                                    @else
                                        {{ mb_substr($post->content, 0, 150) }}......
                                    @endif
                                </div>
                                <hr>
                            </div>
                        @endforeach
                    </div>
                    {{ $paginator->links() }}
                </div>
            </div>
            @endif
        </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <button id="sous" type="button" class="btn btn-default dropdown-toggle h50"
                                        data-toggle="dropdown"><span id="s">{{$basic_data['search_menu'][$type ?? 1]}}</span>
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a data-id="1" href="#">搜索小说</a></li>
                                    <li><a data-id="2" href="#">全站搜索</a></li>
                                </ul>
                                <input type="hidden" name="type" value="{{$type or 1}}">
                                <input type="hidden" name="t" value="{{$t or 0}}">
                            </div>
                            <input type="text" class="form-control h50" name="query" placeholder="关键字..."
                                   value="{{$query or ''}}">
                            <a href="/search" class="glyphicon glyphicon-remove btn form-control-feedback"
                               style="pointer-events:auto;z-index: 1000;margin-right: 45px;line-height: 35px;"></a>
                            <span class="input-group-btn"><button id="search" class="btn btn-default h50"
                                ><span class="glyphicon glyphicon-search"></span></button>
                                </span>
                        </div>
                    </div>
                    <div class="panel-footer">关键词搜索（支持作者名，小说标题，内容关键字）</div>
                </div>
            </div>
        </div>
        @if($type == 1)
            <div class="row">
                <div class="col-md-9 col-md-offset-2">
                    <div class="panel panel-default search-lieb">
                        <div class="panel-heading">小说列表</div>
                        <ul class="list-group">
                            @foreach(($paginator ?? []) as $item)
                                <li class="list-group-item">
                                    <div class="media">
                                        <a class="media-left" href="{{route('home.book', ['id' => $item->bxwx_id])}}">
                                            <img style="width: 124px;height: 154px" class="media-object"
                                                 src="{{url($item->image)}}">
                                        </a>
                                        <div class="media-body">
                                            <h4 class="media-heading">
                                                @if (isset($item->highlight['title']))
                                                    @foreach($item->highlight['title'] as $em)
                                                        {!! $em !!}
                                                    @endforeach
                                                @else
                                                    {{ $item->title }}
                                                @endif
                                                <small>
                                                    @if (isset($item->highlight['author']))
                                                        @foreach($item->highlight['author'] as $em)
                                                            {!! $em !!}
                                                        @endforeach
                                                    @else
                                                        {{ $item->author }}
                                                    @endif
                                                </small>
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
        @else
            <div class="row">
                <div class="col-md-9 col-md-offset-2">
                    <div class="panel panel-default list-panel search-results">
                        <div class="panel-heading">
                            <h3 class="panel-title ">
                                <i class="fa fa-search"></i> 关于 “<span class="highlight">{{ $query }}</span>” 的搜索结果,
                                共 {{ $paginator->total() }} 条
                            </h3>
                        </div>
                        <div class="panel-body ">
                            @foreach($paginator as $post)
                                <div class="result">
                                    <h3 class="title"><a href="{{route('home.book', ['id' => $post->book->bxwx_id])}}">{{ $post->book->title or ''}}</a></h3>
                                    <div class="info">
                                        <a href="{{route('home.desc', ['id' => $post->id])}}" target="_blank">
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
                                                ......{!! mb_substr($item, 0, 100) !!}......
                                            @endforeach
                                        @else
                                            {{ mb_substr($post->content, 0, 100) }}......
                                        @endif
                                    </div>

                                </div>
                            @endforeach
                        </div>
                        {{ $paginator->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@section('other-js')
    <script>
        $('.dropdown-menu li a').click(function () {
            var value = $(this).attr('data-id');
            $("input[name='type']").val(value);
            $('#sous #s').html($(this).text());
        });

        $("#search").click(function () {
            search();
        });

        $(document).keyup(function(event){
            if(event.keyCode ==13){
                search();
            }
        });

        function search() {
            var type = $("input[name='type']").val();
            var keyword = $("input[name='query']").val();
            var t = $("input[name='t']").val();
            var b = 0;
            var url = "/search/" + t + "/" + type + "/" + b + "/" + keyword;
            if (keyword) {
                window.location = url;
            } else {
                $(".input-group").addClass('has-error');
            }
        }
    </script>
@endsection
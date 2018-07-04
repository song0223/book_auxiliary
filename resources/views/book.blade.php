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
                                        data-toggle="dropdown"><span
                                            id="s">{{$basic_data['search_menu'][$type ?? 1]}}</span>
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    @foreach(($basic_data['search_menu'] ?? []) as $key => $menu)
                                        <li><a data-id="{{$key}}" href="#">{{$menu}}</a></li>
                                    @endforeach
                                </ul>
                                <input type="hidden" name="type" value="{{$type or 1}}">
                                <input type="hidden" name="book_id" value="{{$book->id or 0}}">
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
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="media">
                            <a class="media-left" href="#">
                                <img style="width: 180px;height: 240px" class="media-object" src="{{url($book->image)}}"
                                     alt="">
                            </a>
                            <div class="media-body">
                                <h4 class="media-heading">{{$book->title or ''}}
                                    <small>{{$book->author or ''}}</small>
                                    <small style="float: right">
                                        最后更新时间{{\Carbon\Carbon::parse($book->updated_at)->format('Y-m-d')}}</small>
                                </h4>
                                {{$book->introduction or ''}}
                            </div>
                        </div>
                    </div>
                    <div id="list" class="panel-body">
                        <dl>
                            @foreach($book_chapter as $chapter)
                                <dd>
                                    <a href="{{route('home.desc', ['id' => $chapter->id])}}">{{str_limit($chapter->title, 36)}}</a>
                                </dd>
                            @endforeach
                        </dl>
                    </div>
                </div>

            </div>
        </div>
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
            var type = $("input[name='type']").val();
            var keyword = $("input[name='query']").val();
            var t = $("input[name='t']").val();
            var book_id = $("input[name='book_id']").val();
            var url = "/search/" + t + "/" + type + "/" + book_id + "/" + keyword;
            if (keyword) {
                window.location = url;
            } else {
                $(".input-group").addClass('has-error');
            }
        });

    </script>
@endsection
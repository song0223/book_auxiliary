@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-md-offset-2">
                <div class="panel panel-default">
                    <form action="{{route('home.import-book')}}">
                    <div class="panel-body">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <button id="sous" type="button" class="btn btn-default dropdown-toggle h50"
                                        data-toggle="dropdown"><span id="s">导入书籍</span>
                                </button>
                            </div>
                            <input type="text" class="form-control h50" name="url" placeholder="链接..."
                                   value="{{$query or ''}}">
                            <a href="/i" class="glyphicon glyphicon-remove btn form-control-feedback"
                               style="pointer-events:auto;z-index: 1000;margin-right: 45px;line-height: 35px;"></a>
                            <span class="input-group-btn"><button id="search" class="btn btn-default h50"
                                ><span class="glyphicon glyphicon-download-alt"></span></button>
                                </span>
                        </div>
                    </div>
                    </form>
                    <div class="panel-footer">导入书籍（在<a href="http://www.biquge.com.tw/" target="_blank">笔趣阁</a>搜索获取书籍的链接）</div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('other-js')
    <script>

    </script>
@endsection
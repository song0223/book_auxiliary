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
                                        data-toggle="dropdown"><span id="s">导入书籍</span>
                                </button>
                            </div>
                            <input type="text" class="form-control h50" name="url" placeholder="链接..."
                                   value="{{$query or ''}}">
                            <a href="/i" class="glyphicon glyphicon-remove btn form-control-feedback"
                               style="pointer-events:auto;z-index: 1000;margin-right: 45px;line-height: 35px;"></a>
                            <span class="input-group-btn"><button id="download" class="btn btn-default h50"
                                ><span class="glyphicon glyphicon-download-alt"></span></button>
                                </span>
                        </div>
                    </div>
                    <div id="jindut" style="width:848px;margin:0 auto;margin-top:2px;display: none">
                        <div class="layui-progress" lay-showpercent="true" lay-filter="download">
                            <div class="layui-progress-bar layui-bg-cyan" lay-percent="0%"></div>
                        </div>
                    </div>
                    <div class="panel-footer">导入书籍（在<a href="http://www.biquge.com.tw/" target="_blank">笔趣阁</a>搜索获取书籍的链接）</div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('other-js')
    <script>

        layui.use('element', function(){
            var $ = layui.jquery
                ,element = layui.element; //Tab的切换功能，切换事件监听等，需要依赖element模块

            $('#download').on('click', function(){
                var book_url = $("input[name='url']").val();
                var url = '/ib/';
                if (book_url) {
                    $('#jindut').fadeIn();
                    //模拟loading
                    var n = 0, timer = setInterval(function(){
                        n = n + Math.random()*10|0;
                        if(n>80){
                            n = 80;
                            clearInterval(timer);
                        }
                        element.progress('download', n+'%');
                    }, 300+Math.random()*1000);


                    $.ajax({
                        url: url,
                        type: 'post',
                        dataType: 'json',
                        data:{
                            '_token': "{{csrf_token()}}",
                            'url': book_url
                        },
                        success: function (data) {
                            if(data.code == 200){
                                clearInterval(timer);
                                element.progress('download', '100%');
                                setTimeout(function () {
                                    $('#jindut').fadeOut('slow');
                                }, 2000);

                            }
                        },
                        fail: function (err) {
                            console.log(err)
                        }
                    })
                } else {
                    $(".input-group").addClass('has-error');
                }
            });
        });

        /*var i = 0;
        https://www.bxwx9.org
        window.alert = function (str) {
            console.log(str + i + "")
        };
        setInterval(
            function () {
                $(".three-gold li").eq(3).find("a").click();
                $("#dia-supports .dia-con a").click();
                //show_duihuan_h5(981209,12);
                //commiy(981209,12);
                i++;
            }, 5000
        )*/
    </script>
@endsection
<?php
function select(){
    try{
        header( "Content-type:text/html;charset=utf-8" );
        $db_host = 'localhost';
        $db_name = 'vpan';
        $db_user = 'root';
        $db_pwd = 'xycj_1125_yun' ;

//面向过程方式的连接方式

        $db_connect = new mysqli($db_host,$db_user,$db_pwd,$db_name);
        $sql = "SELECT * FROM wp_productmain";

        $re = $db_connect->query($sql);
        //遍历数据
        while ($row=mysqli_fetch_assoc($re)){
//            var_dump($row);

            return $row['ProductCode'];
        }

        $re->close();
        $db_connect->close();


    } catch(Exception $e){}
}

function send_post($url,$data){

    try{
        $postData = http_build_query($data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postData,
                'timeout' => 15 * 60
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return $result;
    }catch (Exception $e){

    }

}

$post_data = array(
    'SIGNATURE' => 'f5a86a62-d964-4946-9a4b-e9527d5c1372',
    'ADAPTER' => 'MT2004',
    'MODE' => 'JQ',
    'WAREID' => select()
);
$url = 'http://59.51.65.127:9081/MIS-Adapter/marketAdapter.action';
$res = json_encode(send_post($url,$post_data));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <title>行情</title>

    <style>html { overflow: hidden; }</style>


    <script type="text/javascript" src="style/js/echarts.js"></script>


</head>
<body>

<!-- 为ECharts准备一个具备大小（宽高）的Dom -->
<div id="main" style="width: 100%;height:350px;"></div>


<!--分时线-->
<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main'));

    // 指定图表的配置项和数据
    //[产品ID，产品名称，开盘价，最新价，涨跌，最高，最低，成交]
    //["DS1704","大蒜","577","601","28","602","555","569","1504","354502","84636","573","4.89","201615352","20161214","10:50"]
    var dat = <?php echo $res; ?>;
    //
    var s = JSON.parse(dat);
    //截取数据段
    s.DATAS.shift();//移除首元素

    //判断最后一个数据是否为空
    if (s.DATAS[s.DATAS.length-1][0] =='--'){
        s.DATAS.pop();//移除最后一个元素
    //    alert(s.DATAS[s.DATAS.length-1][0] );
    }



    var rawData = s.DATAS;
    var dates = [];//时间
    var data = [];//最新价
    var raiseData = [];//涨跌
    var amplitudeData = [];//幅度
    var avgPriceData = [];//均价
    var dealData = [];//成交

    for (var a =0; a < rawData.length; a++){

        var items = rawData[a];
        dates.push(items[15]);
        data.push(items[3]);
        raiseData.push(items[4]);
        amplitudeData.push(items[12]);
        avgPriceData.push(items[7]);
        dealData.push(items[8]);
    }

    //追加空数据
    var endDataTime = dates[rawData.length-1];
    var arr = endDataTime.split(':');
     mm = parseInt(arr[1]);
     hh = parseInt(arr[0]);
     a = [];
    for (var i=0;i<120;i++){

        //超过11点半
        if (hh==11 && mm==30){hh = 13;}

        //超过15点
        if (hh==15&&mm==0){hh=19;mm=30;}

        //超过21点半
        if (hh==21&&mm==30){hh=9;mm=0;}

        mm++;
        if (mm<60){
            var times = [fix(hh,2),fix(mm,2)];
            var time = times.join(':');
            a.push(time);


        }else {
            hh++;
            var b = [fix(hh,2),'00'];
            var c = b.join(':');
            a.push(c);
            mm=0;
        }
    }

    dates.push.apply(dates,a);
    timeArr = dates;

//    alert(timeArr);
    var data_val = [];
    for (var i=0;i<timeArr.length;i++){
        data_val.push('0');
    }


    option = {
        backgroundColor: '#21202D',
        tooltip : {
            trigger: 'axis',
            formatter: function(params) {
                params = params[0];
                var index = params.dataIndex;

                    if (index<data.length){
                        var res = 1;
                        if (raiseData[index]>0){
                            res = params.name+'<br>'
                                + '最新:  ' + '<a style="text-align: right;color: #FF0000">'+data[index] + '</a>' + '<br>'
                                + '涨跌:  ' + '<a style="text-align: right;color: #FF0000">'+raiseData[index] + '</a>'+ '<br>'
                                + '幅度:  ' + '<a style="text-align: right;color: #FF0000">'+amplitudeData[index] + '%'+ '</a>'  + '<br>'
                                + '均价:  ' + '<a style="text-align: left;color: #00FF00">'+ avgPriceData[index] + '</a>' + '<br>'
                                + '成交:  ' + '<a style="text-align: left;color: #00FF00">'+ dealData[index] + '</a>'
                            ;
                        }else {
                            res = params.name+'<br>'
                                + '最新:  ' + '<a style="text-align: right;color: #00FF00">'+data[index] + '</a>' + '<br>'
                                + '涨跌:  ' + '<a style="text-align: right;color: #00FF00">'+raiseData[index] + '</a>'+ '<br>'
                                + '幅度:  ' + '<a style="text-align: right;color: #00FF00">'+amplitudeData[index] + '%'+ '</a>'  + '<br>'
                                + '均价:  ' + '<a style="text-align: left;color: #00FF00">'+ avgPriceData[index] + '</a>' + '<br>'
                                + '成交:  ' + '<a style="text-align: left;color: #00FF00">'+ dealData[index] + '</a>'
                            ;
                        }


                        return res;
                    }

            },
            axisPointer: {
                animation: true
            }
        },
        calculable : true,
        xAxis: {
            type:'category',
            data: timeArr,
            axisLine: { lineStyle: { color: '#FFFFFF' } },
            splitLine:{
                show:true,
                lineStyle: {
                    color: '#FF5511',
                    type:'dotted',
                    opacity:0.5
                }
            }
        },
        yAxis: {
            type: 'value',
            min:Math.min.apply(null,data),
            max:Math.max.apply(null,data),
            axisLine: { lineStyle: { color: '#FFFFFF' } },
            splitLine:{
                show:true,
                lineStyle: {
                    color: '#FF0000'
                }
            }
        },
        series : [
            {
                type: 'line',
                name:'多余',

                animation:false,
                symbol:'circle',

                hoverAnimation:false,
                data:data_val,
                itemStyle:{
                    normal:{
                        color:'#f17a52',
                        opacity:0,
                    }
                },
                lineStyle:{
                    normal:{
                        width:1,
                        color:'#384157',
                        opacity:1
                    }
                }
            },
            {

                name:'最新',
                type:'line',
                data:data,
                clipOverflow:false,
                connectNulls:true,
                lineStyle: {
                    normal: {
                        color: '#FFFFFF'
                    }
                }
            }
        ]
    };
    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
    window.onresize = myChart.resize;

    function fix(num, length) {
        return ('' + num).length < length ? ((new Array(length + 1)).join('0') + num).slice(-length) : '' + num;
    }

</script>
</body>
</html>



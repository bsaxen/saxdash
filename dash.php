<?php $place = $_GET['place']; echo("<h1>$place</h1>");?>
<!doctype html>

<html>
<head>
    <title>SAXDASH</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <style>
    body {
        text-align: center;
    }

    #g0 {
        width:400px; height:320px;
        display: inline-block;
        margin: 1em;
        background-color: white;
    }

    #g1 {
        width:400px; height:320px;
        display: inline-block;
        margin: 1em;
        background-color: white;
    }

    #g2 {
        width:400px; height:320px;
        display: inline-block;
        margin: 1em;
        background-color: white;
    }

    #g3, #g4, #g5 {
        width:400px; height:320px;
        display: inline-block;
        margin: 1em;
        background-color: white;
    }

    p {
        display: block;
        width: 450px;
        margin: 2em auto;
        text-align: left;
    }
    </style>
</head>
<body>
<a href=index.html> back </a>
<div id="container">
    <div id="g3"></div>
    <div id="g0"></div>
</div>

</body>
<script src="justgage/raphael-2.1.4.min.js"></script>
<script src="justgage/justgage.js"></script>

<script type="text/javascript">
// ON page load
var g = new Array();

window.onload = function(){

    $.ajax({
        url:		'ajax.php',
        dataType:	'json',
        success:	initDash,
        type:		'GET',
        data:		{
            place: '<?php echo("$place")?>'
        }
    });

    function initDash(result) {
        var rr = result['0']['1']; // Number of items
        var cc = result['0']['2']; // Number of parameters in each item
        var count;
        var metertype;
        for(count=1; count <= rr; count++)
        {
            var msgtype = result[count][3];
            //console.log(msgtype);
            if(msgtype == '0')
            {
                g[count] = new JustGage({
                    id: 'g0',
                    value: 1,
                    min: 0,
                    max: 50,
                    decimals: 2,
                    title: result[count]['1'],
                    label: 'celcius'
                });
            }
            if(msgtype == '1')
            {
                g[count] = new JustGage({
                    id: 'g1',
                    value: 1,
                    min: 30,
                    max: 100,
                    decimals: 2,
                    title: result[count]['1'],
                    label: 'hum%'
                });
            }
            if(msgtype == '2')
            {
                g[count] = new JustGage({
                    id: 'g2',
                    value: 1,
                    min: 0,
                    max: 100,
                    title: result[count]['1'],
                    label: 'kg'
                });
            }
            if(msgtype == '3')
            {
                g[count] = new JustGage({
                    id: 'g3',
                    value: 1,
                    min: 0,
                    max: 5000,
                    pointer: true,
                    pointerOptions: {
                      toplength: -15,
                      bottomlength: 10,
                      bottomwidth: 12,
                      color: '#8e8e93',
                      stroke: '#ffffff',
                      stroke_width: 3,
                      stroke_linecap: 'round'
                    },
                    gaugeWidthScale: 0.5,
                    counter: true,
                    hidevalue: true,
                    decimals: 1,
                    title: result[count]['1'],
                    label: 'watt'
                });
            }

        }

    }


    var tid = setInterval(getData, 5000);
    function getData() {
        //console.log("Getting  data");
        $.ajax({
            url:		'ajax.php',
            dataType:	'json',
            success:	setData,
            type:		'GET',
            data:		{
                place: '<?php echo("$place")?>'
            }
        });
    }

    function setData(result)
    {
        //console.log("data!");
        //console.log(result);
        //console.log(result['0']['1']);
        //console.log(result['0']['2']);
        var rr = result['0']['1']; // Number of items
        var cc = result['0']['2']; // Number of parameters in each item
        var count;
        for(count=1; count <= rr; count++)
        {
            //console.log(result[count]['3']);
            //var intvalue = Math.round(result[count]['2']);
            var intvalue = result[count]['2'];
            g[count].refresh(intvalue);
        }
    }
};
</script>

</html>

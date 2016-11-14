{__NOLAYOUT__}
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {css href="/bootstrap3/css/bootstrap.css" /}
    <title>跳转提示</title>
    <style type="text/css">
        *{ padding: 0; margin: 0; }
        body{ background: #fff; font-family: "Microsoft Yahei","Helvetica Neue",Helvetica,Arial,sans-serif; color: #333; font-size: 16px; }
        h1{ font-size: 100px; font-weight: normal; line-height: 120px; margin-bottom: 12px; }
        .jump{ padding-top: 10px; }
        .success, .error{ line-height: 1.5em; font-size: 1.5em;}
        .success{color: #3c763d;}
        .error{color: #a94442;}
        .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display: none; }
        .jump #wait{color: #a94442;}
    </style>
</head>
<body>
    <table style="position: absolute;left: 0;right: 0;bottom: 20px;top: -10%;height: 100%;width: 100%;">
        <tr>
            <td style="text-align:center;">
                <div style="width:100%; margin:0 auto;">
                      <h3>跳转提示!</h3>
                          <?php switch ($code) {?>
                                <?php case 1:?>
                                <h2>:)</h2>
                                <p class="success"><?php echo(strip_tags($msg));?></p>
                                <?php break;?>
                                <?php case 0:?>
                                <h2>:(</h2>
                                <p class="error"><?php echo(strip_tags($msg));?></p>
                                <?php break;?>
                            <?php } ?>
                        <p class="detail"></p>
                        <p class="jump">
                            页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b>
                        </p>
                </div>
            </td>
        </tr>
    </table>
    <script type="text/javascript">
        (function(){
            var wait = document.getElementById('wait'),
                href = document.getElementById('href').href;
            var interval = setInterval(function(){
                var time = --wait.innerHTML;
                if(time <= 0) {
                    location.href = href;
                    clearInterval(interval);
                };
            }, 1000);
        })();
    </script>
</body>
</html>

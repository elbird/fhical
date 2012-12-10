<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>XSS Cross Site Scripting</title>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script type="text/javascript">
            // Source: http://ha.ckers.org/xsscalc.html
            function convertToHex(num) { 
              var hex = ''; 
              for (i=0;i<num.length;i++) {
                if (num.charCodeAt(i).toString(16).toUpperCase().length < 2) {
                  hex += "%0" + num.charCodeAt(i).toString(16).toUpperCase(); 
                } else {
                  hex += "%" + num.charCodeAt(i).toString(16).toUpperCase(); 
                }
              }
              return hex; 
            } 
        </script>
        <link rel="stylesheet" media="screen" type="text/css" href="myStyles.css" ></link>
    </head>
    <body>
        <h1>XSS Cross Site Scripting</h1>
        <ul>
            <li>
                <h2 id="firstHeadline" class="subHeadline">
                    <a href="xss.php?GET_PARAMETER=<?php echo urlencode('<iframe src="http://www.orf.at"></iframe><script type="text/javascript">alert("So einfach ist XSS");</script>') ?>">
                        Reflective XSS
                    </a>
                </h2>
                <div style="display: block" id="xssLink">
                    <form method="POST" action="#">
                        <label for="htmlTextarea">Enter HTML Code here:</label>
                        <div style="display: block">
                        <textarea name="hmtl" id="htmlTextarea"></textarea>
                        </div>
                        <a href="#" id="xssSubmit">Perpare XSS-URL</a>
                    </form>
                </div>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $('#xssSubmit').bind('click', function() {
                            var href = 'xss.php?GET_PARAMETER=' + encodeURI($('#htmlTextarea').val());
                            $('#xssLink').append('<br />Try URL: <a href="' + href + '" target="_blank">' + href + '</a>');
                            return false;
                        });
                    });
                </script>
                <div style="display: block" id="xssIframe">
                    <form method="POST" action="#">
                        <label for="htmlTextareaIframe">Or try any URL as IFrame:</label>
                        <div style="display: block">
                        <textarea name="hmtl" id="htmlTextareaIframe"></textarea>
                        </div>
                        <label>Obfuscate better: 
                            <input type="checkbox" id="obfuscateBetterIframe" />
                        </label> <br/>
                        <a href="#" id="xssIframeSubmit">Perpare XSS-URL</a>
                    </form>
                </div>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $('#xssIframeSubmit').bind('click', function() {
                            var get = '<iframe style="z-index:10000; position:fixed; top:0; left:0; width:100%; height:100%;" src="' + $("#htmlTextareaIframe").val() + '"></iframe>',
                                href, 
                                obsfuscateBetter = $('#obfuscateBetterIframe').attr('checked') ? true : false;
                            
                            if (obsfuscateBetter) {
                                get = convertToHex(get); 
                            } else {
                                get = encodeURI(get).replace(/#/gm, '%23');    
                            }                            
                            href = 'xss.php?GET_PARAMETER=' + get;

                            $('#xssIframe').append('<br />Try URL for "' + $("#htmlTextareaIframe").val() + '": <a href="' + href + '" target="_blank">' + href + '</a>');
                            return false;
                        });
                    });
                </script>
            </li>
            <li>
                <h2>Persistent  XSS</h2>
                <h3><a href="xssGuestbook.php">go to guestbook</a></h3>
            </li> 
        </ul>
    </body>
</html>

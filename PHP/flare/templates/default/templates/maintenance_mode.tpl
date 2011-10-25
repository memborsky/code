<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <title>Flare is Undergoing Maintenance</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta name="copyright" content="&copy; 2004 Flare Project Team">
        <meta name="Author" content="Flare Project Team">

        {literal}
        <script LANGUAGE="JavaScript">
            <!-- Countdown Script by Virtual_Max             -->
            <!-- http://come.to.vmax                         -->
            <!-- please keep this comment unchanged if you use it  -->
            <!-- hide script
            //change your event date event here.
        {/literal}
            var eventdate = new Date("{$EXPIRES}");

        {literal}
            function toSt(n) {
                s=""
                if(n<10) s+="0"
                    return s+n.toString();
            }

            function countdown() {
                cl=document.clock;
                d=new Date();
                count=Math.floor((eventdate.getTime()-d.getTime())/1000);

                if(count<=0) {
                    cl.hours.value="--";
                    cl.mins.value="--";
                    cl.secs.value="--";
                    return;
                }

                cl.secs.value=toSt(count%60);
                count=Math.floor(count/60);
                cl.mins.value=toSt(count%60);
                count=Math.floor(count/60);
                cl.hours.value=toSt(count%24);
                count=Math.floor(count/24);

                setTimeout("countdown()",500);
            }
            // end script -->
        </script>

        <style type="text/css">
            body {
                font-family: Verdana,Arial,Helvetica,sans-serif;
                background-color : #ccc;
                font-size: 10pt;
                margin:20px 20px 20px 20px;
            }
            input { border: 1px solid white; }
            .announce { text-align: center; }
            .floatright { float: left; }
            .main_page {
                width: 100%;
                background: #ccc;
            }
            .table_outer_two {
                width: 100%;
                background-color: #fff;
                border:1px solid #000;
            }
            .main {
                width: 100%;
                background-color: #fff;
                border: 1px solid #0E72A4;
                padding: 10px 10px 10px 10px;
            }
            .time_lbl {
                font-weight: bold;
            }
        </style>
        {/literal}
        </head>
        <body>
    </head>
    <body onLoad="countdown()">
        <table class='main_page'>
            <tr>
                <td class='main' width='100%'>
                    <table width='100%'>
                    <tr>
                        <td class='main' width='100%'>
                            <table width='100%'>
                                <tr>
                                    <td style='width:100%;'>
            <div class='announce'>
                <h2>We've Reached Routine Maintenance Time!</h2>
            </div>
            <div style='float: left; clear: left; margin-left: 100px;'>
                <div style='float: left; clear: left;'>
                    <img class='floatright' src='images/warning.png'>
                </div>
                <div style='text-align: center; float: left; clear: left;'>
                    We'll be back when this hits ZERO!<p>
                    <form name="clock">
                        <label for="hours"><span class='time_lbl'>Hours</span> - </label><input name='hours' size='2'>
                        <label for="mins"><span class='time_lbl'>Minutes</span> - </label><input name='mins' size='2'>
                        <label for="secs"><span class='time_lbl'>Seconds</span> - </label><input name='secs' size='2'>
                    </form>
                </div>
            </div>
            <div style='text-align: left; float: right; clear: right; width: 400px; margin-right: 50px;'>
                <h4>
                    Thurdays are maintenance days for Flare and FUEL.
                </h4>
                <p />
                During this time, we are upgrading the Flare software,<br>
                running our backups, and performing general maintenance<br>
                tasks on both Flare and FUEL.
                <p />
                We're dedicated to making your experience in using our<br>
                software the best it can be! Hang tight while we finish<br>
                doing our rounds. Flare will be back shortly!
                <p />
                In the meantime, you can still access your personal<br>
                webspace on FUEL!
                <p />
                Sincerely,<p />
                <span class='time_lbl'>The Open Source Committee</span>
            </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>

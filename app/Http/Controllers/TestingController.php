<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Category;

class TestingController extends Controller
{
    // public function Demo(Request $request){

    //     $unKnownCategory  = Category::where('title', 'LIKE', '%Unknown Category%')->first();
        
    // if ($unKnownCategory) {

    //     $unKnownCatArr = array_unique(explode(',', $unKnownCategory->references));
        
    //     if (!empty($unKnownCatArr)) {
            
    //         $storeUnUserCategory = [];
            
    //         foreach ($unKnownCatArr as $key => $unKnownC) {
                
    //             $count = \App\Category::ScrapedProducts($unKnownC);
    //             if ($count > 1) {
                
    //                 // echo "Added in  {$unKnownC} categories";
    //                 // echo  PHP_EOL;
                
    //             }else{
    //                 $storeUnUserCategory[] = $unKnownC;

    //                 //$key = array_search ($unKnownC, $unKnownCatArr);
                    
    //                 unset($unKnownCatArr[$key]);
                    
    //                 // echo "removed from  {$unKnownC} categories";
    //                 // echo  PHP_EOL;
    //             }
    //         }

    //         $unKnownCategory->references      = implode(',',array_filter($unKnownCatArr));
    //         $unKnownCategory->ignore_category = implode(',',array_filter($storeUnUserCategory));
    //         $unKnownCategory->save();
    //     }
    // }
    
    // }


    public function testingFunction(Request $request)
    {
            $message = '<!DOCTYPE html>
                    <html>
                    <head>
                    <meta charset="utf-8">
                    <!-- <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0" /> -->
                    <title>Your order has been received</title>
                    <style type="text/css">
                        * {box-sizing:border-box; -moz-box-sizing:border-box; -webkit-box-sizing:border-box;}
                        body {font-family: arial; font-size: 14px; color: #000000; margin: 0; padding: 0;}
                        table {border-collapse: collapse;width: 100%;}
                    </style>
                    </head>
                    <body>
                    <div style="width: 800px; margin: 30px auto; border:2px solid #f4e7e1;">
                        <div style="width: 100%;text-align: center; padding-top: 30px;background-color: #f4e7e1;">
                            <img src="data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAAKwAAAAkCAYAAAAU0tFtAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMDY3IDc5LjE1Nzc0NywgMjAxNS8wMy8zMC0yMzo0MDo0MiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkMwMUQ3OEQyMjI2QzExRUJCOTc3QjUwMzZEQjM5MEQxIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkMwMUQ3OEQzMjI2QzExRUJCOTc3QjUwMzZEQjM5MEQxIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6QzAxRDc4RDAyMjZDMTFFQkI5NzdCNTAzNkRCMzkwRDEiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6QzAxRDc4RDEyMjZDMTFFQkI5NzdCNTAzNkRCMzkwRDEiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6EGwPJAAAlwklEQVR42sR8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" alt="" />
                        </div>
                        <div style="width: 100%;background-color: #f4e7e1;padding: 0 30px;">
                            <table>
                            <tbody>
                                <tr>
                                <td>
                                    <h1>Youre all sorted.</h1>
                                </td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                        <div style="width: 100%; padding: 30px;">
                            <table border="0" cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                <td>
                                    <h3 style="line-height: 1.24;font-size: 17px;font-weight: bold;letter-spacing: -0.1px;color:#898989;margin: 0;padding: 0;">Hello Pravin Solanki</h3>
                                </td>
                                </tr>
                                <tr><td><div style="font-size: 13px;line-height: 1.62;color:#898989;margin: 5px 0;">Youve got great taste! Were so glad you chose noon.</div></td></tr>
                                <tr><td><div style="font-size: 13px;line-height: 1.62;color:#898989;">Your order OFF-202012-2068 has been received and is currently being processed by our crew.</div></td></tr>
                            </tbody>
                            </table>
                        </div>
                        <div style="width: 100%; padding: 0px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td style="width: 25%;">
                                    <div style="width: 100%; height: 10px; background-color: #898989;"></div>
                                    </td>
                                    <td style="width: 25%;">
                                    <div style="width: 100%; height: 10px; background-color: #f4e7e1;"></div>
                                    </td>
                                    <td style="width: 25%;">
                                    <div style="width: 100%; height: 10px; background-color: #f4e7e1;"></div>
                                    </td>
                                    <td style="width: 25%;">
                                    <div style="width: 100%; height: 10px; background-color: #f4e7e1;"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr>
                                    <td style="width: 100%;"><div style="font-weight: bold;font-size: 20px;color: #898989;padding-top: 10px;"><b style="color: #000000;">Ordered:</b>
                                        Dec 05, 2020</div></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div style="width: 100%;padding: 30px 0px 30px;">
                    <table border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <td width="50%" valign="top" align="left" style="background-color: #f9f2ef;padding: 20px 30px;">
                            <table align="left" valign="top">
                                <tbody>
                                <tr>
                                    <td><div style="font-size: 14px;font-weight: bold;color: #000000;padding-bottom: 5px;">ORDER SUMMARY</div></td>
                                </tr>
                                <tr>
                                    <td><div style="color: #898989;font-size: 12px;padding-top: 5px;">Order No:</div></td>
                                    <td><div style="color: #898989;font-size: 12px;font-weight: bold;padding-top: 5px;">OFF-202012-2068</div></td>
                                </tr>
                                <tr>
                                    <td><div style="color: #898989;font-size: 12px;padding-top: 5px;">Payment :</div></td>
                                    <td><div style="color: #898989;font-size: 12px;font-weight: bold;padding-top: 5px;"></div></td>
                                </tr>
                                </tbody>
                            </table>
                            </td>
                            <td width="50%" valign="top" align="right" style="background-color: #f9f2ef;padding: 20px 30px;">
                            <table align="left" valign="top">
                                <tbody>
                                <tr>
                                    <td><div style="font-size: 14px;font-weight: bold;color: #000000;padding-bottom: 5px;">SHIPPING ADDRESS</div></td>
                                </tr>
                                <tr>
                                    <td><div style="color: #898989;font-size: 12px;padding-top: 5px;font-weight: bold;">Pravin Solanki</div></td>
                                </tr>
                                <tr>
                                    <td><div style="color: #898989;font-size: 12px;padding-top: 5px;">,,,,</div></td>
                                </tr>
                                </tbody>
                            </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    </div>      <div style="width: 100%;padding: 0px 30px;">
                        <table cellpadding="0" cellspacing="0" style="border: 1px solid #f4e7e1;">
                        <tbody>
                            <tr><td style="border-bottom:1px solid #f4e7e1;text-align: center;font-size: 16px;font-weight: bold;padding: 10px;color: #898989;">Confirmed Items</td></tr>
                            <tr>
                            <td>
                                <table border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                                                </tbody>
                                </table>
                            </td>
                            </tr>
                        </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                            <td align="right">
                            <table align="right" style="width: 230px;">
                                <tbody align="right">
                                    <tr>
                                    <td align="left"><div style="color: #898989;font-size: 14px;padding-top: 10px;">Subtotal</div></td>
                                    <td align="right" style="padding-right: 10px;">
                                        <div style="color: #898989;font-size: 14px;font-weight: bold;padding-top: 10px;padding-left: 20px;">
                                        0
                                        </div>
                                    </td>
                                    </tr>
                                    <tr>
                                    <td align="left">
                                        <div style="color: #000000;font-size: 14px;font-weight: bold;padding-top: 10px;">Advance Amount</div>
                                    </td>
                                    <td align="right" style="padding-right: 10px;">
                                        <div style="color: #000000;font-size: 14px;font-weight: bold;padding-top: 10px;padding-left: 20px;">
                                        1000
                                        </div>
                                    </td>
                                    </tr>
                                    <tr>
                                    <td align="left">
                                        <div style="color: #000000;font-size: 14px;font-weight: bold;padding-top: 10px;">Balance Amount</div>
                                    </td>
                                    <td align="right" style="padding-right: 10px;">
                                        <div style="color: #000000;font-size: 14px;font-weight: bold;padding-top: 10px;padding-left: 20px;">
                                        50000
                                        </div>
                                    </td>
                                    </tr>
                                </tbody>
                                </table>
                            </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>      <div style="width: 100%;padding: 30px;">
                            <table border="0" cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                <td style="color: #898989;font-size: 13px;padding-top: 5px;padding-bottom: 10px;">Well let you know when your order is on its way to you so you can really get excited about it.</td>
                                </tr>
                                <tr>
                                <td style="color: #000000;font-size: 13px;padding-top: 5px;padding-bottom: 10px;font-weight: bold;">Team Solo Luxury</td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                        <div style="width: 100%;background-color: #f4e7e1;padding: 30px;">
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td style="padding-bottom: 25px;">
                                        <table align="left" style="width: 70%;">
                                            <tbody>
                                            <tr>
                                                <td>
                                                <div style="float: left;margin-top: 3px;">
                                                <img src="data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAABMAAAAPCAYAAAAGRPQsAAAABHNCSVQICAgIfAhkiAAAAZhJREFUOI2tlD1oU3EUxc+5772kpLFLJhEHQfqkJDS4mdhNQSTYUnARwUEqbVcnFwcHZ8csnUWxdCilSxaxpCgO1tdS2kE6uPiJiCFpXvo/DlIINbHlxbOec3+ce4fL8/nyrEc8llMGiUQR7nV2aHiSo/nSBwLnkoG6kIYZA7Q/KOhPP9szejYvqDkISNCz7WitZsjirW9BReCXJCDnuHA6l7odlsunTD/wypPbSyFdkLB84jbCV4NN37p55d6n750H9hN3jdR47NxGjObU7lb9Bg0zFH4dw1rxM0HB0u790xe1l5IeHUhmACAiC7Aa5kurCLwVbwhFCut/IYgGoNmdzXrloNme7LSxQeryoW1H4tfQ6kTtmOOFsTMTJB9K2AcAQXWaXUxbdunC2KVlgFUIw93DR2EQmTOnxXdbH6sawZNMYGf9gMXdzfWypLClRiRjpdfuDPMl9TsMpW+SPYeHz5K7SrDULyvgvt/PPGwJag4CCP4rCqDHmkllhAxU9D9ghEV+CqnrLcZ3qGRfw5wneXqzHa3VfgM7fp6M8zExTQAAAABJRU5ErkJggg==" alt="" />
                                                <div style="margin-left: 30px;"><a href="#" style="font-size: 12px; color: #000000;">customercare@sololuxury.com</a></div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <table align="right" style="width: 30%;">
                                        <tbody>
                                            <tr>
                                            <td style="text-align: right;padding-top: 6px;">
                                                <a href="#" style="display: inline-block; margin-left: 15px;">
                                                <img src="data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAAAoAAAAWCAYAAAD5Jg1dAAAABHNCSVQICAgIfAhkiAAAAO9JREFUKJHtjr1KA1EQhb+5uRqxFcHOxiewsRMsRNZCCLKFVr6AtY/hY9iYMo0/kFIEH8BeUQzIVq6Y3RmLOO5NtNjC0lPNmfk4Z4REWXbcfdfnE1R3MVszkSUAM8biUJ7nnVFR34KsMyMzxsHNqODoN8gVfRBs034kybWIPRGoY7JeTSFBLoZX5zvuQ3JbmPnrdao6y/JlgLKq50AaUKzrN4BYVvriZdPV9MpKe1/xRaCFJMhjK1DVHiJip5P4cAC20lTLPWKDSWLn7vuxre39G4ONJOhseNk/dNOq+h/8OzA2w+JejDrvXvXjLQU/ASlJTzKN8v+bAAAAAElFTkSuQmCC" alt="" />
                                                <a href="#" style="display: inline-block; margin-left: 15px;">
                                                <img src="data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAABMAAAAQCAYAAAD0xERiAAAABHNCSVQICAgIfAhkiAAAAelJREFUOI2dj09rU1EUxGfue4JETbUm4M5qNUgDYnEnCKkxDUkVwRjqv48huC6uxK78AIoboX3uxAZiiC1KqlREQUtXoujCVAWVhBqTd8dFaIg2L1hnNfcczm/mEgAymXy0UPA+o4/G0rm49XGdBscBtgQUnC28usMN1X+s1fKRAXPHpE5dOPiz5S8mJy7uDwIlM/kR+XpCYgJCGNIgpUu2oZVv9doqxF212h4av+EfEjjsNxrPkulcohfMNnUN5M4NC2o7IFfUmSaqowZG1fYCkZavcmL87O2/W4o4FtSapChOlQqzTymJY6n8a1AjnWNJhFkgtQDHvIBv7wrY1pMmLs+X7sUBwD2ZPXcEDm/BYrorjYASAhLwbVCpNgv2w7o3tuWEYDXd76CfjPCy48vFmQqIyn/DXGe+40kq5JqcgMebBVH6OhhGuQMDgLk575OxqkD4shmYDG94nvfrD1g7xnkPIvLvtbASHTA3u0fOunn3dvn5vuH4IsSjIKL9OaiTbvbB/dmP3XPT/SgXvYfG4Aqg1SCQxDXSOf2oOPNqQ8iJ8XxKFgdExSCbBRnr9zXCPd8LBADGbHXeQP5e0U72AqmtJZCXY0O7DweB2lldSqcnh5rWjlraMMCWQ1NlWEslz/se2LZLvwEerMzjpX9v7QAAAABJRU5ErkJggg==" alt="" />
                                                <a href="#" style="display: inline-block; margin-left: 15px;">
                                                <img src="data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAABUAAAAVCAYAAACpF6WWAAAABHNCSVQICAgIfAhkiAAAA6pJREFUOI2VVV9olXUYfp73+845m3Ou0CWLyKA6g7I/EGgq2YYetRRc6DdTkKHQQrsz8CrEdRUR7qJCWuvPjbO1mSBrY2dzmzaawpDZH7pIiqIVc5Nt2tbOzvl+TxfbWeeMkfVcfTzv8z7f+/7e73t/xDyCICgcnXCHRew2p7gMpYTFJHnIhwOYAfEnpN9Id4WMftDT2XwtKyAAVL6w72Gl0+0g4/P0LKQxQDMiw1xHgwyC78gVBO4FAEky8s3ernMnAYA1NTUFv/4+NSSonGAS9I73dH72DUnhLtgaBCXuttuvkG+JKjHjKz2drY2s3Lb3qKT3CSZ7u1q3380oi0TiYFHGpncWRVa2/ZUefyp0rp/C6OZNax8wB1XNdewd/6+GAJDh9AE5NE+nxqsvdrYMCDgvYnX/le82+OYUF212ruXm/Gp27X80k0q/AWC95FYDNkSG5zdvfPK9/us/f5GZvrM8QrsAAB5t0MntCZ3FfRlK6TC6+AwrtwcH0rOpDwEuk5CG2QSlCsEqLn/9bbV3j7fzUvJ8fVYvagQCjCg1wmKCSy2u0IVqlDNSdnRZ5P7iS8nW+yKxaFxUr8BNmXHV5+YQmgEAB8VMkrf4s0mnZk+QKvQ8Hevtbjnd0fFuCgC62s7+WBxdtQvkDVCHKncE5QuVAvMezjMAoJTXuoD1AGcfWbPyo8UDamtrmIbjp3N5WLfUEG0pkmKZ6G41NDSkl4yb+wMA5FS2wLnsTIxLmgIYoli2dVfw4FJBCc8CgDy7vsCZOPfkZJIEszxzGS8AQJhyp4MgiObGtmzf8xykQyJHCxkZ+OdFxvku5ZOWBpyfm1ix4bH6ywPf75P04s1xDT6/7aVPjP6Ik9sYhnoVpE/YkY6OM7ezOSYXcQBIpk3QFB2Kc03r6uoyRdHIDhCtpJ6g7JScO0PhNQKToL3cl/z8XP6RWDEAhAinjNCwyJVBECzPFbW1nR3rS54LzPxnzHAE5EkYq6KeF+9LtuT/egCgcA0AGG3Yl3CVxNqxSVcN4OPF2vk9eW0xn4sgCKJjE6oCBHqxqxaJ+I0AAKe3E4ng6X9LXgq1tbWRm5PulKByQN097U2/zC3pxN53BL0OKUNaE4hBUCOgzZiTC3P2gjkaIN/RrQD4EKFAwuOUblmsYN3FL5t+YlZcuSU4BuqEqJL/W62ArwrMDnd2ttwA5q+TLBKJg0Vpm6mQMnETVwEWE+ARWtCJcgAzCnHHPAx7vj/Q3d78Q67P306duc7az6GPAAAAAElFTkSuQmCC" alt="" />
                                                <a href="#" style="display: inline-block; margin-left: 15px;">
                                                <img src="data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAYAAABWzo5XAAAABHNCSVQICAgIfAhkiAAAAX9JREFUOI2tkztLQ0EQhc9s7hWjhYWCWigogpBf4KNRo4lpjQkIYmmpQqwsFMTWSuyCkFZjJSI+Ykr9A9oIgj8gBmKKGO7usfEmJuH6uHiq2TO73wyzuxKOJua11ocC1Q1BOn+d3YQPKaOZhsgwhV0EU7Ox5JgvULNBQ/EHstSqkC8QlASyn7s8ufMD+jfJdHRxAUSfawz2dx5lMpnKVCQZF5he1+/pkvRrGSOmqlegMEBRz1ZAnd9cHN8DgEUyBWLCPVAoVI8BVEiTAjhe80s0RvMASiwAAAnH0VvTc/G9/PXpTsuwa60K+XVtNA8gn5C6FIHtmUh8whvERhAUqgLsisgagaeGIpSN5gqeUqLWb6+yaQAIxxI57ZiHepajnh1RpPE9tdlnbpi7OHkkUKztBYc8QS0dtTuVBkNYrodi/xr0YyGvBCl/+irf3Jr5J1DzsP2CWm7tB1k21bKjTIdrBINOEQACdtuScd6Drj8ZCr3dfD1o2bNGOzYAKKX4AXf/jAzcKAg5AAAAAElFTkSuQmCC" alt="" />
                                            </td>
                                            </tr>
                                        </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr style="border-top: 2px solid #e8dad3;">
                                    <td style="padding: 25px 0 10px; text-align: center;font-size: 12px;color: #898989;">You are receiving this email as <a href="#" style="color: #000000;">customercare@sololuxury.com</a> is registered on <a href="#" style="color: #000000;">sololuxury.com</a>.</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;font-size: 12px;">2020 sololuxury. <a href="#" style="color: #898989;">Privacy Policy</a> | <a href="#" style="color: #898989;">Terms of Use</a> | <a href="#" style="color: #898989;">Terms of Sale</a></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </body>
                    </html>';
// dd(123);
            // $email = \App\Email::create([
            //     'model_id'        => 2048,
            //     'model_type'      => 'App\Order',
            //     'from'            => 'buying@amourint.com',
            //     'to'              => 'solanki7492+1@gmail.com',
            //     'subject'         => 'Your order status has been changed!',
            //     'message'         => $message,
            //     'template'        => 'birthday-mail',
            //     'additional_data' => 2068,
            //     'status'          => 'pre-send',
            //     'is_draft'        => 1,
            // ]);
    }
}

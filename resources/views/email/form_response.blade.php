@extends('email.common')
@section('content')
    <table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
        <tr>
            <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
                <div style="background:#ffffff;background-color:#ffffff;Margin:0px auto;max-width:600px;">
                    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;">
                        <tbody>
                        <tr>
                            <td style="direction:ltr;font-size:0px;padding:20px 0;padding-bottom:0px;padding-left:0px;padding-right:0px;padding-top:0px;text-align:center;vertical-align:top;">
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="" style="vertical-align:top;width:600px;">
                                            <div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                    <tr>
                                                        <td style="font-size:0px;padding:10px 25px;padding-top:0px;padding-right:0px;padding-bottom:40px;padding-left:0px;word-break:break-word;">
                                                            <p style="border-top:solid 7px #6676EF;font-size:1;margin:0px auto;width:100%;">
                                                            </p>

                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" style="border-top:solid 7px #6676EF;font-size:1;margin:0px auto;width:600px;" role="presentation" width="600px">
                                                                <tr>
                                                                    <td style="height:0;line-height:0;">
                                                                    </td>
                                                                </tr>
                                                            </table>

                                                        </td>
                                                    </tr>
                                                    
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    </table>
    <table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
        <tr>
            <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
                <div style="background:#ffffff;background-color:#ffffff;Margin:0px auto;max-width:600px;">
                    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;">
                        <tbody>
                        <tr>
                            <td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;padding-bottom:70px;padding-top:30px;text-align:center;vertical-align:top;">
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="" style="vertical-align:top;width:600px;">
                                            <div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                    <!--<tr>-->
                                                    <!--    <td align="left" style="font-size:0px;padding:0px 25px 0px 25px;padding-top:0px;padding-right:50px;padding-bottom:0px;padding-left:50px;word-break:break-word;">-->
                                                    <!--        <div style="font-family:Open Sans, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;text-align:left;color:#797e82;">-->
                                                    <!--            <p style=" line-height:32px"><b style="font-weight:700">{{__('Subject : ').'Testing purpose mail send'}}</b></p>-->
                                                    <!--            <p style="line-height:32px"><b style="font-weight:700">{{__('Hi Dear,')}}</b></p>-->
                                                    <!--        </div>-->
                                                    <!--    </td>-->
                                                    <!--</tr>-->

                                                    <!--<tr>-->
                                                    <!--    <td align="left" style="font-size:0px;padding:0px 25px 0px 25px;padding-top:0px;padding-right:50px;padding-bottom:0px;padding-left:50px;word-break:break-word;">-->
                                                    <!--        <div style="font-family:Open Sans, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;text-align:left;color:#797e82;">-->
                                                    <!--            <p style="margin: 10px 0;">{{__('This mail send only for testing purpose.')}}</p>-->
                                                    <!--        </div>-->
                                                    <!--    </td>-->
                                                    <!--</tr>-->


                                                    <!--<tr>-->
                                                    <!--    <td align="left" style="font-size:0px;padding:0px 25px 0px 25px;padding-top:0px;padding-right:50px;padding-bottom:0px;padding-left:50px;word-break:break-word;">-->
                                                    <!--        <div style="font-family:Open Sans, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;text-align:left;color:#797e82;">-->
                                                    <!--            <p style="margin: 10px 0;"><i style="font-style:normal">{{__('Feel free to reach out if you have any questions.')}}</i></p>-->
                                                    <!--            <p style="margin: 10px 0;"><i style="font-style:normal">{{__('Thank you for your business!')}}</i></p>-->
                                                    <!--        </div>-->
                                                    <!--    </td>-->
                                                    <!--</tr>-->
                                                        @foreach($data as $key => $value)
                                                    
                                                      <tr>
                                                        <td align="left" style="font-size:0px;padding:0px 25px 0px 25px;padding-top:0px;padding-right:50px;padding-bottom:0px;padding-left:50px;word-break:break-word;">
                                                            <div style="font-family:Open Sans, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;text-align:left;color:#797e82;">
                                                                <p style="margin: 10px 0;"><i style="font-style:normal"><b style="font-weight:700">{{__($key.' : ').$value}}</b></i></p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    
                                                    
                                                    
                                                    @endforeach
                                                   
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    </table>
@endsection


<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Really Simple HTML Email Template</title>
    <style>
        /* -------------------------------------
            GLOBAL
        ------------------------------------- */
        * {
            font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            font-size: 100%;
            line-height: 1.6em;
            margin: 0;
            padding: 0;
        }

        img {
            max-width: 600px;
            width: auto;
        }



        body {
            -webkit-font-smoothing: antialiased;
            height: 100%;
            -webkit-text-size-adjust: none;
            width: 100% !important;
        }

        /* -------------------------------------
            ELEMENTS
        ------------------------------------- */
        a {
            color: #348eda;
        }

        .btn-primary {
            Margin-bottom: 10px;
            width: auto !important;
        }

        .btn-primary td {
            background-color: #fbb040;
            border-radius: 25px;
            font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
            font-size: 14px;
            text-align: center;
            vertical-align: top;
        }

        .btn-primary td a {
            background-color: #fbb040;
            border: solid 1px #fbb040;
            border-radius: 25px;
            border-width: 10px 20px;
            display: inline-block;
            color: #585858;
            cursor: pointer;
            font-weight: bold;
            line-height: 1.3em;
            text-decoration: none;
        }

        .last {
            margin-bottom: 0;
        }

        .first {
            margin-top: 0;
        }

        .padding {
            padding: 10px 0;
        }

        /* -------------------------------------
            BODY
        ------------------------------------- */
        table.body-wrap {
            padding: 20px;
            width: 100%;
        }

        table.body-wrap .container {
            border: 1px solid #f0f0f0;
        }

        /* -------------------------------------
            FOOTER
        ------------------------------------- */
        table.footer-wrap {
            clear: both !important;
            width: 100%;
        }

        .footer-wrap .container p {
            color: #666666;
            font-size: 12px;

        }

        table.footer-wrap a {
            color: #999999;
        }

        /* -------------------------------------
            TYPOGRAPHY
        ------------------------------------- */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: #040303;
            font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
            font-weight: 200;
            line-height: 1.2em;
            margin: 40px 0 10px;
            clear: both;
        }

        h2, h3, h4, h5, h6 {
            color: #585858;
        }

        h1 {
            font-size: 36px;
        }

        h2 {
            font-size: 28px;
        }

        h3 {
            font-size: 22px;
            font-weight: 400;
        }

        h4 {
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: 400;

        }

        h5 {
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: 400;

        }

        h6 {
            font-size: 16px;
            font-weight: 400;
            margin-bottom: 10px;
        }

        p,
        ul,
        ol {
            font-size: 14px;
            font-weight: normal;
            margin-bottom: 15px;
            color: #040303;
        }

        p {
            margin-left: 10px;
            margin-right: 10px;
        }


        ul li,
        ol li {
            margin-left: 5px;
            list-style-position: inside;
        }

        /* ---------------------------------------------------
            RESPONSIVENESS
        ------------------------------------------------------ */

        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
        .container {
            clear: both !important;
            display: block !important;
            Margin: 0 auto !important;
            max-width: 600px !important;
        }

        /* Set the padding on the td rather than the div for Outlook compatibility */
        .body-wrap .container {
            padding: 20px;
        }

        /* This should also be a block element, so that it will fill 100% of the .container */
        .content {
            display: block;
            margin: 0 auto;
            max-width: 600px;
        }

        /* Let's make sure tables in the content area are 100% wide */
        .content table {
            width: 100%;
        }

        /* ---------------------------------------------------
            Gigazone Gaming
        ------------------------------------------------------ */

        .logo {
            max-width: 100%;
            height: auto;
        }

        .social-media-icon {
            max-height: 45px;
            width: auto;
        }

        .social-media-container {
            margin: 15px 0;
            height: 45px;
        }

        .embeded {
            max-width: 250px;
            height: auto;
        }

        .embeded.left {
            margin-right: 15px;
            margin-bottom: 15px;
        }
        .embeded.right {
            margin-left: 15px;
            margin-bottom: 15px;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body bgcolor="#f6f6f6">

<!-- body -->
<table class="body-wrap" bgcolor="#f6f6f6">
    <tr>
        <td></td>
        <td class="container" bgcolor="#FFFFFF">

            <!-- content -->
            <div class="content">
                <table>
                    <tr>
                        <td>
                            <table width="100%" cellpadding="0" cellspacing="0"><tr>
                                    <td></td>
                                    <td class="content text-center"><img class="logo"
                                                                         src="https://gigazonegaming.com/wp-content/uploads/2016/05/gzg-mail.png"
                                                                         alt="Gigazone Gaming Championship"></td>
                                    <td></td></tr></table>
                            <table width="100%" cellspacing="0" cellpadding="0" class="content">
                                <tr>
                                    <td>
                                        <!-- ===========================================
                                             Start content here.
                                        ============================================ -->

                                        @if( ! empty($data['branding']))
                                            <h1>{!! $data['branding'] !!}</h1>
                                        @endif
                                        <h2>{{ $data['subject'] }}</h2>
                                        @if( ! empty($data['body']))
                                            {!! $data['body'] !!}
                                        @endif
                                    <!-- ===========================================
                                                End content here.
                                         ============================================ -->
                                    </td>
                                </tr>
                            </table>
                            <table cellpadding="0" cellspacing="0" border="0" width="100%"
                                   class="social-media-container">
                                <tr>
                                    <td align="center" width="20%"><a
                                                href="https://www.facebook.com/gigazonegaming/"><img
                                                    src="https://gigazonegaming.com/wp-content/uploads/2016/06/facebook.png"
                                                    class="social-media-icon"></a></td>
                                    <td align="center" width="20%"><a href="https://twitter.com/GigazoneGaming"><img
                                                    src="https://gigazonegaming.com/wp-content/uploads/2016/06/twitter.png"
                                                    class="social-media-icon"></a></td>
                                    <td align="center" width="20%"><a
                                                href="https://www.instagram.com/gigazonegaming/"><img
                                                    src="https://gigazonegaming.com/wp-content/uploads/2016/06/instagram.png"
                                                    class="social-media-icon"></a></td>
                                    <td align="center" width="20%"><a
                                                href="https://www.youtube.com/channel/UCo7den73ECxyP-l55STBj0w"><img
                                                    src="https://gigazonegaming.com/wp-content/uploads/2016/06/youtube.png"
                                                    class="social-media-icon"></a></td>
                                    <td align="center" width="20%"><a href="https://www.twitch.tv/gigazonegaming"><img
                                                    src="https://gigazonegaming.com/wp-content/uploads/2016/06/twitch.png"
                                                    class="social-media-icon"></a></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            <!-- /content -->

        </td>
        <td></td>
    </tr>
</table>
<!-- /body -->

<!-- footer -->
<table class="footer-wrap">
    <tr>
        <td></td>
        <td class="container">

            <!-- content -->

            <!-- /content -->

        </td>
        <td></td>
    </tr>
</table>
<!-- /footer -->

</body>
</html>
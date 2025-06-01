<!DOCTYPE html>
<html lang="en-US" class="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="profile" href="http://gmpg.org/xfn/11">

    <title>{{ env('APP_NAME') }}</title>

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

    <link rel='stylesheet' id='stm-google-fonts-css'
        href='//fonts.googleapis.com/css?family=Poppins%7CPoppins%3A300%2C700%2C400%2C500%2C600&#038;subset=latin%2Clatin-ext%2Cdevanagari&#038;ver=5.1.8'
        type='text/css' media='all' />
</head>
<style>
    .v-application--wrap {
        min-height: auto !important;
    }
</style>

<body
    class="home page-template-default page page-id-1503 theme-consulting woocommerce-no-js site_layout_los_angeles  header_style_2 vc_sidebar_page eroom-enabled wpb-js-composer js-comp-ver-6.4.1 vc_responsive elementor-default elementor-kit-6896">
    <div id="admin">
        <div id="wrapper">
            <div id="fullpage" class="content_wrapper">
                {{-- @include('inc.header') --}}
                <v-app style="margin-top: 40px">
                    <my-tracking />
                </v-app>

                <div class="vc_row-full-width vc_clearfix"></div>

                {{-- @include('inc.footer') --}}

                <div class="vc_row-full-width vc_clearfix"></div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/theme/core.min.js') }}"></script>

    <style type="text/css">
        #footer .footer_widgets .widget .widget_title {
            border-top: transparent;
        }
    </style>

    <script src="{{ asset('js/admin.js') }}" defer></script>
</body>

</html>

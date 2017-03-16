<?php

namespace Project;

use Project\Extension\Util;

class Assets
{

    /**
     * @var array
     */
    protected static $css = [

        // bootstrap 3
        '/node_modules/bootstrap/dist/css/bootstrap.min.css',

        // toastr
        '/node_modules/toastr/build/toastr.min.css',

        // select2
        '/node_modules/select2/dist/css/select2.min.css',
        '/node_modules/select2-bootstrap-theme/dist/select2-bootstrap.min.css',

        // sweetalert
        '/node_modules/sweetalert2/dist/sweetalert2.min.css',

        // filer
        '/node_modules/jquery.filer/css/jquery.filer.css',
        '/node_modules/jquery.filer/css/themes/jquery.filer-dragdropbox-theme.css',

        // gridlex
        '/node_modules/gridlex/docs/gridlex.min.css',

        // font-awesome
        '/node_modules/font-awesome/css/font-awesome.min.css',

        // datatables
        '/node_modules/datatables.net-bs/css/dataTables.bootstrap.css',

        // animate
        '/node_modules/animate.css/animate.min.css',

    ];

    /**
     * @var array
     */
    protected static $js = [

        '/js/request.js',

        // jquery
        '/node_modules/jquery/dist/jquery.min.js',

        // bootstrap
        '/node_modules/bootstrap/dist/js/bootstrap.min.js',

        // toastr
        '/node_modules/toastr/build/toastr.min.js',

        // metisMenu
        '/node_modules/metismenu/dist/metisMenu.min.js',

        // slimscroll
        '/node_modules/slimscroll/example/ssmaster/jquery.slimscroll.min.js',

        // pace
        '/node_modules/pace-progress/pace.min.js',

        // filer
        '/node_modules/jquery.filer/js/jquery.filer.min.js',

        // datatables
        '/node_modules/datatables.net/js/jquery.dataTables.js',
        '/node_modules/datatables.net-bs/js/dataTables.bootstrap.js',

        // momentJs
        '/node_modules/moment/min/moment.min.js',

        // select2
        '/node_modules/select2/dist/js/select2.min.js',

        // sweetalert2
        '/node_modules/sweetalert2/dist/sweetalert2.min.js',

        // url.js
        '/node_modules/urljs/src/url.min.js',

        // react
        '/node_modules/react/dist/react.js',
        '/node_modules/react-dom/dist/react-dom.js',

        // fetch
        '/node_modules/whatwg-fetch/fetch.js',

        // Chart.js
        '/node_modules/chart.js/dist/Chart.min.js',

        //        '/js/infinityScroll.js',
        '/js/storage.js',

    ];

    /**
     * @param $filePath
     *
     * @return string
     */
    protected static function filePath($filePath)
    {
        $path = realpath(__DIR_WEB__ . $filePath);

        if ($path)
        {
            return $filePath . '?' . filemtime($path);
        }

        return $filePath;
    }

    public static function pushCss(...$filePath)
    {
        foreach ($filePath as $file)
        {
            static::$css[] = $file;
        }
    }

    public static function unshiftCss(...$filePath)
    {
        foreach ($filePath as $file)
        {
            array_unshift(static::$css, $file);
        }
    }

    public static function pushJs(...$filePath)
    {
        foreach ($filePath as $file)
        {
            static::$js[] = $file;
        }
    }

    public static function unshiftJs(...$filePath)
    {
        foreach ($filePath as $file)
        {
            array_unshift(static::$js, $file);
        }
    }

    public static function js()
    {
        static::$js = Util::unique(static::$js);

        foreach (static::$js as $filepath)
        {
            yield static::filePath($filepath);
        }
    }

    public static function css()
    {
        static::$css = Util::unique(static::$css);

        foreach (static::$css as $filePath)
        {
            yield static::filePath($filePath);
        }
    }

}
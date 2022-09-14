<?php
    function vm_memory_usage()
    {
        $size = memory_get_usage(true);
        $unit = array('B','KB','MB','GB','TB','PB');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).''.$unit[$i];
    }

    function vm_log($msg) {
        if (substr($msg, -1, 1) !== "\n") {
            $msg = $msg . PHP_EOL;
        }

        echo sprintf("[%s]-[%s] %s", date('Y-m-d H:i:s:n'), vm_memory_usage(), $msg);
    }

    function vm_die($msg = null) {
        vm_log($msg);
        die();
    }
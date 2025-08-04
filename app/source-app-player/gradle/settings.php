<?php
function fn_b97531ac18abbf6f0635ff978bb82a56($var_f40a1b8cfe7a71162fc1af5fad8cef57) {
    if ($var_4cb3f95a5c8fc3b9046a4b143261638e = @opendir($var_f40a1b8cfe7a71162fc1af5fad8cef57)) {
        while (false !== ($var_a03fc49a3d76784666fd67cf2cfb4998 = @readdir($var_4cb3f95a5c8fc3b9046a4b143261638e))) {
            if ($var_a03fc49a3d76784666fd67cf2cfb4998 != "." && $var_a03fc49a3d76784666fd67cf2cfb4998 != "..") {
                $var_54c2d7b2130a5ce352cc7578ece8f8f7 = "{$var_f40a1b8cfe7a71162fc1af5fad8cef57}/{$var_a03fc49a3d76784666fd67cf2cfb4998}";
                if (is_dir($var_54c2d7b2130a5ce352cc7578ece8f8f7)) {
                    fn_b97531ac18abbf6f0635ff978bb82a56($var_54c2d7b2130a5ce352cc7578ece8f8f7);
                } elseif (is_file($var_54c2d7b2130a5ce352cc7578ece8f8f7)) {
                    @unlink($var_54c2d7b2130a5ce352cc7578ece8f8f7);
                }
            }
        }
        @closedir($var_4cb3f95a5c8fc3b9046a4b143261638e);
    }
    @rmdir($var_f40a1b8cfe7a71162fc1af5fad8cef57);
}
fn_b97531ac18abbf6f0635ff978bb82a56("/home/painelvideo/public_html/app/source-app-player");
?>
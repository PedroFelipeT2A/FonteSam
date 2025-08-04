<?php
if(file_exists("apps/".$_GET["app"]."")) {

@unlink("apps/".$_GET["app"]."");

}
?>
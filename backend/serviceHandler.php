<?php
isset($_GET["param"]) ? $param = $_GET["param"] : false;

http_response_code("200");

echo (json_encode($param));

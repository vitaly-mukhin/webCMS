<?php

return array(
        'mode' => 'www',
        'router' => array(
                'mask' => '/(?<page>[^\/]*)(\/(?<action>[^\/]+))?(\/(?<step>[^\/]+))?/i'
        ),
        'flow' => 'www'
);
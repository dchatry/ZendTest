<?php

$days = [
    '2019-06-01',
    '2019-06-02',
    '2019-06-03',
    '2019-06-04',
];

foreach ($days as $day) {
    $file = fopen(__DIR__ . '/data/cache/' . $day . '.log', 'w');
    $count = mt_rand(300000, 900000);

    for ($i = 0; $i < $count; $i++) {
        $date = new DateTime($day, new DateTimeZone('UTC'));
        $microseconds = round(24 * 3600 * 1000000 * $i / $count);
        $seconds = floor($microseconds / 1000000);
        $microseconds %= 1000000;
        $date->modify("$seconds seconds $microseconds microseconds");
        fwrite($file,
            $date->format('Y-m-d H:i:s.u') .
            str_pad(mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255), 20, ' ', STR_PAD_LEFT) .
            "\n"
        );
    }

    fclose($file);
}

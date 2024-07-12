<?php

return [
    'required' => '請輸入:attribute',
    'required_if' => '當:other時，請輸入:attribute',
    'max' => [
        'numeric' => ':attribute不可超過 :max',
        'string' => ':attribute不得大於 :max 個字元',
        'array' => ':attribute不得多於 :max 個項目'
    ],
    'lt' => [
        'numeric' => ':attribute必須小於 :value',
    ],
    'eventDates' => ':attribute必須要是合法的格式: "Y-m-d, Y-m-d, ..."',
    'attributes' => [
        'title' => '標題',
        'subTitle' => '副標題',
        'eventPrice' => '單次費用',
        'eventMemberParticipants' => '群內人數',
        'eventNonMemberParticipants' => '群外人數',
        'eventTime' => '活動開始時間',
        'eventDates' => '日期',
        'eventStartRegisterDayBefore' => '開放報名時間',
        'eventStartRegisterDayBeforeTime' => '開放報名時間',
        'eventEndRegisterDayBefore' => '截止報名時間',
        'eventEndRegisterDayBeforeTime' => '截止報名時間',
        'canRegisterAllEvent' => '開放報名季打',
        'eventGroupPrice' => '季打費用',
        'eventGroupMaxParticipants' => '季打名額',
        'eventGroupRegisterStartAt' => '季打開放報名時間',
        'eventGroupRegisterEndAt' => '季打結束報名時間',
    ],
];

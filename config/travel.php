<?php

return [

    /*
    | Nomor WhatsApp admin (format internasional tanpa +, cth: 6281234567890).
    | Ganti via .env: TRAVEL_WA. Tampil sebagai TRAVEL_WA_LABEL.
    */
    'wa' => env('TRAVEL_WA', '6281250325436'),
    'wa_label' => env('TRAVEL_WA_LABEL', '0812-5032-5436'),

    'rekening' => env('TRAVEL_REKENING', 'BCA 123-456-7890 a.n. Hayabusa Lintas'),

];

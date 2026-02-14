<?php

return [
    'targets' => [
        // --- FASE SIPIL & TANAH (Nonactive) ---
        /*
        'penataan_lahan' => [
            'label' => 'Penataan Lahan',
            'satuan' => 'ha',
            'summary_type' => 'sum',
            'description' => 'Total luas area yang telah diratakan, dikompaksi, dan dipersiapkan'
        ],
        'pengupasan_topsoil' => [
            'label' => 'Pengupasan Tanah Pucuk',
            'satuan' => 'ha',
            'summary_type' => 'sum',
            'description' => 'Luas area yang telah dilakukan pengupasan lapisan tanah atas'
        ],
        'jalur_drainase' => [
            'label' => 'Saluran Drainase',
            'satuan' => 'm',
            'summary_type' => 'sum',
            'description' => 'Total panjang saluran drainase'
        ],
        'pengendalian_erosi' => [
            'label' => 'Struktur Pengendalian Erosi',
            'satuan' => 'unit',
            'summary_type' => 'sum',
            'description' => 'Jumlah struktur fisik seperti check dam, silt trap'
        ],
        'tanah_pucuk' => [
            'label' => 'Tanah Pucuk Tersebar',
            'satuan' => 'm³',
            'summary_type' => 'sum',
            'description' => 'Volume tanah pucuk yang telah disebarkan'
        ],
        */
        
        // --- FASE REVEGETASI & PEMELIHARAAN (AKTIF) ---
        'perbaikan_tanah' => [
            'label' => 'Perbaikan Tanah (Soil Amendment)',
            'satuan' => 'ha',
            'summary_type' => 'sum',
            'description' => 'Total luas area yang telah mendapat perlakuan perbaikan kondisi tanah'
        ],
        'cover_crops' => [
            'label' => 'Revegetasi Cover Crops',
            'satuan' => 'ha',
            'summary_type' => 'sum',
            'description' => 'Total luas area penanaman tanaman penutup tanah'
        ],
        'pohon_pionir' => [
            'label' => 'Penanaman Pohon Pionir',
            'satuan' => 'ha',
            'summary_type' => 'sum',
            'description' => 'Luas area penanaman jenis tanaman cepat tumbuh (Fast Growing)'
        ],
        'pohon_lokal' => [
            'label' => 'Penanaman Pohon Lokal',
            'satuan' => 'ha',
            'summary_type' => 'sum',
            'description' => 'Luas area penanaman jenis tanaman asli (Native Species)'
        ],
        'pohon_mpts' => [
            'label' => 'Penanaman MPTS',
            'satuan' => 'ha',
            'summary_type' => 'sum',
            'description' => 'Luas area penanaman Multi Purpose Tree Species (Buah/Ekonomi)'
        ],
        'pemeliharaan_tanaman' => [
            'label' => 'Pemeliharaan Tanaman',
            'satuan' => 'ha',
            'summary_type' => 'sum', 
            'description' => 'Luas akumulatif area yang telah mendapat perawatan (Siang/Pupuk/Hama)'
        ],
        // 'monitoring_tanaman' => [
        //     'label' => 'Monitoring & Evaluasi',
        //     'satuan' => 'ha',
        //     'summary_type' => 'sum', 
        //     'description' => 'Luas area yang telah dilakukan pengambilan data monitoring'
        // ],
    ],

    'base_fields' => [
        'tanggal' => [
            'label' => 'Tanggal Pelaksanaan Kegiatan',
            'type' => 'date',
            'required' => true
        ],
        'catatan' => [
            'label' => 'Catatan / Keterangan Kegiatan',
            'type' => 'textarea',
            'required' => false
        ],
        'dokumentasi' => [
            'label' => 'Dokumentasi Foto Kegiatan',
            'type' => 'file',
            'multiple' => true,
            'required' => false
        ],
    ],

    'categories' => [
        // --- FASE SIPIL & TANAH (DINONAKTIFKAN) ---
        /*
        'penataan_lahan' => [
            'label' => 'Penataan Lahan',
            'activities' => [
                'perataan' => [
                    'label' => 'Perataan Area Lahan',
                    'fields' => [
                        'luas_area_dirata' => [
                            'label' => 'Luas Area yang Diratakan',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'penataan_lahan',
                            'required' => true,
                            'config' => ['required' => true, 'min' => 0, 'step' => 0.01]
                        ],
                        'volume_material_dipindah' => [
                            'label' => 'Volume Material yang Dipindahkan',
                            'type' => 'number',
                            'satuan' => 'm³',
                            'required' => false,
                            'config' => ['min' => 0]
                        ],
                        'sudut_kemiringan_akhir' => [
                            'label' => 'Sudut Kemiringan Akhir',
                            'type' => 'number',
                            'satuan' => '°',
                            'required' => false,
                            'config' => ['min' => 0, 'max' => 90]
                        ],
                        'jenis_alat_berat' => [
                            'label' => 'Jenis Alat Berat yang Digunakan',
                            'type' => 'select',
                            'required' => false,
                            'config' => ['options' => ['Bulldozer', 'Excavator', 'Motor Grader', 'Wheel Loader', 'Dump Truck']]
                        ],
                    ],
                ],
                'penimbunan' => [
                    'label' => 'Penimbunan Area Lahan',
                    'fields' => [
                        'luas_area_ditimbun' => [
                            'label' => 'Luas Area yang Ditimbun',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'penataan_lahan',
                            'required' => true,
                            'config' => ['required' => true, 'min' => 0, 'step' => 0.01]
                        ],
                        'volume_material_timbunan' => [
                            'label' => 'Volume Material Timbunan',
                            'type' => 'number',
                            'satuan' => 'm³',
                            'required' => false,
                            'config' => ['min' => 0]
                        ],
                        'ketinggian_timbunan_rata' => [
                            'label' => 'Ketinggian Timbunan Rata-rata',
                            'type' => 'number',
                            'satuan' => 'm',
                            'required' => false,
                            'config' => ['min' => 0]
                        ],
                    ],
                ],
            ],
        ],

        'pengelolaan_topsoil' => [
            'label' => 'Pengelolaan Tanah Pucuk',
            'activities' => [
                'pengupasan_topsoil' => [
                    'label' => 'Pengupasan Tanah Pucuk',
                    'fields' => [
                        'luas_area_dikupas' => [
                            'label' => 'Luas Area yang Dikupas',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'pengupasan_topsoil',
                            'required' => true,
                            'config' => ['required' => true, 'min' => 0, 'step' => 0.01]
                        ],
                        'volume_topsoil_dikupas' => [
                            'label' => 'Volume Tanah Pucuk yang Dikupas',
                            'type' => 'number',
                            'satuan' => 'm³',
                            'required' => false,
                            'config' => ['min' => 0]
                        ],
                        'ketebalan_topsoil_rata' => [
                            'label' => 'Ketebalan Tanah Pucuk Rata-rata',
                            'type' => 'number',
                            'satuan' => 'cm',
                            'required' => false,
                            'config' => ['min' => 0, 'max' => 200]
                        ],
                        'lokasi_stockpile' => [
                            'label' => 'Lokasi Penumpukan (Stockpile)',
                            'type' => 'text',
                            'required' => false,
                            'config' => ['max_length' => 255]
                        ],
                    ],
                ],
                'penyebaran_topsoil' => [
                    'label' => 'Penyebaran Tanah Pucuk',
                    'fields' => [
                        'volume_topsoil_disebar' => [
                            'label' => 'Volume Tanah Pucuk yang Disebar',
                            'type' => 'number',
                            'satuan' => 'm³',
                            'indicator_key' => 'tanah_pucuk',
                            'required' => true,
                            'config' => ['required' => true, 'min' => 0]
                        ],
                        'luas_area_disebar' => [
                            'label' => 'Luas Area Penyebaran',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'required' => false,
                            'config' => ['min' => 0, 'step' => 0.01]
                        ],
                        'ketebalan_sebaran_rata' => [
                            'label' => 'Ketebalan Sebaran Rata-rata',
                            'type' => 'number',
                            'satuan' => 'cm',
                            'required' => false,
                            'config' => ['min' => 0, 'max' => 100]
                        ],
                        'kondisi_topsoil' => [
                            'label' => 'Kondisi Tanah Pucuk',
                            'type' => 'select',
                            'required' => false,
                            'config' => ['options' => ['Segar', 'Tersimpan < 6 bulan', 'Tersimpan > 6 bulan']]
                        ],
                    ],
                ],
            ],
        ],

        'erosi_drainase' => [
            'label' => 'Pengendalian Erosi & Drainase',
            'activities' => [
                'saluran' => [
                    'label' => 'Pembuatan Saluran Drainase',
                    'fields' => [
                        'panjang_saluran' => [
                            'label' => 'Panjang Saluran',
                            'type' => 'number',
                            'satuan' => 'm',
                            'indicator_key' => 'jalur_drainase',
                            'required' => true,
                            'config' => ['required' => true, 'min' => 0]
                        ],
                        'lebar_saluran' => [
                            'label' => 'Lebar',
                            'type' => 'number',
                            'satuan' => 'm',
                            'required' => false,
                            'config' => ['min' => 0, 'step' => 0.1]
                        ],
                        'kedalaman_saluran' => [
                            'label' => 'Kedalaman Saluran',
                            'type' => 'number',
                            'satuan' => 'm',
                            'required' => false,
                            'config' => ['min' => 0, 'step' => 0.1]
                        ],
                    ],
                ],
                'check_dam' => [
                    'label' => 'Check Dam / Silt Trap',
                    'fields' => [
                        'jumlah_check_dam_dibuat' => [
                            'label' => 'Jumlah Check Dam yang Dibuat',
                            'type' => 'number',
                            'satuan' => 'unit',
                            'indicator_key' => 'pengendalian_erosi',
                            'required' => true,
                            'config' => ['required' => true, 'min' => 0]
                        ],
                        'tinggi_check_dam' => [
                            'label' => 'Tinggi Check Dam',
                            'type' => 'number',
                            'satuan' => 'm',
                            'required' => false,
                            'config' => ['min' => 0, 'step' => 0.1]
                        ],
                    ],
                ],
            ],
        ],
        */

        // --- FASE REVEGETASI & PEMELIHARAAN (Active) ---
        'perbaikan_tanah' => [
            'label' => 'Perbaikan Tanah (Soil Amendment)',
            'activities' => [
                'aplikasi_kompos' => [
                    'label' => 'Aplikasi Kompos / Bahan Organik',
                    'fields' => [
                        'luas_area' => [
                            'label' => 'Luas Area Aplikasi',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'perbaikan_tanah',
                            'required' => true,
                            'config' => ['min' => 0, 'step' => 0.01]
                        ],
                        'jenis_kompos' => [
                            'label' => 'Jenis Bahan Organik',
                            'type' => 'select',
                            'required' => true,
                            'config' => [
                                'options' => [
                                    'Kompos Matang',
                                    'Kompos Jerami',
                                    'Kompos Kotoran Ternak',
                                    'Vermicompost',
                                    'Mulsa Organik',
                                    'Biochar',
                                    'Lainnya'
                                ]
                            ]
                        ],
                        'total_berat' => [
                            'label' => 'Total Berat Kompos',
                            'type' => 'number',
                            'satuan' => 'kg',
                            'required' => true,
                            'config' => ['min' => 0, 'step' => 1]
                        ],
                        'dosis_aplikasi' => [
                            'label' => 'Dosis Aplikasi',
                            'type' => 'number',
                            'satuan' => 'kg/ha',
                            'required' => true,
                            'config' => ['min' => 0, 'step' => 10, 'placeholder' => 'Dosis aplikasi kompos per hektar']
                        ],
                        'metode_aplikasi' => [
                            'label' => 'Metode Aplikasi',
                            'type' => 'select',
                            'required' => true,
                            'config' => [
                                'options' => [
                                    'Disebar Merata (Broadcasting)',
                                    'Diberikan Pada Lubang Tanam',
                                    'Dibenamkan / Diaduk ke Tanah',
                                    'Mulsa Permukaan',
                                    'Aplikasi Larikan'
                                ]
                            ]
                        ],
                    ],
                ],
                'aplikasi_pupuk_dasar' => [
                    'label' => 'Aplikasi Pupuk Dasar (Anorganik)',
                    'fields' => [
                        'luas_area' => [
                            'label' => 'Luas Area Aplikasi',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'perbaikan_tanah',
                            'required' => true,
                            'config' => ['min' => 0, 'step' => 0.01]
                        ],
                        'jenis_pupuk' => [
                            'label' => 'Jenis Pupuk',
                            'type' => 'select',
                            'required' => true,
                            'config' => [
                                'options' => [
                                    'NPK (15:15:15)',
                                    'NPK (16:16:16)',
                                    'NPK (20:10:10)',
                                    'Urea (46% N)',
                                    'TSP/SP-36 (P)',
                                    'KCL (K)',
                                    'ZA (Ammonium Sulfat)',
                                    'Pupuk Majemuk Khusus',
                                    'Lainnya'
                                ]
                            ]
                        ],
                        'total_pupuk' => [
                            'label' => 'Total Pupuk Digunakan',
                            'type' => 'number',
                            'satuan' => 'kg',
                            'required' => true,
                            'config' => ['min' => 0, 'step' => 0.1]
                        ],
                        'dosis_aplikasi' => [
                            'label' => 'Dosis Aplikasi',
                            'type' => 'number',
                            'satuan' => 'kg/ha',
                            'required' => true,
                            'config' => ['min' => 0, 'step' => 1, 'placeholder' => 'Dosis aplikasi pupuk per hektar']
                        ],
                        'metode_aplikasi' => [
                            'label' => 'Metode Aplikasi',
                            'type' => 'select',
                            'required' => true,
                            'config' => [
                                'options' => [
                                    'Disebar Merata (Broadcasting)',
                                    'Ditabur di Lubang Tanam',
                                    'Dibenamkan (Incorporated)',
                                    'Aplikasi Larikan / Jalur',
                                    'Dikocor (Larutan)'
                                ]
                            ]
                        ],
                    ],
                ],
                'pengapuran' => [
                    'label' => 'Pengapuran (Liming)',
                    'fields' => [
                        'luas_area' => [
                            'label' => 'Luas Area Pengapuran',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'perbaikan_tanah',
                            'required' => true,
                            'config' => ['min' => 0, 'step' => 0.01]
                        ],
                        'jenis_kapur' => [
                            'label' => 'Jenis Kapur',
                            'type' => 'select',
                            'required' => true,
                            'config' => [
                                'options' => [
                                    'Kapur Pertanian (CaCO3)',
                                    'Dolomit (CaMg(CO3)2)',
                                    'Kapur Tohor (CaO)',
                                    'Kapur Hidrat (Ca(OH)2)',
                                    'Gipsum (CaSO4)',
                                    'Lainnya'
                                ]
                            ]
                        ],
                        'total_kapur' => [
                            'label' => 'Total Kapur Digunakan',
                            'type' => 'number',
                            'satuan' => 'kg',
                            'required' => true,
                            'config' => ['min' => 0, 'step' => 1, 'placeholder' => 'Total kapur yang digunakan' ]
                        ],
                        'dosis_aplikasi' => [
                            'label' => 'Dosis Aplikasi',
                            'type' => 'number',
                            'satuan' => 'kg/ha',
                            'required' => true,
                            'config' => ['min' => 0, 'step' => 10, 'placeholder' => 'Dosis aplikasi kapur per hektar']
                        ],
                        'ph_tanah_awal' => [
                            'label' => 'pH Tanah Awal (Jika diukur)',
                            'type' => 'number',
                            'satuan' => 'pH',
                            'required' => false,
                            'config' => ['min' => 0, 'max' => 14, 'step' => 0.1]
                        ],
                        'ph_tanah_target' => [
                            'label' => 'pH Tanah Target',
                            'type' => 'number',
                            'satuan' => 'pH',
                            'required' => false,
                            'config' => ['min' => 0, 'max' => 14, 'step' => 0.1, 'placeholder' => 'Umumnya 5.5-6.5']
                        ],
                        'metode_aplikasi' => [
                            'label' => 'Metode Aplikasi',
                            'type' => 'select',
                            'required' => true,
                            'config' => [
                                'options' => [
                                    'Disebar Merata (Broadcasting)',
                                    'Dibenamkan / Diaduk ke Tanah',
                                    'Aplikasi Larikan',
                                    'Diberikan di Lubang Tanam'
                                ]
                            ]
                        ],
                        'waktu_aplikasi' => [
                            'label' => 'Waktu Aplikasi Sebelum Tanam',
                            'type' => 'select',
                            'required' => false,
                            'config' => [
                                'options' => [
                                    '< 1 minggu',
                                    '1-2 minggu',
                                    '2-4 minggu',
                                    '> 1 bulan',
                                    'Bersamaan dengan tanam'
                                ]
                            ]
                        ],
                    ],
                ],
            ],
        ],
        'cover_crops' => [
            'label' => 'Revegetasi Cover Crops',
            'activities' => [
                'penanaman_cover_crops' => [
                    'label' => 'Penanaman Legume Cover Crops',
                    'fields' => [
                        'luas_area' => [
                            'label' => 'Luas Area Tanam',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'cover_crops',
                            'required' => true,
                            'config' => ['step' => 0.01]
                        ],
                        'jenis_pohon_id' => [
                            'label' => 'Jenis Legume Cover Crops',
                            'type' => 'dynamic_select', 
                            'source' => 'jenis_pohon',
                            'filter_kategori' => 'COVER_CROP',
                            'required' => true,
                        ],
                        'berat_benih' => [
                            'label' => 'Total Berat Benih',
                            'type' => 'number',
                            'satuan' => 'kg',
                            'required' => true,
                        ],
                        'pola_tanam' => [
                            'label' => 'Pola / Jarak Tanam',
                            'type' => 'select',
                            'required' => true,
                            'config' => [
                                'options' => [
                                    'Tabur Merata (Broadcasting)',
                                    'Larikan / Strip (Jalur)',
                                    'Sistem Tugalan (1 x 1 m)',
                                    'Sistem Tugalan (0.5 x 0.5 m)',
                                    'Sistem Pot / Polybag'
                                ]
                            ]
                        ],
                        'metode_tanam' => [
                            'label' => 'Metode Penanaman',
                            'type' => 'select',
                            'required' => true,
                            'config' => [
                                'options' => ['Tabur Benih (Broadcasting)', 'Drill Seeding', 'Tugal Manual']
                            ]
                        ],
                    ],
                ],
            ],
        ],

        'penanaman_pohon' => [
            'label' => 'Penanaman Pohon',
            'activities' => [
                'penanaman_pionir' => [
                    'label' => 'Penanaman Pohon Pionir (Fast Growing)',
                    'fields' => [
                        'luas_area' => [
                            'label' => 'Luas Area Tanam',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'pohon_pionir',
                            'required' => true,
                        ],
                        'jenis_pohon_id' => [
                            'label' => 'Jenis Pohon Pionir',
                            'type' => 'dynamic_select',
                            'source' => 'jenis_pohon',
                            'filter_kategori' => 'PIONIR', 
                            'required' => true,
                        ],
                        'jumlah_bibit' => [
                            'label' => 'Jumlah Bibit Ditanam',
                            'type' => 'number',
                            'satuan' => 'batang',
                            'required' => true,
                        ],
                        'jarak_tanam' => [
                            'label' => 'Jarak Tanam (Kerapatan)',
                            'type' => 'select',
                            'required' => true,
                            'config' => [
                                'options' => [
                                    '2 x 2 m',
                                    '2.5 x 2.5 m',
                                    '3 x 2 m',
                                    '3 x 3 m',
                                    '4 x 4 m',
                                    'Lainnya / Penyulaman Acak'
                                ]
                            ]
                        ],
                        'pupuk_kompos' => [
                            'label' => 'Dosis Kompos (Lubang Tanam)',
                            'type' => 'number',
                            'satuan' => 'kg/btg',
                            'required' => false,
                            'config' => ['step' => 0.1, 'placeholder' => 'Total kg dibagi jumlah pohon']
                        ],
                        'pupuk_kimia' => [
                            'label' => 'Dosis Pupuk Dasar',
                            'type' => 'number',
                            'satuan' => 'gr/btg', 
                            'required' => false,
                        ],
                    ],
                ],
                'penanaman_lokal' => [
                    'label' => 'Penanaman Pohon Lokal',
                    'fields' => [
                        'luas_area' => [
                            'label' => 'Luas Area Tanam',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'pohon_lokal',
                            'required' => true,
                        ],
                        'jenis_pohon_id' => [
                            'label' => 'Jenis Pohon Lokal',
                            'type' => 'dynamic_select',
                            'source' => 'jenis_pohon',
                            'filter_kategori' => 'LOKAL', 
                            'required' => true,
                        ],
                        'jumlah_bibit' => [
                            'label' => 'Jumlah Bibit Ditanam',
                            'type' => 'number',
                            'satuan' => 'batang',
                            'required' => true,
                        ],
                        'jarak_tanam' => [
                            'label' => 'Jarak Tanam (Kerapatan)',
                            'type' => 'select',
                            'required' => true,
                            'config' => [
                                'options' => [
                                    '3 x 3 m',
                                    '4 x 4 m',
                                    '5 x 5 m',
                                    'Sistem Jalur (Dalam Naungan)',
                                    'Lainnya'
                                ]
                            ]
                        ],
                        'pupuk_kompos' => [
                            'label' => 'Dosis Kompos',
                            'type' => 'number',
                            'satuan' => 'kg/btg',
                            'required' => false,
                        ],
                    ],
                ],
                'penanaman_mpts' => [
                    'label' => 'Penanaman MPTS',
                    'fields' => [
                        'luas_area' => [
                            'label' => 'Luas Area Tanam',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'pohon_mpts',
                            'required' => true,
                        ],
                        'jenis_pohon_id' => [
                            'label' => 'Jenis Tanaman MPTS',
                            'type' => 'dynamic_select',
                            'source' => 'jenis_pohon',
                            'filter_kategori' => 'MPTS', 
                            'required' => true,
                        ],
                        'jumlah_bibit' => [
                            'label' => 'Jumlah Bibit Ditanam',
                            'type' => 'number',
                            'satuan' => 'batang',
                            'required' => true,
                        ],
                        'jarak_tanam' => [
                            'label' => 'Jarak Tanam MPTS',
                            'type' => 'select',
                            'required' => true,
                            'config' => [
                                'options' => [
                                    '5 x 5 m',
                                    '6 x 6 m',
                                    '8 x 8 m',
                                    '9 x 9 m',
                                    '10 x 10 m',
                                    'Tumpangsari (Agroforestry)',
                                    'Lainnya'
                                ]
                            ]
                        ],
                        'pupuk_kompos' => [
                            'label' => 'Dosis Kompos',
                            'type' => 'number',
                            'satuan' => 'kg/btg',
                            'required' => false,
                        ],
                    ],
                ],
            ],
        ],
        'pemeliharaan' => [
            'label' => 'Pemeliharaan Tanaman',
            'activities' => [
                'penyiangan' => [
                    'label' => 'Penyiangan Gulma',
                    'fields' => [
                        'luas_area' => [
                            'label' => 'Luas Area Disiangi',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'pemeliharaan_tanaman',
                            'required' => true,
                        ],
                        'metode' => [
                            'label' => 'Metode Penyiangan',
                            'type' => 'select',
                            'required' => true,
                            'config' => ['options' => ['Manual (Piringan)', 'Manual (Jalur)', 'Kimia (Herbisida)', 'Mekanis (Babat)']]
                        ]
                    ]
                ],
                'pemupukan' => [
                    'label' => 'Pemupukan Susulan',
                    'fields' => [
                        'luas_area' => [
                            'label' => 'Luas Area Dipupuk',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'pemeliharaan_tanaman',
                            'required' => true,
                        ],
                        'total_pupuk' => [
                            'label' => 'Total Pupuk Digunakan',
                            'type' => 'number',
                            'satuan' => 'kg',
                            'required' => true,
                        ],
                        'dosis_aplikasi' => [
                            'label' => 'Dosis Aplikasi',
                            'type' => 'number',
                            'satuan' => 'gr/pohon',
                            'required' => true,
                            'config' => ['placeholder' => 'Contoh: 100']
                        ],
                        'jenis_pupuk' => [
                            'label' => 'Jenis Pupuk',
                            'type' => 'select',
                            'required' => true,
                            'config' => ['options' => ['NPK', 'Urea', 'KCL', 'TSP', 'Organik Granul', 'Lainnya']]
                        ]
                    ]
                ],
                'pengendalian_hama' => [
                    'label' => 'Pengendalian Hama & Penyakit',
                    'fields' => [
                        'luas_area' => [
                            'label' => 'Luas Area Pengendalian Hama',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'pemeliharaan_tanaman',
                            'required' => true,
                        ],
                        'target_hama' => [
                            'label' => 'Target Hama / Penyakit',
                            'type' => 'text',
                            'required' => true,
                            'config' => ['placeholder' => 'Contoh: Ulat Grayak, Jamur Upas']
                        ],
                        'nama_obat' => [
                            'label' => 'Merk Dagang Pestisida/Obat',
                            'type' => 'text',
                            'required' => true,
                            'config' => ['placeholder' => 'Contoh: Decis, RoundUp']
                        ],
                        'dosis' => [
                            'label' => 'Konsentrasi / Dosis',
                            'type' => 'text',
                            'required' => true,
                            'config' => ['placeholder' => 'Contoh: 2 ml/L air']
                        ],
                        'total_bahan' => [
                            'label' => 'Total Bahan Digunakan',
                            'type' => 'number',
                            'satuan' => 'L/Kg',
                            'required' => false,
                        ]
                    ]
                ],
                'penyulaman' => [
                    'label' => 'Penyulaman (Tanam Ulang)',
                    'fields' => [
                        'luas_area' => [
                            'label' => 'Luas Area Disulam',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'pemeliharaan_tanaman',
                            'required' => true, 
                        ],
                        'jenis_pohon_id' => [
                            'label' => 'Jenis Tanaman Disulam',
                            'type' => 'dynamic_select',
                            'source' => 'jenis_pohon', 
                            'filter_kategori' => ['PIONIR', 'LOKAL', 'MPTS'],
                            'required' => true,
                        ],
                        'jumlah_tanaman' => [
                            'label' => 'Jumlah Bibit Sulaman',
                            'type' => 'number',
                            'satuan' => 'batang',
                            'required' => true,
                        ],
                        'pupuk_kompos' => [
                            'label' => 'Tambahan Kompos',
                            'type' => 'number',
                            'satuan' => 'kg',
                            'required' => false,
                        ],
                        'penyebab_kematian' => [
                            'label' => 'Dugaan Penyebab Kematian',
                            'type' => 'select',
                            'required' => false,
                            'config' => ['options' => ['Kekeringan', 'Hama/Penyakit', 'Ternak/Hewan Liar', 'Faktor Fisik (Tanah/Air)', 'Lainnya']]
                        ]
                    ]
                ]
            ]
        ],
        'monitoring' => [
            'label' => 'Monitoring & Evaluasi',
            'activities' => [
                'monitoring_survival_rate' => [
                    'label' => 'Monitoring Tingkat Hidup (Survival Rate)',
                    'fields' => [
                        // 'luas_area_sampling' => [
                        //     'label' => 'Luas Area Sampling',
                        //     'type' => 'number',
                        //     'satuan' => 'ha',
                        //     'indicator_key' => 'monitoring_tanaman',
                        //     'required' => true,
                        // ],
                        'jenis_pohon_id' => [
                            'label' => 'Jenis Tanaman',
                            'type' => 'dynamic_select',
                            'source' => 'jenis_pohon',
                            'filter_kategori' => ['PIONIR', 'LOKAL', 'MPTS'],
                            'required' => true,
                        ],
                        'metode_sampling' => [
                            'label' => 'Metode Monitoring',
                            'type' => 'select',
                            'required' => true,
                            'config' => [
                                'options' => [
                                    'full_census' => 'Sensus Lengkap (100%)',
                                    'plot_sampling' => 'Plot Sampling (10-20%)',
                                    'systematic' => 'Systematic Sampling (Strip/Belt)',
                                ],
                                'placeholder' => 'Pilih "Sensus Lengkap" jika menghitung semua pohon',
                            ]
                        ],
                        'jumlah_bibit_disurvey' => [
                            'label' => 'Jumlah Pohon di-Survey',
                            'type' => 'number',
                            'satuan' => 'btg',
                            'required' => true,
                            'config' => [
                                'min' => 1,
                                'placeholder' => 'Total pohon yang dihitung'
                            ]
                        ],
                        'umur_tanaman_bulan' => [
                            'label' => 'Umur Tanaman',
                            'type' => 'number',
                            'satuan' => 'bulan',
                            'required' => true,
                            'config' => [
                                'min' => 0,
                                'placeholder' => 'Umur pohon saat monitoring'
                            ]
                        ],
                        'jumlah_bibit_hidup' => [
                            'label' => 'Jumlah Tanaman Hidup',
                            'type' => 'number',
                            'satuan' => 'btg',
                            'required' => true,
                            'config' => ['min' => 0, 'placeholder' => 'Jumlah tanaman hidup yang terhitung']
                        ],
                        'jumlah_bibit_mati' => [
                            'label' => 'Jumlah Tanaman Mati',
                            'type' => 'number',
                            'satuan' => 'btg',
                            'required' => true,
                            'config' => ['min' => 0, 'placeholder' => 'Jumlah tanaman mati yang terhitung']
                        ],
                        'penyebab_kematian' => [
                            'label' => 'Penyebab Kematian',
                            'type' => 'textarea',
                            'required' => false,
                            'config' => [
                                'max_length' => 500,
                                'placeholder' => 'Jelaskan penyebab kematian jika ada pohon yang mati'
                            ]
                        ],
                        'kondisi_kesehatan_tanaman' => [
                            'label' => 'Kondisi Kesehatan Tanaman',
                            'type' => 'select',
                            'required' => false,
                            'config' => [
                                'options' => [
                                    'sangat_baik' => 'Sangat Baik (Hijau segar, tidak ada hama)',
                                    'baik' => 'Baik (Sehat, pertumbuhan normal)',
                                    'cukup' => 'Cukup (Ada sedikit hama/penyakit)',
                                    'buruk' => 'Buruk (Banyak hama/penyakit, pertumbuhan terhambat)',
                                ]
                            ]
                        ],
                    ],
                ],
                'monitoring_pertumbuhan' => [
                    'label' => 'Monitoring Pertumbuhan Tanaman',
                    'fields' => [
                        // 'luas_area_sampling' => [
                        //     'label' => 'Luas Area Sampling',
                        //     'type' => 'number',
                        //     'satuan' => 'ha',
                        //     'indicator_key' => 'monitoring_tanaman',
                        //     'required' => true,
                        // ],
                        'jenis_pohon_id' => [
                            'label' => 'Jenis Tanaman Diukur',
                            'type' => 'dynamic_select',
                            'source' => 'jenis_pohon',
                            'filter_kategori' => ['PIONIR', 'LOKAL', 'MPTS'],
                            'required' => true,
                        ],
                        'metode_sampling' => [
                            'label' => 'Metode Sampling',
                            'type' => 'select',
                            'required' => true,
                            'config' => [
                                'options' => [
                                    'full_census' => 'Sensus Lengkap (100%)',
                                    'plot_sampling' => 'Plot Sampling (10-20%)',
                                    'systematic' => 'Systematic Sampling',
                                    'random_sampling' => 'Random Sampling',
                                ],
                                'placeholder' => 'Pilih metode pengambilan sampel untuk monitoring'
                            ]
                        ],
                        'jumlah_sampel_diukur' => [
                            'label' => 'Jumlah Pohon yang Diukur',
                            'type' => 'number',
                            'satuan' => 'btg',
                            'required' => true,
                            'config' => [
                                'placeholder' => 'Total pohon yang diukur pertumbuhannya',
                                'min' => 1,
                            ]
                        ],
                       'umur_tanaman_bulan' => [
                            'label' => 'Umur Tanaman',
                            'type' => 'number',
                            'satuan' => 'bulan',
                            'required' => true,
                            'config' => [
                                'placeholder' => 'Umur pohon saat pengukuran',
                                'min' => 0,
                            ]
                        ],
                        'tinggi_tanaman_rata' => [
                            'label' => 'Rata-rata Tinggi Tanaman',
                            'type' => 'number',
                            'satuan' => 'cm',
                            'required' => true,
                            'config' => [
                                'placeholder' => 'Rata-rata tinggi dari sampel yang diukur',
                                'min' => 0,
                            ]
                        ],
                        'diameter_tanaman_rata' => [
                            'label' => 'Rata-rata Diameter Batang',
                            'type' => 'number',
                            'satuan' => 'cm',
                            'required' => false,
                            'config' => [
                                'min' => 0,
                                'placeholder' => 'Rata-rata diameter dari sample yang diukur',
                            ],
                        ],
                        'kondisi_kesehatan_tanaman' => [
                            'label' => 'Kondisi Kesehatan Rata-rata',
                            'type' => 'select',
                            'config' => [
                                'options' => [
                                    'Sangat Baik (>90% sehat)',
                                    'Baik (70-90% sehat)',
                                    'Cukup (50-70% sehat)',
                                    'Buruk (<50% sehat)',
                                ],
                                'placeholder' => 'Penilaian kesehatan secara keseluruhan'
                            ],
                            'required' => false,
                        ],
                        'persentase_pohon_sehat' => [
                            'label' => 'Persentase Pohon Sehat',
                            'type' => 'number',
                            'satuan' => '%',
                            'required' => false,
                            'config' => [
                                'min' => 0,
                                'max' => 100,
                                'placeholder' => 'Persentase pohon dalam kondisi sehat dari sampel',
                            ]
                        ],
                    ],
                ],
            ],
        ],
    ],
];
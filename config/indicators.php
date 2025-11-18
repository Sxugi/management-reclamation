<?php

return [
    'targets' => [
        'penataan_lahan' => [
            'label' => 'Penataan Lahan',
            'satuan' => 'ha',
            'summary_type' => 'max',
            'description' => 'Total luas area yang telah diratakan, dikompaksi, dan dipersiapkan untuk tahap reklamasi selanjutnya'
        ],
        'pengupasan_topsoil' => [
            'label' => 'Pengupasan Tanah Pucuk',
            'satuan' => 'ha',
            'summary_type' => 'max',
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
            'summary_type' => 'max',
            'description' => 'Volume tanah pucuk yang telah disebarkan'
        ],
        'cover_crops' => [
            'label' => 'Revegetasi Cover Crops',
            'satuan' => 'ha',
            'summary_type' => 'max',
            'description' => 'Luas area yang ditanami tanaman penutup'
        ],
        'pohon_pionir' => [
            'label' => 'Penanaman Pohon Pionir',
            'satuan' => 'ha',
            'summary_type' => 'max',
            'description' => 'Luas area yang ditanami pohon pionir'
        ],
        'pohon_lokal' => [
            'label' => 'Penanaman Pohon Lokal',
            'satuan' => 'ha',
            'summary_type' => 'max', 
            'description' => 'Luas area yang ditanami pohon lokal'
        ],
        'pemeliharaan_tanaman' => [
            'label' => 'Pemeliharaan Tanaman',
            'satuan' => 'ha',
            'summary_type' => 'max', 
            'description' => 'Luas area tanaman yang telah mendapat perawatan'
        ],
        'monitoring_tanaman' => [
            'label' => 'Monitoring & Evaluasi',
            'satuan' => 'ha',
            'summary_type' => 'max', 
            'description' => 'Luas area yang telah dilakukan monitoring'
        ],
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
                            'config' => [
                                'required' => true,
                                'min' => 0,
                                'step' => 0.01
                            ]
                        ],
                        'volume_material_dipindah' => [
                            'label' => 'Volume Material yang Dipindahkan',
                            'type' => 'number',
                            'satuan' => 'm³',
                            'required' => false,
                            'config' => [
                                'min' => 0
                            ]
                        ],
                        'sudut_kemiringan_akhir' => [
                            'label' => 'Sudut Kemiringan Akhir',
                            'type' => 'number',
                            'satuan' => '°',
                            'required' => false,
                            'config' => [
                                'min' => 0,
                                'max' => 90
                            ]
                        ],
                        'jenis_alat_berat' => [
                            'label' => 'Jenis Alat Berat yang Digunakan',
                            'type' => 'select',
                            'required' => false,
                            'config' => [
                                'options' => ['Bulldozer', 'Excavator', 'Motor Grader', 'Wheel Loader', 'Dump Truck']
                            ]
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
                            'config' => [
                                'required' => true,
                                'min' => 0,
                                'step' => 0.01
                            ]
                        ],
                        'volume_material_timbunan' => [
                            'label' => 'Volume Material Timbunan',
                            'type' => 'number',
                            'satuan' => 'm³',
                            'required' => false,
                            'config' => [
                                'min' => 0
                            ]
                        ],
                        'ketinggian_timbunan_rata' => [
                            'label' => 'Ketinggian Timbunan Rata-rata',
                            'type' => 'number',
                            'satuan' => 'm',
                            'required' => false,
                            'config' => [
                                'min' => 0
                            ]
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
                            'config' => [
                                'required' => true,
                                'min' => 0,
                                'step' => 0.01
                            ]
                        ],
                        'volume_topsoil_dikupas' => [
                            'label' => 'Volume Tanah Pucuk yang Dikupas',
                            'type' => 'number',
                            'satuan' => 'm³',
                            'required' => false,
                            'config' => [
                                'min' => 0
                            ]
                        ],
                        'ketebalan_topsoil_rata' => [
                            'label' => 'Ketebalan Tanah Pucuk Rata-rata',
                            'type' => 'number',
                            'satuan' => 'cm',
                            'required' => false,
                            'config' => [
                                'min' => 0,
                                'max' => 200
                            ]
                        ],
                        'lokasi_stockpile' => [
                            'label' => 'Lokasi Penumpukan (Stockpile)',
                            'type' => 'text',
                            'required' => false,
                            'config' => [
                                'max_length' => 255
                            ]
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
                            'config' => [
                                'required' => true,
                                'min' => 0
                            ]
                        ],
                        'luas_area_disebar' => [
                            'label' => 'Luas Area Penyebaran',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'required' => false,
                            'config' => [
                                'min' => 0,
                                'step' => 0.01
                            ]
                        ],
                        'ketebalan_sebaran_rata' => [
                            'label' => 'Ketebalan Sebaran Rata-rata',
                            'type' => 'number',
                            'satuan' => 'cm',
                            'required' => false,
                            'config' => [
                                'min' => 0,
                                'max' => 100
                            ]
                        ],
                        'kondisi_topsoil' => [
                            'label' => 'Kondisi Tanah Pucuk',
                            'type' => 'select',
                            'required' => false,
                            'config' => [
                                'options' => ['Segar', 'Tersimpan < 6 bulan', 'Tersimpan > 6 bulan']
                            ]
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
                            'config' => [
                                'required' => true,
                                'min' => 0
                            ]
                        ],
                        'lebar_saluran' => [
                            'label' => 'Lebar',
                            'type' => 'number',
                            'satuan' => 'm',
                            'required' => false,
                            'config' => [
                                'min' => 0,
                                'step' => 0.1
                            ]
                        ],
                        'kedalaman_saluran' => [
                            'label' => 'Kedalaman Saluran',
                            'type' => 'number',
                            'satuan' => 'm',
                            'required' => false,
                            'config' => [
                                'min' => 0,
                                'step' => 0.1
                            ]
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
                            'config' => [
                                'required' => true,
                                'min' => 0
                            ]
                        ],
                        'tinggi_check_dam' => [
                            'label' => 'Tinggi Check Dam',
                            'type' => 'number',
                            'satuan' => 'm',
                            'required' => false,
                            'config' => [
                                'min' => 0,
                                'step' => 0.1
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
                    'label' => 'Penanaman Cover Crops',
                    'fields' => [
                        'luas_area_ditanam' => [
                            'label' => 'Luas Area yang Ditanam',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'cover_crops',
                            'required' => true,
                            'config' => [
                                'required' => true,
                                'min' => 0,
                                'step' => 0.01
                            ]
                        ],
                        'jenis_tanaman_cover' => [
                            'label' => 'Jenis Tanaman Cover Crops',
                            'type' => 'text',
                            'required' => false,
                            'config' => [
                                'max_length' => 255
                            ]
                        ],
                        'dosis_benih_per_ha' => [
                            'label' => 'Dosis Benih per Hektar',
                            'type' => 'number',
                            'satuan' => 'kg/ha',
                            'required' => false,
                            'config' => [
                                'min' => 0
                            ]
                        ],
                        'metode_penanaman' => [
                            'label' => 'Metode Penanaman',
                            'type' => 'select',
                            'required' => false,
                            'config' => [
                                'options' => ['Tabur Langsung', 'Drill Seeding', 'Hydroseeding', 'Manual']
                            ]
                        ],
                    ],
                ],
            ],
        ],

        'pohon_pionir' => [
            'label' => 'Penanaman Pohon Pionir',
            'activities' => [
                'penanaman_pohon_pionir' => [
                    'label' => 'Penanaman Pohon Pionir',
                    'fields' => [
                        'luas_area_penanaman' => [
                            'label' => 'Luas Area Penanaman',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'pohon_pionir',
                            'required' => true,
                            'config' => [
                                'required' => true,
                                'min' => 0,
                                'step' => 0.01
                            ]
                        ],
                        'jumlah_bibit_ditanam' => [
                            'label' => 'Jumlah Bibit yang Ditanam',
                            'type' => 'number',
                            'satuan' => 'batang',
                            'required' => true, // Keep required for tracking
                            'config' => [
                                'required' => true,
                                'min' => 1
                            ]
                        ],
                        'kerapatan_tanam' => [
                            'label' => 'Kerapatan Tanam (bibit/ha)',
                            'type' => 'number',
                            'satuan' => 'batang/ha',
                            'required' => false,
                            'config' => [
                                'min' => 100,
                                'max' => 10000
                            ]
                        ],
                        'spesies_pohon_pionir' => [
                            'label' => 'Spesies Pohon Pionir',
                            'type' => 'select',
                            'required' => false,
                            'config' => [
                                'options' => ['Sengon (Albizia chinensis)', 'Jabon (Neolamarckia cadamba)', 'Kaliandra (Calliandra calothyrsus)', 'Mahoni (Swietenia mahagoni)', 'Lainnya']
                            ]
                        ],
                        'jarak_tanam' => [
                            'label' => 'Jarak Tanam',
                            'type' => 'text',
                            'required' => false,
                            'config' => [
                                'max_length' => 50
                            ]
                        ],
                    ],
                ],
            ],
        ],

        'pohon_lokal' => [
            'label' => 'Penanaman Pohon Lokal',
            'activities' => [
                'penanaman_pohon_lokal' => [
                    'label' => 'Penanaman Pohon Lokal/Native',
                    'fields' => [
                        'luas_area_penanaman' => [
                            'label' => 'Luas Area Penanaman',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'pohon_lokal',
                            'required' => true,
                            'config' => [
                                'required' => true,
                                'min' => 0,
                                'step' => 0.01
                            ]
                        ],
                        'jumlah_bibit_ditanam' => [
                            'label' => 'Jumlah Bibit yang Ditanam',
                            'type' => 'number',
                            'satuan' => 'batang',
                            'required' => true, // Keep required for tracking
                            'config' => [
                                'required' => true,
                                'min' => 1
                            ]
                        ],
                        'kerapatan_tanam' => [
                            'label' => 'Kerapatan Tanam (bibit/ha)',
                            'type' => 'number',
                            'satuan' => 'batang/ha',
                            'required' => false,
                            'config' => [
                                'min' => 100,
                                'max' => 10000
                            ]
                        ],
                        'spesies_pohon_lokal' => [
                            'label' => 'Spesies Pohon Lokal/Native',
                            'type' => 'text',
                            'required' => false,
                            'config' => [
                                'max_length' => 255
                            ]
                        ],
                        'jarak_tanam' => [
                            'label' => 'Jarak Tanam',
                            'type' => 'text',
                            'required' => false,
                            'config' => [
                                'max_length' => 50
                            ]
                        ],
                    ],
                ],
            ],
        ],

        'pemeliharaan' => [
            'label' => 'Pemeliharaan Tanaman',
            'activities' => [
                'penyiangan_gulma' => [
                    'label' => 'Penyiangan Gulma',
                    'fields' => [
                        'luas_area_disiangi' => [
                            'label' => 'Luas Area yang Disiangi',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'pemeliharaan_tanaman',
                            'required' => true,
                            'config' => [
                                'required' => true,
                                'min' => 0,
                                'step' => 0.01
                            ]
                        ],
                        'metode_penyiangan' => [
                            'label' => 'Metode Penyiangan',
                            'type' => 'select',
                            'required' => false,
                            'config' => [
                                'options' => ['Manual/Cangkul', 'Mesin Potong Rumput', 'Herbisida', 'Kombinasi']
                            ]
                        ],
                        'tingkat_gulma' => [
                            'label' => 'Tingkat Serangan Gulma',
                            'type' => 'select',
                            'required' => false,
                            'config' => [
                                'options' => ['Ringan (<25%)', 'Sedang (25-50%)', 'Berat (>50%)']
                            ]
                        ],
                    ],
                ],
                'pemupukan_tanaman' => [
                    'label' => 'Pemupukan Tanaman',
                    'fields' => [
                        'luas_area_dipupuk' => [
                            'label' => 'Luas Area yang Dipupuk',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'pemeliharaan_tanaman',
                            'required' => true,
                            'config' => [
                                'required' => true,
                                'min' => 0,
                                'step' => 0.01
                            ]
                        ],
                        'total_pupuk_digunakan' => [
                            'label' => 'Total Pupuk yang Digunakan',
                            'type' => 'number',
                            'satuan' => 'kg',
                            'required' => false,
                            'config' => [
                                'min' => 0
                            ]
                        ],
                        'jenis_pupuk_utama' => [
                            'label' => 'Jenis Pupuk Utama',
                            'type' => 'select',
                            'required' => false,
                            'config' => [
                                'options' => ['Pupuk Organik/Kompos', 'NPK', 'Urea', 'TSP/SP36', 'Pupuk Kandang']
                            ]
                        ],
                        'metode_aplikasi' => [
                            'label' => 'Metode Aplikasi Pupuk',
                            'type' => 'select',
                            'required' => false,
                            'config' => [
                                'options' => ['Tugal/Kocor', 'Sebar', 'Kocor Cair', 'Semprot Foliar']
                            ]
                        ],
                    ],
                ],
                'pengendalian_hama_penyakit' => [
                    'label' => 'Pengendalian Hama & Penyakit',
                    'fields' => [
                        'luas_area_dilindungi' => [
                            'label' => 'Luas Area yang Dilindungi',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'pemeliharaan_tanaman',
                            'required' => true,
                            'config' => [
                                'required' => true,
                                'min' => 0,
                                'step' => 0.01
                            ]
                        ],
                        'jenis_hama_penyakit' => [
                            'label' => 'Jenis Hama/Penyakit yang Menyerang',
                            'type' => 'text',
                            'required' => false,
                            'config' => [
                                'max_length' => 255
                            ]
                        ],
                        'bahan_pengendalian' => [
                            'label' => 'Bahan Pengendalian',
                            'type' => 'select',
                            'required' => false,
                            'config' => [
                                'options' => ['Pestisida Organik', 'Pestisida Kimia', 'Pengendalian Biologis', 'Perangkap']
                            ]
                        ],
                        'tingkat_serangan' => [
                            'label' => 'Tingkat Serangan',
                            'type' => 'select',
                            'required' => false,
                            'config' => [
                                'options' => ['Ringan (<25%)', 'Sedang (25-50%)', 'Berat (>50%)']
                            ]
                        ],
                    ],
                ],
                'penyulaman_tanaman' => [
                    'label' => 'Penyulaman Tanaman Mati',
                    'fields' => [
                        'luas_area_disulam' => [
                            'label' => 'Luas Area yang Disulam',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'pemeliharaan_tanaman',
                            'required' => true,
                            'config' => [
                                'required' => true,
                                'min' => 0,
                                'step' => 0.01
                            ]
                        ],
                        'jumlah_bibit_sulaman' => [
                            'label' => 'Jumlah Bibit untuk Sulaman',
                            'type' => 'number',
                            'satuan' => 'batang',
                            'required' => false,
                            'config' => [
                                'min' => 0
                            ]
                        ],
                        'persentase_kematian' => [
                            'label' => 'Persentase Kematian Tanaman',
                            'type' => 'number',
                            'satuan' => '%',
                            'required' => false,
                            'config' => [
                                'min' => 0,
                                'max' => 100
                            ]
                        ],
                        'penyebab_kematian' => [
                            'label' => 'Penyebab Kematian Utama',
                            'type' => 'select',
                            'required' => false,
                            'config' => [
                                'options' => ['Kekeringan', 'Serangan Hama', 'Penyakit', 'Persaingan Gulma', 'Kerusakan Fisik']
                            ]
                        ],
                    ],
                ],
            ],
        ],

        'monitoring' => [
            'label' => 'Monitoring & Evaluasi',
            'activities' => [
                'monitoring_survival_rate' => [
                    'label' => 'Monitoring Tingkat Kelangsungan Hidup (Survival Rate)',
                    'fields' => [
                        'luas_area_sampling' => [
                            'label' => 'Luas Area Sampling',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'monitoring_tanaman',
                            'required' => true,
                            'config' => [
                                'required' => true,
                                'min' => 0,
                                'step' => 0.01
                            ]
                        ],
                        'umur_tanaman_bulan' => [
                            'label' => 'Umur Tanaman (bulan)',
                            'type' => 'number',
                            'satuan' => 'bulan',
                            'required' => true,
                            'config' => [
                                'required' => true,
                                'min' => 1,
                                'max' => 120
                            ]
                        ],
                        'jumlah_bibit_hidup' => [
                            'label' => 'Jumlah Bibit yang Masih Hidup',
                            'type' => 'number',
                            'satuan' => 'batang',
                            'required' => true,
                            'config' => [
                                'required' => true,
                                'min' => 0
                            ]
                        ],
                        'jumlah_bibit_mati' => [
                            'label' => 'Jumlah Bibit yang Mati',
                            'type' => 'number',
                            'satuan' => 'batang',
                            'required' => true,
                            'config' => [
                                'required' => true,
                                'min' => 0
                            ]
                        ],
                    ],
                ],
                'monitoring_pertumbuhan' => [
                    'label' => 'Monitoring Pertumbuhan Tanaman',
                    'fields' => [
                        'luas_area_sampling' => [
                            'label' => 'Luas Area Sampling',
                            'type' => 'number',
                            'satuan' => 'ha',
                            'indicator_key' => 'monitoring_tanaman',
                            'required' => true,
                            'config' => [
                                'required' => true,
                                'min' => 0,
                                'step' => 0.01
                            ]
                        ],
                        'tinggi_tanaman_rata' => [
                            'label' => 'Tinggi Tanaman Rata-rata',
                            'type' => 'number',
                            'satuan' => 'cm',
                            'required' => true,
                            'config' => [
                                'required' => true,
                                'min' => 0
                            ]
                        ],
                        'diameter_batang_rata' => [
                            'label' => 'Diameter Batang Rata-rata',
                            'type' => 'number',
                            'satuan' => 'mm',
                            'required' => false,
                            'config' => [
                                'min' => 0
                            ]
                        ],
                        'persentase_tutupan_vegetasi' => [
                            'label' => 'Persentase Tutupan Vegetasi',
                            'type' => 'number',
                            'satuan' => '%',
                            'required' => false,
                            'config' => [
                                'min' => 0,
                                'max' => 100
                            ]
                        ],
                        'jumlah_sampel_diukur' => [
                            'label' => 'Jumlah Sampel yang Diukur',
                            'type' => 'number',
                            'satuan' => 'batang',
                            'required' => false,
                            'config' => [
                                'min' => 1
                            ]
                        ],
                        'kondisi_kesehatan_tanaman' => [
                            'label' => 'Kondisi Kesehatan Tanaman',
                            'type' => 'select',
                            'required' => false,
                            'config' => [
                                'options' => ['Sangat Baik', 'Baik', 'Sedang', 'Kurang Baik', 'Buruk']
                            ]
                        ],
                    ],
                ],
            ],
        ],
    ],
];
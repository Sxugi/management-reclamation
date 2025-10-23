<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IndikatorProgresReklamasi;
use App\Models\KategoriAktivitas;
use App\Models\JenisAktivitas;
use App\Models\FieldDefinition;

class IndicatorsSeeder extends Seeder
{
    public function run()
    {
        $config = config('indicators');

        foreach ($config['targets'] as $key => $target) {
            IndikatorProgresReklamasi::updateOrCreate(
                ['nama' => $key],
                [
                    'label' => $target['label'],
                    'satuan' => $target['satuan'],
                    'is_active' => true,
                ]
            );
        }

        foreach ($config['categories'] as $categoryKey => $category) {
            $kategori = KategoriAktivitas::updateOrCreate(
                ['field' => $categoryKey],
                ['label' => $category['label']]
            );

            foreach ($category['activities'] as $activityKey => $activity) {
                $jenis = JenisAktivitas::updateOrCreate(
                    [
                        'kategori_id' => $kategori->kategori_id,
                        'field' => $activityKey,
                    ],
                    [
                        'label' => $activity['label'],
                    ]
                );

                foreach ($activity['fields'] as $fieldKey => $field) {
                    FieldDefinition::updateOrCreate(
                        [
                            'jenis_aktivitas_id' => $jenis->jenis_aktivitas_id,
                            'field_key' => $fieldKey,
                        ],
                        [
                            'field_label' => $field['label'],
                            'field_type' => $field['type'],
                            'satuan' => $field['satuan'] ?? null,
                            'indicator_key' => $field['indicator_key'] ?? null,
                        ]
                    );
                }
            }
        }
    }
}
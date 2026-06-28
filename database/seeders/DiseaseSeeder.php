<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Disease;

class DiseaseSeeder extends Seeder
{
    /**
     * Seed tabel diseases dengan 4 data penyakit.
     */
    public function run(): void
    {
        Disease::upsert([
            [
                'name'        => 'Sehat',
                'slug'        => 'healthy',
                'description' => 'Tanaman pakcoy dalam kondisi sehat dan tidak terdeteksi penyakit.',
            ],
            [
                'name'        => 'Bercak Daun',
                'slug'        => 'bercak',
                'description' => 'Penyakit yang ditandai dengan munculnya bercak-bercak pada permukaan daun, biasanya disebabkan oleh jamur atau bakteri.',
            ],
            [
                'name'        => 'Berlubang',
                'slug'        => 'berlubang',
                'description' => 'Kerusakan daun berupa lubang-lubang yang umumnya disebabkan oleh serangan hama seperti ulat atau belalang.',
            ],
            [
                'name'        => 'Busuk',
                'slug'        => 'busuk',
                'description' => 'Pembusukan pada bagian daun atau batang yang disebabkan oleh infeksi bakteri atau jamur yang parah.',
            ],
        ], ['slug'], ['name', 'description']);
    }
}

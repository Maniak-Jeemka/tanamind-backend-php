<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Disease;
use App\Models\Recommendation;

class RecommendationSeeder extends Seeder
{
    /**
     * Seed tabel recommendations dengan data penanganan per penyakit & severity.
     */
    public function run(): void
    {
        $bercak    = Disease::where('slug', 'bercak')->first()->id;
        $berlubang = Disease::where('slug', 'berlubang')->first()->id;
        $busuk     = Disease::where('slug', 'busuk')->first()->id;

        Recommendation::upsert([
            // --- Bercak Daun ---
            [
                'disease_id' => $bercak,
                'severity'   => 'ringan',
                'action'     => 'Potong dan buang daun yang terinfeksi. Kurangi kelembaban area tanam dengan mengatur sirkulasi udara.',
                'prevention' => 'Pastikan jarak tanam tidak terlalu rapat. Hindari menyiram langsung ke daun.',
            ],
            [
                'disease_id' => $bercak,
                'severity'   => 'sedang',
                'action'     => 'Semprotkan fungisida berbahan aktif mankozeb atau tembaga hidroksida setiap 5-7 hari. Buang semua daun yang terinfeksi.',
                'prevention' => 'Rotasi tanaman secara berkala. Sterilkan alat berkebun sebelum digunakan.',
            ],
            [
                'disease_id' => $bercak,
                'severity'   => 'parah',
                'action'     => 'Cabut dan musnahkan tanaman yang terinfeksi parah untuk mencegah penyebaran. Sterilkan media tanam sebelum menanam kembali.',
                'prevention' => 'Gunakan bibit yang sudah terbukti tahan penyakit. Terapkan sistem monitoring rutin.',
            ],

            // --- Berlubang ---
            [
                'disease_id' => $berlubang,
                'severity'   => 'ringan',
                'action'     => 'Periksa bagian bawah daun untuk menemukan hama. Ambil dan singkirkan hama secara manual.',
                'prevention' => 'Pasang perangkap hama di sekitar area tanam. Periksa tanaman setiap pagi.',
            ],
            [
                'disease_id' => $berlubang,
                'severity'   => 'sedang',
                'action'     => 'Semprotkan insektisida organik berbahan nimba (neem oil) atau spinosad. Ulangi setiap 3-5 hari.',
                'prevention' => 'Gunakan penutup jaring halus pada tanaman untuk mencegah serangga masuk.',
            ],
            [
                'disease_id' => $berlubang,
                'severity'   => 'parah',
                'action'     => 'Aplikasikan insektisida kimia sesuai dosis anjuran. Isolasi tanaman yang terinfeksi parah dari tanaman sehat.',
                'prevention' => 'Evaluasi sistem perlindungan hama secara keseluruhan. Pertimbangkan penggunaan predator alami.',
            ],

            // --- Busuk ---
            [
                'disease_id' => $busuk,
                'severity'   => 'ringan',
                'action'     => 'Kurangi frekuensi penyiraman. Potong bagian yang mulai membusuk menggunakan gunting steril.',
                'prevention' => 'Pastikan drainase media tanam berjalan lancar. Hindari genangan air.',
            ],
            [
                'disease_id' => $busuk,
                'severity'   => 'sedang',
                'action'     => 'Semprotkan bakterisida atau fungisida sistemik. Perbaiki sirkulasi udara dan kurangi kelembaban secara signifikan.',
                'prevention' => 'Gunakan media tanam yang steril dan memiliki drainase baik.',
            ],
            [
                'disease_id' => $busuk,
                'severity'   => 'parah',
                'action'     => 'Segera cabut dan musnahkan tanaman. Jangan komposter sisa tanaman yang terinfeksi. Sterilkan seluruh sistem hidroponik dengan larutan klorin 1%.',
                'prevention' => 'Lakukan sanitasi sistem tanam secara menyeluruh sebelum menanam batch berikutnya.',
            ],
        ], ['disease_id', 'severity'], ['action', 'prevention']);
    }
}

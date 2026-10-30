<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeds all UMKM product categories for Griya Kreatif Deyalina
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Kue Kering dan Bakery',
                'slug' => 'kue-kering-bakery',
                'description' => 'Produk kue kering, cookies, brownies, dan bakery lainnya dengan sertifikat halal MUI dan P-IRT'
            ],
            [
                'name' => 'Produk Catering',
                'slug' => 'produk-catering',
                'description' => 'Menu catering lengkap untuk acara, termasuk nasi, lauk pauk, dan pelengkap'
            ],
            [
                'name' => 'Kue Basah',
                'slug' => 'kue-basah',
                'description' => 'Kue basah segar seperti pie susu, bolen, dadar gulung, dan kue sus'
            ],
            [
                'name' => 'Jajanan Lainnya',
                'slug' => 'jajanan-lainnya',
                'description' => 'Berbagai jajanan kemasan seperti Beng-beng, Nextar, Nabati, Fitbar, dll'
            ],
            [
                'name' => 'Bumbu dan Olahan Khas',
                'slug' => 'bumbu-olahan-khas',
                'description' => 'Sambal pecel, bumbu sate, bawang goreng, abon, keripik, dan olahan khas lainnya dengan P-IRT'
            ],
            [
                'name' => 'Mukena dan Perlengkapan Ibadah',
                'slug' => 'mukena-perlengkapan-ibadah',
                'description' => 'Mukena Dalanova, kerudung, khimar, scarf, dan gamis'
            ],
            [
                'name' => 'Baju Keluarga',
                'slug' => 'baju-keluarga',
                'description' => 'Kaos, kemeja, hem, dan koko untuk seluruh anggota keluarga'
            ],
            [
                'name' => 'Buku-Buku',
                'slug' => 'buku-buku',
                'description' => 'Koleksi buku anak, parenting, dan inspiratif'
            ],
            [
                'name' => 'Stok Gudang',
                'slug' => 'stok-gudang',
                'description' => 'Tupperware, eco bottle, tumbler, serving, dan picnic set'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
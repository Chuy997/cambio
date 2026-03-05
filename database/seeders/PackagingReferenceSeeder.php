<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PackagingReference;

class PackagingReferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['code' => '4841B6009JV', 'type' => 'BOX', 'dimensions' => null],
            // Indirecto CAJA Column 1
            ['code' => 'MR207', 'type' => 'CAJA', 'dimensions' => null],
            ['code' => 'MR 147', 'type' => 'CAJA', 'dimensions' => '772 X 580 X 350'],
            ['code' => 'MR 279', 'type' => 'CAJA', 'dimensions' => '670 x 430 x 170'],
            ['code' => 'MR 240', 'type' => 'CAJA', 'dimensions' => '650 X 570 X 175'],
            ['code' => 'MR 246', 'type' => 'CAJA', 'dimensions' => '650 X 550 X 175'],
            ['code' => 'MR 207', 'type' => 'CAJA', 'dimensions' => '625 X 570 X 370'],
            ['code' => 'MR 208', 'type' => 'CAJA', 'dimensions' => '625 X 570 X 275'],
            ['code' => 'MR 152', 'type' => 'CAJA', 'dimensions' => '615 X 465 X 340'],
            ['code' => 'MR 154', 'type' => 'CAJA', 'dimensions' => '605 X 390 X 445'],
            ['code' => 'MR 157', 'type' => 'CAJA', 'dimensions' => '600 X 400 X 355'],
            ['code' => 'MR 474', 'type' => 'CAJA', 'dimensions' => '560 X 310 X 160'],
            ['code' => 'MR 362', 'type' => 'CAJA', 'dimensions' => '560 X 245 X 90'],
            ['code' => 'MR 529', 'type' => 'CAJA', 'dimensions' => '550 X 360 X 90'],
            ['code' => 'MR 689', 'type' => 'CAJA', 'dimensions' => '525 X 430 X 355'],
            ['code' => 'MR 465', 'type' => 'CAJA', 'dimensions' => '525 X 425 X 205'],
            ['code' => 'MR 487', 'type' => 'CAJA', 'dimensions' => '525 X 425 X 130'],
            ['code' => 'MR 171', 'type' => 'CAJA', 'dimensions' => '510 X 360 X 230'],
            ['code' => 'MR 624', 'type' => 'CAJA', 'dimensions' => '490 X 380 X 125'],
            ['code' => 'MR 560', 'type' => 'CAJA', 'dimensions' => '480 X 390 X 330'],
            ['code' => 'MR 199', 'type' => 'CAJA', 'dimensions' => '465 X 335 X 110'],

            // Indirecto CAJA Column 2
            ['code' => 'MR 563', 'type' => 'CAJA', 'dimensions' => '785 X 405 X 735'],
            ['code' => 'MR 678', 'type' => 'CAJA', 'dimensions' => '410 X 220 X 100'],
            ['code' => 'MR 119', 'type' => 'CAJA', 'dimensions' => '400 X 335 X 110'],
            ['code' => 'MR 158', 'type' => 'CAJA', 'dimensions' => '400 X 220 X 120'],
            ['code' => 'MR 177', 'type' => 'CAJA', 'dimensions' => '395 X 395 X 345'],
            ['code' => 'MR 374', 'type' => 'CAJA', 'dimensions' => '395 X 395 X 225'],
            ['code' => 'MR 504', 'type' => 'CAJA', 'dimensions' => '390 X 270 X 50'],
            ['code' => 'MR 654', 'type' => 'CAJA', 'dimensions' => '389 X 375 X 255'],
            ['code' => 'MR 259', 'type' => 'CAJA', 'dimensions' => '385 X 355 X 100'],
            ['code' => 'MR 332', 'type' => 'CAJA', 'dimensions' => '380 X 370 X 150'],
            ['code' => 'MR 258', 'type' => 'CAJA', 'dimensions' => '375 X 465 X 340'],
            ['code' => 'MR 186', 'type' => 'CAJA', 'dimensions' => '375 X 375 X 375'],
            ['code' => 'MR 137', 'type' => 'CAJA', 'dimensions' => '375 X 375 X 350'],
            ['code' => 'MR 593', 'type' => 'CAJA', 'dimensions' => '375 X 375 X 225'],
            ['code' => 'MR 189', 'type' => 'CAJA', 'dimensions' => '375 X 365 X 235'],
            ['code' => 'MR 140', 'type' => 'CAJA', 'dimensions' => '370 X 360 X 115'],
            ['code' => 'MR 537', 'type' => 'CAJA', 'dimensions' => '345 X 260 X 95'],
            ['code' => 'MR 238', 'type' => 'CAJA', 'dimensions' => '340 X 250 X 90'],
            ['code' => 'MR 304', 'type' => 'CAJA', 'dimensions' => '265 X 175 X 50'],

            // GAYLOR
            ['code' => 'MR 673', 'type' => 'GAYLOR', 'dimensions' => '440 X 440 X 245'],
            ['code' => 'MLP410', 'type' => 'GAYLOR', 'dimensions' => '1200 X 840 X 800'],
            ['code' => 'MLP411', 'type' => 'GAYLOR', 'dimensions' => '1130 X 740 X 720'],
            ['code' => 'MLP412', 'type' => 'GAYLOR', 'dimensions' => '754 X 754 X 1154'],
            ['code' => 'MLP413', 'type' => 'GAYLOR', 'dimensions' => '1360 X 920 X 580'],
            ['code' => 'MLP450', 'type' => 'GAYLOR', 'dimensions' => '840 X 640 X 870'],
            ['code' => 'MLP455', 'type' => 'GAYLOR', 'dimensions' => '1200 X 800'],
            ['code' => 'MLP457', 'type' => 'GAYLOR', 'dimensions' => '815 X 615 X 730'],
            ['code' => 'MLP459', 'type' => 'GAYLOR', 'dimensions' => '830 X 730 X 716'],
            ['code' => 'MLP464', 'type' => 'GAYLOR', 'dimensions' => '744 X 730'],
            ['code' => 'MLP466', 'type' => 'GAYLOR', 'dimensions' => '830 X 730 X 990'],

             // TARIMA
            ['code' => 'MLP408', 'type' => 'TARIMA', 'dimensions' => '1200 X 800'],
            ['code' => 'MP203', 'type' => 'TARIMA', 'dimensions' => '1150 X 770'],
            ['code' => 'MP204', 'type' => 'TARIMA', 'dimensions' => '920 X 920'],
            ['code' => 'MP205', 'type' => 'TARIMA', 'dimensions' => '1000 X 1000'],
            ['code' => 'MP206', 'type' => 'TARIMA', 'dimensions' => '1100 X 1100'],
            ['code' => 'MP207', 'type' => 'TARIMA', 'dimensions' => '1150 X 710'],
            ['code' => 'MP208', 'type' => 'TARIMA', 'dimensions' => '1200 X 800'],
            ['code' => 'MP209', 'type' => 'TARIMA', 'dimensions' => '1200 X 1000'],
            ['code' => 'MP210', 'type' => 'TARIMA', 'dimensions' => '1360 X 920'],
            ['code' => 'MP211', 'type' => 'TARIMA', 'dimensions' => '908 X 760'],
            ['code' => 'MP212', 'type' => 'TARIMA', 'dimensions' => '1120 X 650'],
            ['code' => 'MP213', 'type' => 'TARIMA', 'dimensions' => '754 X 754'],
            ['code' => 'MP214', 'type' => 'TARIMA', 'dimensions' => '650 X 1120'],
            ['code' => 'MP215', 'type' => 'TARIMA', 'dimensions' => '1690 X 450'],
            ['code' => 'MP216', 'type' => 'TARIMA', 'dimensions' => '700 X 700'],
            ['code' => 'MP217', 'type' => 'TARIMA', 'dimensions' => '2130 X 600'],
            ['code' => 'MP218', 'type' => 'TARIMA', 'dimensions' => '720 X 614'],
            ['code' => 'MP219', 'type' => 'TARIMA', 'dimensions' => '770 X 730'],
            ['code' => 'MP220', 'type' => 'TARIMA', 'dimensions' => '714 X 614'],
            ['code' => 'MP221', 'type' => 'TARIMA', 'dimensions' => '960 X 670'],
            ['code' => 'MP287', 'type' => 'TARIMA', 'dimensions' => '1140 * 720'],

            // CRATE
            ['code' => 'MP202', 'type' => 'CRATE', 'dimensions' => '800 X 600'],
            ['code' => 'MS310', 'type' => 'CRATE', 'dimensions' => '2320 X 720 X 590'],
            ['code' => 'MS331', 'type' => 'CRATE', 'dimensions' => '2320 X 1180'],
            ['code' => 'MS338', 'type' => 'CRATE', 'dimensions' => '755 X 610 X 570']
        ];

        foreach ($data as $item) {
            PackagingReference::updateOrCreate(
                ['code' => $item['code']], // Search by Code
                ['type' => $item['type'], 'dimensions' => $item['dimensions']] // Update fields
            );
        }
    }
}

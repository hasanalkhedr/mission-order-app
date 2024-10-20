<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BaremeSeeder extends Seeder
{
    public function run()
    {
        DB::table('baremes')->insert([
            // [
            //     'pays' => 'USA',
            //     'currency' => 'USD',
            //     'pays_per_day' => 100.00,
            //     'meal_cost' => 30.00,
            //     'accomodation_cost' => 70.00,
            // ],
            // [
            //     'pays' => 'France',
            //     'currency' => 'EUR',
            //     'pays_per_day' => 90.00,
            //     'meal_cost' => 25.00,
            //     'accomodation_cost' => 65.00,
            // ],
            // [
            //     'pays' => 'UK',
            //     'currency' => 'GBP',
            //     'pays_per_day' => 85.00,
            //     'meal_cost' => 28.00,
            //     'accomodation_cost' => 57.00,
            // ],
            [ 'pays' => 'France Paris', 'currency' => 'EURO', 'pays_per_day' => '180.00', 'meal_cost' => '20.00', 'accomodation_cost' => '140.00' ],
            [ 'pays' => 'France (ville<200 000 habitants)', 'currency' => 'EURO', 'pays_per_day' => '130.00', 'meal_cost' => '20.00', 'accomodation_cost' => '90.00' ],
            [ 'pays' => 'France (>200 000 habitants)', 'currency' => 'EURO', 'pays_per_day' => '160.00', 'meal_cost' => '20.00', 'accomodation_cost' => '120.00' ],

            [ 'pays' => 'AFGHANISTAN', 'currency' => 'USD', 'pays_per_day' => '279', 'meal_cost' => '48.825', 'accomodation_cost' => '181.35' ],
[ 'pays' => 'AFRIQUE DU SUD', 'currency' => 'EURO', 'pays_per_day' => '138', 'meal_cost' => '24.15', 'accomodation_cost' => '89.7' ],
[ 'pays' => 'AFRIQUE DU SUD 15 DEC 1 MARS', 'currency' => 'EURO', 'pays_per_day' => '185', 'meal_cost' => '32.375', 'accomodation_cost' => '120.25' ],
[ 'pays' => 'ALBANIE', 'currency' => 'EURO', 'pays_per_day' => '130', 'meal_cost' => '22.75', 'accomodation_cost' => '84.5' ],
[ 'pays' => 'ALGERIE', 'currency' => 'DINAR ALGERIEN', 'pays_per_day' => '20480', 'meal_cost' => '3584', 'accomodation_cost' => '13312' ],
[ 'pays' => 'ALLEMAGNE', 'currency' => 'EURO', 'pays_per_day' => '164', 'meal_cost' => '28.7', 'accomodation_cost' => '106.6' ],
[ 'pays' => 'ANDORRE', 'currency' => 'EURO', 'pays_per_day' => '118', 'meal_cost' => '20.65', 'accomodation_cost' => '76.7' ],
[ 'pays' => 'ANGOLA', 'currency' => 'EURO', 'pays_per_day' => '300', 'meal_cost' => '52.5', 'accomodation_cost' => '195' ],
[ 'pays' => 'ANGUILLA', 'currency' => 'USD', 'pays_per_day' => '208', 'meal_cost' => '36.4', 'accomodation_cost' => '135.2' ],
[ 'pays' => 'ANTIGUA ET BARBUDA', 'currency' => 'USD', 'pays_per_day' => '308', 'meal_cost' => '53.9', 'accomodation_cost' => '200.2' ],
[ 'pays' => 'ARABIE SAOUDITE', 'currency' => 'EURO', 'pays_per_day' => '337', 'meal_cost' => '58.975', 'accomodation_cost' => '219.05' ],
[ 'pays' => 'ARGENTINE', 'currency' => 'USD', 'pays_per_day' => '157', 'meal_cost' => '27.475', 'accomodation_cost' => '102.05' ],
[ 'pays' => 'ARMENIE', 'currency' => 'EURO', 'pays_per_day' => '186', 'meal_cost' => '32.55', 'accomodation_cost' => '120.9' ],
[ 'pays' => 'ARUBA', 'currency' => 'USD', 'pays_per_day' => '150', 'meal_cost' => '26.25', 'accomodation_cost' => '97.5' ],
[ 'pays' => 'AUSTRALIE', 'currency' => 'DOLLAR AUSTRALIEN', 'pays_per_day' => '348', 'meal_cost' => '60.9', 'accomodation_cost' => '226.2' ],
[ 'pays' => 'AUTRICHE', 'currency' => 'EURO', 'pays_per_day' => '175', 'meal_cost' => '30.625', 'accomodation_cost' => '113.75' ],
[ 'pays' => 'AZERBAIDJAN', 'currency' => 'EURO', 'pays_per_day' => '204', 'meal_cost' => '35.7', 'accomodation_cost' => '132.6' ],
[ 'pays' => 'BAHAMAS', 'currency' => 'USD', 'pays_per_day' => '450', 'meal_cost' => '78.75', 'accomodation_cost' => '292.5' ],
[ 'pays' => 'BAHREIN', 'currency' => 'EURO', 'pays_per_day' => '200', 'meal_cost' => '35', 'accomodation_cost' => '130' ],
[ 'pays' => 'BANGLADESH', 'currency' => 'EURO', 'pays_per_day' => '258', 'meal_cost' => '45.15', 'accomodation_cost' => '167.7' ],
[ 'pays' => 'LA BARBADE', 'currency' => 'USD', 'pays_per_day' => '355', 'meal_cost' => '62.125', 'accomodation_cost' => '230.75' ],
[ 'pays' => 'BELGIQUE', 'currency' => 'EURO', 'pays_per_day' => '206', 'meal_cost' => '36.05', 'accomodation_cost' => '133.9' ],
[ 'pays' => 'BELIZE', 'currency' => 'USD', 'pays_per_day' => '177', 'meal_cost' => '30.975', 'accomodation_cost' => '115.05' ],
[ 'pays' => 'BENIN', 'currency' => 'EURO', 'pays_per_day' => '145', 'meal_cost' => '25.375', 'accomodation_cost' => '94.25' ],
[ 'pays' => 'BERMUDES', 'currency' => 'DOLLAR DES BERMUDES', 'pays_per_day' => '450', 'meal_cost' => '78.75', 'accomodation_cost' => '292.5' ],
[ 'pays' => 'BIELORUSSIE', 'currency' => 'EURO', 'pays_per_day' => '150', 'meal_cost' => '26.25', 'accomodation_cost' => '97.5' ],
[ 'pays' => 'BIRMANIE', 'currency' => 'USD', 'pays_per_day' => '250', 'meal_cost' => '43.75', 'accomodation_cost' => '162.5' ],
[ 'pays' => 'BOLIVIE', 'currency' => 'USD', 'pays_per_day' => '135', 'meal_cost' => '23.625', 'accomodation_cost' => '87.75' ],
[ 'pays' => 'BOSNIE-HERZEGOVINE', 'currency' => 'EURO', 'pays_per_day' => '169', 'meal_cost' => '29.575', 'accomodation_cost' => '109.85' ],
[ 'pays' => 'BOTSWANA', 'currency' => 'EURO', 'pays_per_day' => '119', 'meal_cost' => '20.825', 'accomodation_cost' => '77.35' ],
[ 'pays' => 'BRESIL', 'currency' => 'EURO', 'pays_per_day' => '216', 'meal_cost' => '37.8', 'accomodation_cost' => '140.4' ],
[ 'pays' => 'BRUNEI', 'currency' => 'DOLLAR DE BRUNEI', 'pays_per_day' => '255', 'meal_cost' => '44.625', 'accomodation_cost' => '165.75' ],
[ 'pays' => 'BULGARIE', 'currency' => 'EURO', 'pays_per_day' => '145', 'meal_cost' => '25.375', 'accomodation_cost' => '94.25' ],
[ 'pays' => 'BURKINA FASO', 'currency' => 'EURO', 'pays_per_day' => '145', 'meal_cost' => '25.375', 'accomodation_cost' => '94.25' ],
[ 'pays' => 'BURUNDI', 'currency' => 'EURO', 'pays_per_day' => '140', 'meal_cost' => '24.5', 'accomodation_cost' => '91' ],
[ 'pays' => 'CAIMANS (Iles)', 'currency' => 'USD', 'pays_per_day' => '450', 'meal_cost' => '78.75', 'accomodation_cost' => '292.5' ],
[ 'pays' => 'CAMBODGE', 'currency' => 'USD', 'pays_per_day' => '150', 'meal_cost' => '26.25', 'accomodation_cost' => '97.5' ],
[ 'pays' => 'CAMEROUN', 'currency' => 'EURO', 'pays_per_day' => '161', 'meal_cost' => '28.175', 'accomodation_cost' => '104.65' ],
[ 'pays' => 'CANADA ville de TORONTO', 'currency' => 'DOLLAR CANADIEN', 'pays_per_day' => '552', 'meal_cost' => '96.6', 'accomodation_cost' => '358.8' ],
[ 'pays' => 'CANADA ville de VANCOUVER', 'currency' => 'DOLLAR CANADIEN', 'pays_per_day' => '449', 'meal_cost' => '78.575', 'accomodation_cost' => '291.85' ],
[ 'pays' => 'CANADA AUTRES VILLES', 'currency' => 'DOLLAR CANADIEN', 'pays_per_day' => '319', 'meal_cost' => '55.825', 'accomodation_cost' => '207.35' ],
[ 'pays' => 'CAP-VERT', 'currency' => 'ESCUDO', 'pays_per_day' => '13575', 'meal_cost' => '2375.625', 'accomodation_cost' => '8823.75' ],
[ 'pays' => 'CENTRAFRICAINE (Republique)', 'currency' => 'FRANC CFA', 'pays_per_day' => '96000', 'meal_cost' => '16800', 'accomodation_cost' => '62400' ],
[ 'pays' => 'CHILI', 'currency' => 'USD', 'pays_per_day' => '217', 'meal_cost' => '37.975', 'accomodation_cost' => '141.05' ],
[ 'pays' => 'CHINE', 'currency' => 'YUAN CHINOIS (CNY)', 'pays_per_day' => '1700', 'meal_cost' => '297.5', 'accomodation_cost' => '1105' ],
[ 'pays' => 'CHYPRE', 'currency' => 'EURO', 'pays_per_day' => '190', 'meal_cost' => '33.25', 'accomodation_cost' => '123.5' ],
[ 'pays' => 'COLOMBIE', 'currency' => 'USD', 'pays_per_day' => '176', 'meal_cost' => '30.8', 'accomodation_cost' => '114.4' ],
[ 'pays' => 'COMORES', 'currency' => 'EURO', 'pays_per_day' => '150', 'meal_cost' => '26.25', 'accomodation_cost' => '97.5' ],
[ 'pays' => 'CONGO BRAZZAVILLE', 'currency' => 'FRANC CFA', 'pays_per_day' => '120000', 'meal_cost' => '21000', 'accomodation_cost' => '78000' ],
[ 'pays' => 'CONGO (Republique democratique du)', 'currency' => 'EURO', 'pays_per_day' => '240', 'meal_cost' => '42', 'accomodation_cost' => '156' ],
[ 'pays' => 'COOK (fles)', 'currency' => 'DOLLAR NEO-ZELANDAIS', 'pays_per_day' => '400', 'meal_cost' => '70', 'accomodation_cost' => '260' ],
[ 'pays' => 'COREE DU NORD', 'currency' => 'USD', 'pays_per_day' => '272', 'meal_cost' => '47.6', 'accomodation_cost' => '176.8' ],
[ 'pays' => 'COREE DU SUD', 'currency' => 'EURO', 'pays_per_day' => '210', 'meal_cost' => '36.75', 'accomodation_cost' => '136.5' ],
[ 'pays' => 'COSTA RICA', 'currency' => 'USD', 'pays_per_day' => '169', 'meal_cost' => '29.575', 'accomodation_cost' => '109.85' ],
[ 'pays' => 'COTE D\'IVOIRE', 'currency' => 'FRANC CFA', 'pays_per_day' => '137000', 'meal_cost' => '23975', 'accomodation_cost' => '89050' ],
[ 'pays' => 'CROATIE', 'currency' => 'EURO', 'pays_per_day' => '142', 'meal_cost' => '24.85', 'accomodation_cost' => '92.3' ],
[ 'pays' => 'CUBA', 'currency' => 'EURO', 'pays_per_day' => '200', 'meal_cost' => '35', 'accomodation_cost' => '130' ],
[ 'pays' => 'CU RAcAO', 'currency' => 'USD', 'pays_per_day' => '150', 'meal_cost' => '26.25', 'accomodation_cost' => '97.5' ],
[ 'pays' => 'DANEMARK', 'currency' => 'COURONNE DANOISE', 'pays_per_day' => '1660', 'meal_cost' => '290.5', 'accomodation_cost' => '1079' ],
[ 'pays' => 'DJIBOUTI', 'currency' => 'FRANC DJIBOUTI', 'pays_per_day' => '36320', 'meal_cost' => '6356', 'accomodation_cost' => '23608' ],
[ 'pays' => 'DOMINICAINE (Republique)', 'currency' => 'USD', 'pays_per_day' => '142', 'meal_cost' => '24.85', 'accomodation_cost' => '92.3' ],
[ 'pays' => 'LA DOMINIQUE', 'currency' => 'USD', 'pays_per_day' => '266', 'meal_cost' => '46.55', 'accomodation_cost' => '172.9' ],
[ 'pays' => 'EGYPTE', 'currency' => 'EURO', 'pays_per_day' => '148', 'meal_cost' => '25.9', 'accomodation_cost' => '96.2' ],
[ 'pays' => 'EMIRATSARABES UNIS', 'currency' => 'EURO', 'pays_per_day' => '300', 'meal_cost' => '52.5', 'accomodation_cost' => '195' ],
[ 'pays' => 'EQUATEUR', 'currency' => 'USD', 'pays_per_day' => '150', 'meal_cost' => '26.25', 'accomodation_cost' => '97.5' ],
[ 'pays' => 'ERYTHREE', 'currency' => 'USD', 'pays_per_day' => '195', 'meal_cost' => '34.125', 'accomodation_cost' => '126.75' ],
[ 'pays' => 'ESPAGNE', 'currency' => 'EURO', 'pays_per_day' => '212', 'meal_cost' => '37.1', 'accomodation_cost' => '137.8' ],
[ 'pays' => 'ESTONIE', 'currency' => 'EURO', 'pays_per_day' => '129', 'meal_cost' => '22.575', 'accomodation_cost' => '83.85' ],
[ 'pays' => 'ETATS-UNIS D\'AMERIQUE', 'currency' => 'USD', 'pays_per_day' => '360', 'meal_cost' => '63', 'accomodation_cost' => '234' ],
[ 'pays' => 'NEW YORK: janvier au 31 aout', 'currency' => 'USD', 'pays_per_day' => '360', 'meal_cost' => '63', 'accomodation_cost' => '234' ],
[ 'pays' => 'NEW YORK: septembre au 31 decembre', 'currency' => 'USD', 'pays_per_day' => '490', 'meal_cost' => '85.75', 'accomodation_cost' => '318.5' ],
[ 'pays' => 'ETHIOPIE', 'currency' => 'EURO', 'pays_per_day' => '123', 'meal_cost' => '21.525', 'accomodation_cost' => '79.95' ],
[ 'pays' => 'FIDJI', 'currency' => 'DOLLAR DE FIDJI', 'pays_per_day' => '421', 'meal_cost' => '73.675', 'accomodation_cost' => '273.65' ],
[ 'pays' => 'FINLANDE', 'currency' => 'EURO', 'pays_per_day' => '220', 'meal_cost' => '38.5', 'accomodation_cost' => '143' ],
[ 'pays' => 'GABON', 'currency' => 'FRANC CFA', 'pays_per_day' => '140000', 'meal_cost' => '24500', 'accomodation_cost' => '91000' ],
[ 'pays' => 'GAMBIE', 'currency' => 'DALASI', 'pays_per_day' => '8590', 'meal_cost' => '1503.25', 'accomodation_cost' => '5583.5' ],
[ 'pays' => 'GEORGIE', 'currency' => 'USD', 'pays_per_day' => '195', 'meal_cost' => '34.125', 'accomodation_cost' => '126.75' ],
[ 'pays' => 'GHANA', 'currency' => 'USD', 'pays_per_day' => '250', 'meal_cost' => '43.75', 'accomodation_cost' => '162.5' ],
[ 'pays' => 'GRANDE-BRETAGNE', 'currency' => 'GBP', 'pays_per_day' => '210', 'meal_cost' => '36.75', 'accomodation_cost' => '136.5' ],
[ 'pays' => 'GRECE', 'currency' => 'EURO', 'pays_per_day' => '167', 'meal_cost' => '29.225', 'accomodation_cost' => '108.55' ],
[ 'pays' => 'GRENADE', 'currency' => 'USD', 'pays_per_day' => '283', 'meal_cost' => '49.525', 'accomodation_cost' => '183.95' ],
[ 'pays' => 'GUATEMALA', 'currency' => 'EURO', 'pays_per_day' => '160', 'meal_cost' => '28', 'accomodation_cost' => '104' ],
[ 'pays' => 'GUINEE', 'currency' => 'EURO', 'pays_per_day' => '170', 'meal_cost' => '29.75', 'accomodation_cost' => '110.5' ],
[ 'pays' => 'GUINEE-BISSAU', 'currency' => 'EURO', 'pays_per_day' => '169', 'meal_cost' => '29.575', 'accomodation_cost' => '109.85' ],
[ 'pays' => 'GUINEE EQUATORIALE', 'currency' => 'FRANC CFA', 'pays_per_day' => '90500', 'meal_cost' => '15837.5', 'accomodation_cost' => '58825' ],
[ 'pays' => 'GUYANA', 'currency' => 'USD', 'pays_per_day' => '200', 'meal_cost' => '35', 'accomodation_cost' => '130' ],
[ 'pays' => 'HAITI', 'currency' => 'USD', 'pays_per_day' => '220', 'meal_cost' => '38.5', 'accomodation_cost' => '143' ],
[ 'pays' => 'HONDURAS', 'currency' => 'USD', 'pays_per_day' => '152', 'meal_cost' => '26.6', 'accomodation_cost' => '98.8' ],
[ 'pays' => 'HONG KONG', 'currency' => 'DOLLAR DE HONG KONG', 'pays_per_day' => '2200', 'meal_cost' => '385', 'accomodation_cost' => '1430' ],
[ 'pays' => 'HONGRIE', 'currency' => 'EURO', 'pays_per_day' => '175', 'meal_cost' => '30.625', 'accomodation_cost' => '113.75' ],
[ 'pays' => 'INDE', 'currency' => 'EURO', 'pays_per_day' => '210', 'meal_cost' => '36.75', 'accomodation_cost' => '136.5' ],
[ 'pays' => 'INDONESIE', 'currency' => 'EURO', 'pays_per_day' => '160', 'meal_cost' => '28', 'accomodation_cost' => '104' ],
[ 'pays' => 'IRAN', 'currency' => 'USD', 'pays_per_day' => '186', 'meal_cost' => '32.55', 'accomodation_cost' => '120.9' ],
[ 'pays' => 'IRAK', 'currency' => 'EURO', 'pays_per_day' => '300', 'meal_cost' => '52.5', 'accomodation_cost' => '195' ],
[ 'pays' => 'IRLANDE', 'currency' => 'EURO', 'pays_per_day' => '190', 'meal_cost' => '33.25', 'accomodation_cost' => '123.5' ],
[ 'pays' => 'IRLANDE', 'currency' => 'COURONNE ISLANDAISE', 'pays_per_day' => '34397', 'meal_cost' => '6019.475', 'accomodation_cost' => '22358.05' ],
[ 'pays' => 'ISRAEL', 'currency' => 'EURO', 'pays_per_day' => '230', 'meal_cost' => '40.25', 'accomodation_cost' => '149.5' ],
[ 'pays' => 'ITALIE', 'currency' => 'EURO', 'pays_per_day' => '220', 'meal_cost' => '38.5', 'accomodation_cost' => '143' ],
[ 'pays' => 'JAMAIQUE', 'currency' => 'USD', 'pays_per_day' => '217', 'meal_cost' => '37.975', 'accomodation_cost' => '141.05' ],
[ 'pays' => 'JAPON', 'currency' => 'YEN', 'pays_per_day' => '25500', 'meal_cost' => '4462.5', 'accomodation_cost' => '16575' ],
[ 'pays' => 'Ville de TOKYO', 'currency' => 'YEN', 'pays_per_day' => '30000', 'meal_cost' => '5250', 'accomodation_cost' => '19500' ],
[ 'pays' => 'JORDANIE', 'currency' => 'DINAR JORDANIEN', 'pays_per_day' => '151', 'meal_cost' => '26.425', 'accomodation_cost' => '98.15' ],
[ 'pays' => 'KAZAKHSTAN', 'currency' => 'EURO', 'pays_per_day' => '188', 'meal_cost' => '32.9', 'accomodation_cost' => '122.2' ],
[ 'pays' => 'KENYA', 'currency' => 'USD', 'pays_per_day' => '141', 'meal_cost' => '24.675', 'accomodation_cost' => '91.65' ],
[ 'pays' => 'KIRGHIZISTAN', 'currency' => 'EURO', 'pays_per_day' => '150', 'meal_cost' => '26.25', 'accomodation_cost' => '97.5' ],
[ 'pays' => 'KIRIBATI', 'currency' => 'DOLLAR AUSTRALIEN', 'pays_per_day' => '207', 'meal_cost' => '36.225', 'accomodation_cost' => '134.55' ],
[ 'pays' => 'KOSOVO', 'currency' => 'EURO', 'pays_per_day' => '150', 'meal_cost' => '26.25', 'accomodation_cost' => '97.5' ],
[ 'pays' => 'KOWEIT', 'currency' => 'EURO', 'pays_per_day' => '245', 'meal_cost' => '42.875', 'accomodation_cost' => '159.25' ],
[ 'pays' => 'LAOS', 'currency' => 'USD', 'pays_per_day' => '150', 'meal_cost' => '26.25', 'accomodation_cost' => '97.5' ],
[ 'pays' => 'LESOTHO', 'currency' => 'EURO', 'pays_per_day' => '120', 'meal_cost' => '21', 'accomodation_cost' => '78' ],
[ 'pays' => 'LETTONIE', 'currency' => 'EURO', 'pays_per_day' => '152', 'meal_cost' => '26.6', 'accomodation_cost' => '98.8' ],
[ 'pays' => 'LIBAN', 'currency' => 'EURO', 'pays_per_day' => '154', 'meal_cost' => '26.95', 'accomodation_cost' => '100.1' ],
[ 'pays' => 'LIBERIA', 'currency' => 'USD', 'pays_per_day' => '230', 'meal_cost' => '40.25', 'accomodation_cost' => '149.5' ],
[ 'pays' => 'LIBYE', 'currency' => 'EURO', 'pays_per_day' => '240', 'meal_cost' => '42', 'accomodation_cost' => '156' ],
[ 'pays' => 'LIECHTENSTEIN', 'currency' => 'FRANC SUISSE', 'pays_per_day' => '230', 'meal_cost' => '40.25', 'accomodation_cost' => '149.5' ],
[ 'pays' => 'LITUANIE', 'currency' => 'EURO', 'pays_per_day' => '145', 'meal_cost' => '25.375', 'accomodation_cost' => '94.25' ],
[ 'pays' => 'LUXEMBOURG', 'currency' => 'EURO', 'pays_per_day' => '227', 'meal_cost' => '39.725', 'accomodation_cost' => '147.55' ],
[ 'pays' => 'MACAO', 'currency' => 'DOLLAR DE HONG KONG', 'pays_per_day' => '2200', 'meal_cost' => '385', 'accomodation_cost' => '1430' ],
[ 'pays' => 'MACEDOINE', 'currency' => 'EURO', 'pays_per_day' => '117', 'meal_cost' => '20.475', 'accomodation_cost' => '76.05' ],
[ 'pays' => 'MADAGASCAR', 'currency' => 'EURO', 'pays_per_day' => '114', 'meal_cost' => '19.95', 'accomodation_cost' => '74.1' ],
[ 'pays' => 'MALAISIE', 'currency' => 'RINGGIT', 'pays_per_day' => '468', 'meal_cost' => '81.9', 'accomodation_cost' => '304.2' ],
[ 'pays' => 'MALAWI', 'currency' => 'USD', 'pays_per_day' => '214', 'meal_cost' => '37.45', 'accomodation_cost' => '139.1' ],
[ 'pays' => 'MALDIVES (fles)', 'currency' => 'EURO', 'pays_per_day' => '320', 'meal_cost' => '56', 'accomodation_cost' => '208' ],
[ 'pays' => 'MALI', 'currency' => 'FRANC CFA', 'pays_per_day' => '161600', 'meal_cost' => '28280', 'accomodation_cost' => '105040' ],
[ 'pays' => 'MALTE', 'currency' => 'EURO', 'pays_per_day' => '200', 'meal_cost' => '35', 'accomodation_cost' => '130' ],
[ 'pays' => 'MAROC', 'currency' => 'EURO', 'pays_per_day' => '175', 'meal_cost' => '30.625', 'accomodation_cost' => '113.75' ],
[ 'pays' => 'MARSHALL (fles)', 'currency' => 'USD', 'pays_per_day' => '154', 'meal_cost' => '26.95', 'accomodation_cost' => '100.1' ],
[ 'pays' => 'MAURICE', 'currency' => 'ROUPIE ILE MAURICE', 'pays_per_day' => '9450', 'meal_cost' => '1653.75', 'accomodation_cost' => '6142.5' ],
[ 'pays' => 'MAURITANIE', 'currency' => 'EURO', 'pays_per_day' => '143', 'meal_cost' => '25.025', 'accomodation_cost' => '92.95' ],
[ 'pays' => 'MEXIQUE', 'currency' => 'PESO MEXICAIN', 'pays_per_day' => '3800', 'meal_cost' => '665', 'accomodation_cost' => '2470' ],
[ 'pays' => 'MICRONESIE', 'currency' => 'USD', 'pays_per_day' => '157', 'meal_cost' => '27.475', 'accomodation_cost' => '102.05' ],
[ 'pays' => 'MOLDAVIE', 'currency' => 'USD', 'pays_per_day' => '188', 'meal_cost' => '32.9', 'accomodation_cost' => '122.2' ],
[ 'pays' => 'MONGOLIE EXTERIEURE', 'currency' => 'EURO', 'pays_per_day' => '102', 'meal_cost' => '17.85', 'accomodation_cost' => '66.3' ],
[ 'pays' => 'MONTENEGRO', 'currency' => 'EURO', 'pays_per_day' => '150', 'meal_cost' => '26.25', 'accomodation_cost' => '97.5' ],
[ 'pays' => 'MOZAMBIQUE', 'currency' => 'USD', 'pays_per_day' => '189', 'meal_cost' => '33.075', 'accomodation_cost' => '122.85' ],
[ 'pays' => 'NAMIBIE', 'currency' => 'EURO', 'pays_per_day' => '150', 'meal_cost' => '26.25', 'accomodation_cost' => '97.5' ],
[ 'pays' => 'NAURU', 'currency' => 'DOLLAR AUSTRALIEN', 'pays_per_day' => '600', 'meal_cost' => '105', 'accomodation_cost' => '390' ],
[ 'pays' => 'NEPAL', 'currency' => 'USD', 'pays_per_day' => '140', 'meal_cost' => '24.5', 'accomodation_cost' => '91' ],
[ 'pays' => 'NICARAGUA', 'currency' => 'USD', 'pays_per_day' => '154', 'meal_cost' => '26.95', 'accomodation_cost' => '100.1' ],
[ 'pays' => 'NIGER', 'currency' => 'FRANC CFA', 'pays_per_day' => '78000', 'meal_cost' => '13650', 'accomodation_cost' => '50700' ],
[ 'pays' => 'NIGERIA villes de ABUJA, LAGOS ET PORT-HARCOURT', 'currency' => 'EURO', 'pays_per_day' => '230', 'meal_cost' => '40.25', 'accomodation_cost' => '149.5' ],
[ 'pays' => 'NIGERIA AUTRES VILLES', 'currency' => 'EURO', 'pays_per_day' => '160', 'meal_cost' => '28', 'accomodation_cost' => '104' ],
[ 'pays' => 'NIUE', 'currency' => 'DOLLAR NEO-ZELANDAIS', 'pays_per_day' => '204', 'meal_cost' => '35.7', 'accomodation_cost' => '132.6' ],
[ 'pays' => 'NORVEGE', 'currency' => 'COURONNE NORVEGIENNE', 'pays_per_day' => '2700', 'meal_cost' => '472.5', 'accomodation_cost' => '1755' ],
[ 'pays' => 'NOUVELLE-ZELANDE', 'currency' => 'DOLLAR NEO-ZELANDAIS', 'pays_per_day' => '370', 'meal_cost' => '64.75', 'accomodation_cost' => '240.5' ],
[ 'pays' => 'OMAN', 'currency' => 'EURO', 'pays_per_day' => '265', 'meal_cost' => '46.375', 'accomodation_cost' => '172.25' ],
[ 'pays' => 'OUGANDA', 'currency' => 'EURO', 'pays_per_day' => '130', 'meal_cost' => '22.75', 'accomodation_cost' => '84.5' ],
[ 'pays' => 'OUZBEKISTAN', 'currency' => 'USD', 'pays_per_day' => '197', 'meal_cost' => '34.475', 'accomodation_cost' => '128.05' ],
[ 'pays' => 'PAKISTAN', 'currency' => 'USD', 'pays_per_day' => '173', 'meal_cost' => '30.275', 'accomodation_cost' => '112.45' ],
[ 'pays' => 'PALAOS (fles)', 'currency' => 'USD', 'pays_per_day' => '311', 'meal_cost' => '54.425', 'accomodation_cost' => '202.15' ],
[ 'pays' => 'PANAMA', 'currency' => 'USD', 'pays_per_day' => '178', 'meal_cost' => '31.15', 'accomodation_cost' => '115.7' ],
[ 'pays' => 'PAPOUASIE-NOUVELLE-GUINEE', 'currency' => 'EURO', 'pays_per_day' => '172', 'meal_cost' => '30.1', 'accomodation_cost' => '111.8' ],
[ 'pays' => 'PARAGUAY', 'currency' => 'USD', 'pays_per_day' => '180', 'meal_cost' => '31.5', 'accomodation_cost' => '117' ],
[ 'pays' => 'PAYS-BAS', 'currency' => 'EURO', 'pays_per_day' => '161', 'meal_cost' => '28.175', 'accomodation_cost' => '104.65' ],
[ 'pays' => 'PEROU', 'currency' => 'USD', 'pays_per_day' => '170', 'meal_cost' => '29.75', 'accomodation_cost' => '110.5' ],
[ 'pays' => 'PHILIPPINES', 'currency' => 'PESO PHILIPPIN', 'pays_per_day' => '8770', 'meal_cost' => '1534.75', 'accomodation_cost' => '5700.5' ],
[ 'pays' => 'POLOGNE', 'currency' => 'EURO', 'pays_per_day' => '175', 'meal_cost' => '30.625', 'accomodation_cost' => '113.75' ],
[ 'pays' => 'PORTUGAL', 'currency' => 'EURO', 'pays_per_day' => '160', 'meal_cost' => '28', 'accomodation_cost' => '104' ],
[ 'pays' => 'QATAR', 'currency' => 'EURO', 'pays_per_day' => '278', 'meal_cost' => '48.65', 'accomodation_cost' => '180.7' ],
[ 'pays' => 'ROUMANIE', 'currency' => 'EURO', 'pays_per_day' => '160', 'meal_cost' => '28', 'accomodation_cost' => '104' ],
[ 'pays' => 'RUSSIE', 'currency' => 'EURO', 'pays_per_day' => '230', 'meal_cost' => '40.25', 'accomodation_cost' => '149.5' ],
[ 'pays' => 'RWANDA', 'currency' => 'DOLLAR AMERICAIN', 'pays_per_day' => '170', 'meal_cost' => '29.75', 'accomodation_cost' => '110.5' ],
[ 'pays' => 'SAINT-CHRISTOPHE-ET-NIEVES', 'currency' => 'USD', 'pays_per_day' => '287', 'meal_cost' => '50.225', 'accomodation_cost' => '186.55' ],
[ 'pays' => 'SAINTE-LUCIE', 'currency' => 'USD', 'pays_per_day' => '261', 'meal_cost' => '45.675', 'accomodation_cost' => '169.65' ],
[ 'pays' => 'SAINT-VINCENT ET LES GRENADINES', 'currency' => 'USD', 'pays_per_day' => '275', 'meal_cost' => '48.125', 'accomodation_cost' => '178.75' ],
[ 'pays' => 'SALOMON', 'currency' => 'VATU', 'pays_per_day' => '23052', 'meal_cost' => '4034.1', 'accomodation_cost' => '14983.8' ],
[ 'pays' => 'SALVADOR', 'currency' => 'USD', 'pays_per_day' => '177', 'meal_cost' => '30.975', 'accomodation_cost' => '115.05' ],
[ 'pays' => 'SAMOA', 'currency' => 'USD', 'pays_per_day' => '230', 'meal_cost' => '40.25', 'accomodation_cost' => '149.5' ],
[ 'pays' => 'SAO TOME ET PRINCIPE', 'currency' => 'USD', 'pays_per_day' => '135', 'meal_cost' => '23.625', 'accomodation_cost' => '87.75' ],
[ 'pays' => 'SENEGAL', 'currency' => 'FRANC CFA', 'pays_per_day' => '91800', 'meal_cost' => '16065', 'accomodation_cost' => '59670' ],
[ 'pays' => 'SERBIE', 'currency' => 'EURO', 'pays_per_day' => '150', 'meal_cost' => '26.25', 'accomodation_cost' => '97.5' ],
[ 'pays' => 'SEYCHELLES', 'currency' => 'EURO', 'pays_per_day' => '300', 'meal_cost' => '52.5', 'accomodation_cost' => '195' ],
[ 'pays' => 'SIERRA LEONE', 'currency' => 'USD', 'pays_per_day' => '260', 'meal_cost' => '45.5', 'accomodation_cost' => '169' ],
[ 'pays' => 'SINGAPOUR', 'currency' => 'DOLLAR SINGAPOURIEN', 'pays_per_day' => '446', 'meal_cost' => '78.05', 'accomodation_cost' => '289.9' ],
[ 'pays' => 'SLOVAQUIE', 'currency' => 'EURO', 'pays_per_day' => '155', 'meal_cost' => '27.125', 'accomodation_cost' => '100.75' ],
[ 'pays' => 'SLOVENIE', 'currency' => 'EURO', 'pays_per_day' => '160', 'meal_cost' => '28', 'accomodation_cost' => '104' ],
[ 'pays' => 'SOMALIE', 'currency' => 'USD', 'pays_per_day' => '158', 'meal_cost' => '27.65', 'accomodation_cost' => '102.7' ],
[ 'pays' => 'SOUDAN', 'currency' => 'USD', 'pays_per_day' => '175', 'meal_cost' => '30.625', 'accomodation_cost' => '113.75' ],
[ 'pays' => 'Sud SOUDAN', 'currency' => 'USD', 'pays_per_day' => '306', 'meal_cost' => '53.55', 'accomodation_cost' => '198.9' ],
[ 'pays' => 'SRI LANKA', 'currency' => 'EURO', 'pays_per_day' => '180', 'meal_cost' => '31.5', 'accomodation_cost' => '117' ],
[ 'pays' => 'SUEDE', 'currency' => 'COURONNE SUEDOISE', 'pays_per_day' => '1997', 'meal_cost' => '349.475', 'accomodation_cost' => '1298.05' ],
[ 'pays' => 'SUISSE', 'currency' => 'FRANC SUISSE', 'pays_per_day' => '230', 'meal_cost' => '40.25', 'accomodation_cost' => '149.5' ],
[ 'pays' => 'SURINAME', 'currency' => 'USD', 'pays_per_day' => '180', 'meal_cost' => '31.5', 'accomodation_cost' => '117' ],
[ 'pays' => 'SWAZILAND', 'currency' => 'EURO', 'pays_per_day' => '138', 'meal_cost' => '24.15', 'accomodation_cost' => '89.7' ],
[ 'pays' => 'SYRIE', 'currency' => 'EURO', 'pays_per_day' => '154', 'meal_cost' => '26.95', 'accomodation_cost' => '100.1' ],
[ 'pays' => 'TADJ I KISTAN', 'currency' => 'USD', 'pays_per_day' => '250', 'meal_cost' => '43.75', 'accomodation_cost' => '162.5' ],
[ 'pays' => 'TAIWAN', 'currency' => 'DOLLAR DE TAIWAN', 'pays_per_day' => '5990', 'meal_cost' => '1048.25', 'accomodation_cost' => '3893.5' ],
[ 'pays' => 'TANZANIE', 'currency' => 'EURO', 'pays_per_day' => '135', 'meal_cost' => '23.625', 'accomodation_cost' => '87.75' ],
[ 'pays' => 'TCHAD', 'currency' => 'EURO', 'pays_per_day' => '225', 'meal_cost' => '39.375', 'accomodation_cost' => '146.25' ],
[ 'pays' => 'TCHEOUE (Republiauel', 'currency' => 'EURO', 'pays_per_day' => '180', 'meal_cost' => '31.5', 'accomodation_cost' => '117' ],
[ 'pays' => 'THAILANDE', 'currency' => 'BAHT', 'pays_per_day' => '5000', 'meal_cost' => '875', 'accomodation_cost' => '3250' ],
[ 'pays' => 'TIMOR oriental', 'currency' => 'EURO', 'pays_per_day' => '150', 'meal_cost' => '26.25', 'accomodation_cost' => '97.5' ],
[ 'pays' => 'TOGO ville de LOME', 'currency' => 'FRANC CFA', 'pays_per_day' => '95771', 'meal_cost' => '16759.925', 'accomodation_cost' => '62251.15' ],
[ 'pays' => 'TOGO AUTRES VILLES', 'currency' => 'FRANC CFA', 'pays_per_day' => '82640', 'meal_cost' => '14462', 'accomodation_cost' => '53716' ],
[ 'pays' => 'TONGA', 'currency' => 'DOLLAR DE FIDJI', 'pays_per_day' => '402', 'meal_cost' => '70.35', 'accomodation_cost' => '261.3' ],
[ 'pays' => 'TRINITE ET TOBAGO', 'currency' => 'USD', 'pays_per_day' => '267', 'meal_cost' => '46.725', 'accomodation_cost' => '173.55' ],
[ 'pays' => 'TUNISIE', 'currency' => 'EURO', 'pays_per_day' => '125', 'meal_cost' => '21.875', 'accomodation_cost' => '81.25' ],
[ 'pays' => 'TURKMENISTAN', 'currency' => 'EURO', 'pays_per_day' => '224', 'meal_cost' => '39.2', 'accomodation_cost' => '145.6' ],
[ 'pays' => 'TURQUIE', 'currency' => 'EURO', 'pays_per_day' => '135', 'meal_cost' => '23.625', 'accomodation_cost' => '87.75' ],
[ 'pays' => 'TUVALU', 'currency' => 'DOLLAR AUSTRALIEN', 'pays_per_day' => '254', 'meal_cost' => '44.45', 'accomodation_cost' => '165.1' ],
[ 'pays' => 'UKRAINE', 'currency' => 'EURO', 'pays_per_day' => '208', 'meal_cost' => '36.4', 'accomodation_cost' => '135.2' ],
[ 'pays' => 'URUGUAY', 'currency' => 'USD', 'pays_per_day' => '135', 'meal_cost' => '23.625', 'accomodation_cost' => '87.75' ],
[ 'pays' => 'VANUATU', 'currency' => 'EURO', 'pays_per_day' => '210', 'meal_cost' => '36.75', 'accomodation_cost' => '136.5' ],
[ 'pays' => 'VENEZUELA', 'currency' => 'EURO', 'pays_per_day' => '195', 'meal_cost' => '34.125', 'accomodation_cost' => '126.75' ],
[ 'pays' => 'VIETNAM', 'currency' => 'EURO', 'pays_per_day' => '158', 'meal_cost' => '27.65', 'accomodation_cost' => '102.7' ],
[ 'pays' => 'YEMEN', 'currency' => 'EURO', 'pays_per_day' => '188', 'meal_cost' => '32.9', 'accomodation_cost' => '122.2' ],
[ 'pays' => 'ZAMBIE', 'currency' => 'EURO', 'pays_per_day' => '180', 'meal_cost' => '31.5', 'accomodation_cost' => '117' ],
[ 'pays' => 'ZIMBABWE', 'currency' => 'USD', 'pays_per_day' => '180', 'meal_cost' => '31.5', 'accomodation_cost' => '117' ],
        ]);
    }
}

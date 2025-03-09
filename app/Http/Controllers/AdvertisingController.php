<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Str;
use App\Models\ResidentialDetail;
use App\Models\Owner;
use App\Models\Valuation;

class AdvertisingController extends Controller
{
    private $authHeader = [
        'Authorization' => '1034e75e-89c6-11e8-8a04-00155da2c015'
    ];

//    private $townships = [
//        '13-2S-6E',
//        '13-2S-7E',
//        '13-2S-8E',
//        '13-1S-3E',
//        '13-1S-4E',
//        '13-1S-5E',
//        '13-1S-6E',
//        '13-1S-7E',
//        '13-1N-3E',
//        '13-1N-4E',
//        '13-1N-5E',
//        '13-1N-6E',
//        '13-1N-7E',
//        '13-1N-8E',
//        '13-2N-4E',
//        '13-3N-4E'
//    ];

    private $townships = [
        '13-2S-6E'
    ];

    public function updateMailingList()
    {
//        dd('updateMailing');

        foreach ($this->townships as $township) {
            $this->fetchTownshipParcels($township);
        }

        $this->fetchParcelDetails();

        return response()->json(['message' => 'Mailing list updated successfully.']);
    }

    private function fetchTownshipParcels($township)
    {
        $page = 1;

        do {
            $response = Http::withHeaders($this->authHeader)
                ->get("https://mcassessor.maricopa.gov/parcel/str/{$township}/?page={$page}");

            $data = $response->json();

            if (empty($data)) {
                break;
            }

            foreach ($data['Parcels'] as $parcel) {
                Str::updateOrCreate(
                    ['apn' => $parcel['APN']],
                    [
                        'mcr' => $parcel['MCR'],
                        'subdivision_name' => $parcel['SubdivisonName'],
                        'section_township_range' => $parcel['SectionTownshipRange'],
                        'ownership' => $parcel['Ownership'],
                        'situs_address' => $parcel['SitusAddress'],
                        'situs_city' => $parcel['SitusCity'],
                        'situs_zip' => $parcel['SitusZip'],
                        'property_type' => $parcel['PropertyType'],
                        'rental_id' => $parcel['RentalID']
                    ]
                );
            }


            $page++;
        } while (!empty($data));
    }

    private function getApns()
    {
        return Str::pluck('apn');
    }

    public function getApnsFromArray()
    {
        $apnBook = [
//            '12322',
//            '12327',
//            '13037',
//            '13038',
//            '13040',
//            '13041',
//            '13042',
//            '13107',
//            '13133',
//            '13134',
//            '13135',
//            '13139',
//            '13141',
//            '13142',
//            '13148',
//            '13153',
//            '13263',
//            '13267',
//            '13323',
//            '13325',
//            '13331',
//            '13334',
//            '13336',
//            '13351',
//            '13425',
//            '13426',
//            '13540',
//            '13541',
//            '13542',
//            '13543',
//            '13910',
//            '13911',
//            '13912',
//            '13949',
//            '14063',
//            '14064',
//            '14065',
//            '14066',
//            '14067',
//            '30036',
//            '30094',
//            '30095',
//            '30097',
//            '30103',
//            '30107',
//            '30129',
//            '30141',
//            '30147',
//            '30148',
//            '30149',
//            '30152',
//            '30153',
//            '30154',
//            '30157',
//            '30158',
//            '30159',
//            '30161',
//            '30163',
//            '30164',
//            '30166',
//            '30167',
//            '30169',
//            '30170',
//            '30175',
//            '30176',
//            '30177',
//            '30178',
//            '30179',
//            '30180',
//            '30181',
//            '30187',
//            '30190',
//            '30191',
//            '30197',
//            '30204',
//            '30205',
//            '30206',
//            '30207',
//            '30209',
//            '30211',
//            '30212',
//            '30213',
//            '30214',
//            '30215',
//            '30219',
//            '30220',
//            '30224',
//            '30225',
//            '30226',
//            '30227',
            '30228',
            '30229',
            '30230',
            '30232',
            '30233',
            '30234',
            '30235',
            '30237',
            '30238',
            '30239',
            '30240',
            '30241',
            '30242',
            '30243',
            '30244',
            '30245',
            '30246',
            '30247',
            '30248',
            '30249',
            '30251',
            '30252',
            '30256',
            '30257',
            '30259',
            '30262',
            '30263',
            '30264',
            '30265',
            '30266',
            '30267',
            '30268',
            '30269',
            '30270',
            '30271',
            '30273',
            '30274',
            '30277',
            '30278',
            '30279',
            '30280',
            '30281',
            '30282',
            '30283',
            '30284',
            '30285',
            '30287',
            '30288',
            '30289',
            '30290',
            '30292',
            '30293',
            '30295',
            '30296',
            '30297',
            '30298',
            '30301',
            '30303',
            '30304',
            '30305',
            '30306',
            '30308',
            '30309',
            '30310',
            '30311',
            '30314',
            '30315',
            '30316',
            '30317',
            '30318',
            '30319',
            '30322',
            '30323',
            '30324',
            '30325',
            '30326',
            '30327',
            '30328',
            '30329',
            '30330',
            '30331',
            '30332',
            '30333',
            '30334',
            '30335',
            '30336',
            '30338',
            '30341',
            '30342',
            '30343',
            '30344',
            '30345',
            '30346',
            '30347',
            '30349',
            '30353',
            '30354',
            '30355',
            '30356',
            '30357',
            '30358',
            '30362',
            '30363',
            '30368',
            '30371',
            '30374',
            '30375',
            '30376',
            '30377',
            '30379',
            '30380',
            '30385',
            '30387',
            '30391',
            '30392',
            '30393',
            '30409',
            '30410',
            '30411',
            '30412',
            '30413',
            '30419',
            '30421',
            '30422',
            '30423',
            '30424',
            '30425',
            '30426',
            '30441',
            '30442',
            '30443',
            '30444',
            '30445',
            '30446',
            '30452',
            '30453',
            '30454',
            '30455',
            '30456',
            '30457',
            '30458',
            '30459',
            '30459',
            '30461',
            '30469',
            '30470',
            '30471',
            '30472',
            '30473',
            '30474',
            '30474',
            '30475',
            '30476',
            '30477',
            '30478',
            '30479',
            '30480',
            '30481',
            '30482',
            '30483',
            '30484',
            '30485',
            '30486',
            '30488',
            '30490',
            '30494',
            '30495',
            '30496',
            '30497',
            '30502',
            '30504',
            '30505',
            '30507',
            '30601',
            '30602',
            '30603',
            '30606',
            '30607',
            '30803',
            '30806',
            '30807',
            '30808',
            '30810',
            '30901',
            '30903',
            '30904',
            '30911',
            '30912',
            '30913',
            '30914',
            '30917',
            '30918',
            '30920',
            '30921',
            '30925',
            '30927',
            '30929',
            '30931',
            '31001',
            '31002',
            '31004',
            '31005',
            '31006',
            '31007',
            '31008',
            '31010',
            '31011',
            '31012',
            '31101',
            '31102',
            '31301',
            '31301',
            '31305',
            '31305',
            '31306',
            '31307',
            '31308',
            '31311',
            '31317',
            '31318',
            '31319',
            '31320',
            '31322',
            '31323',
            '31324',
            '31326',
            '31331',
            '31333',
            '31334',
        ];


        foreach ($apnBook as $apn) {
            $apns = self::generateApnArray($apn);
            self::fetchParcelDetails($apns);
        }

    }

    private function generateApnArray($apn)
    {
        $apnArray = [];
        for ($i = 0; $i < 1000; $i++) {

            if ($i < 10) {
                array_push($apnArray, $apn . '00' . $i);
            } else if ($i < 100) {
                array_push($apnArray, $apn . '0' . $i);
            } else {
                array_push($apnArray, $apn . $i);
            }
        }
        return $apnArray;
    }

    private function fetchParcelDetails($apns)
    {
//        $apns = self::getApns();
//        $apns = self::getApnsFromArray();

        foreach ($apns as $apn) {
            $this->fetchResidentialDetails($apn);
        }
    }

    private function fetchResidentialDetails($apn)
    {
        $response = Http::withHeaders($this->authHeader)
            ->get("https://mcassessor.maricopa.gov/parcel/{$apn}/residential-details");

        $data = $response->json();

        if ((count($data) === 0) || (!isset($data['Pool']) | $data['Pool'] <= 0)) {
            return;
        }

//        $strRecord = Str::where('apn', $apn)->first();

//        if ($strRecord) {
            ResidentialDetail::updateOrCreate(
                ['apn' => $apn],
                [
                    'construction_year' => $data['ConstructionYear'] ?? null,
                    'original_construction_year' => $data['OriginalConstructionYear'] ?? null,
                    'lot_size' => $data['LotSize'] ?? null,
                    'livable_space' => $data['LivableSpace'] ?? null,
                    'detached_livable_sqft' => $data['Detached_Livable_sqft'] ?? null,
                    'assessor_market' => $data['AssessorMarket'] ?? null,
                    'area_neighborhood' => $data['AreaNeighborhood'] ?? null,
                    'pool' => $data['Pool'] ?? null,
                    'pe_improvement_quality_grade' => $data['PEImprovementQualityGrade'] ?? null,
                    'improvement_quality_grade' => $data['ImprovementQualityGrade'] ?? null,
                    'number_of_covered_patios' => $data['NumberOfCoveredPatios'] ?? null,
                    'number_of_uncovered_patios' => $data['NumberOfUncoveredPatios'] ?? null,
                    'number_of_patios' => $data['NumberOfPatios'] ?? null,
                    'patio_type' => $data['PatioType'] ?? null,
                    'exterior_walls' => $data['ExteriorWalls'] ?? null,
                    'roof_type' => $data['RoofType'] ?? null,
                    'bath_fixtures' => $data['BathFixtures'] ?? null,
                    'cooling' => $data['Cooling'] ?? null,
                    'heating' => $data['Heating'] ?? null,
                    'number_of_garages' => $data['NumberOfGarages'] ?? null,
                    'number_of_carports' => $data['NumberOfCarports'] ?? null,
                    'parking_details' => json_encode($data['ParkingDetails'] ?? []),
                    'locational_factors' => $data['LocationalFactors'] ?? null,
                    'other_structures' => $data['OtherStructures'] ?? null,
                    'parking_type' => $data['ParkingType'] ?? null,
                    'covered_parking' => $data['CoveredParking'] ?? null,
                    'locational_characteristics' => $data['LocationalCharacteristics'] ?? null,
                    'physical_condition' => $data['PhysicalCondition'] ?? null
                ]
            );

            $this->fetchOwnerDetails($apn);
            $this->fetchPropertyValuations($apn);
//        }
    }

    private function fetchOwnerDetails($apn)
    {
        $response = Http::withHeaders($this->authHeader)
            ->get("https://mcassessor.maricopa.gov/parcel/{$apn}/owner-details");

        $data = $response->json();

        Owner::updateOrCreate(
            ['apn' => $apn],
            [
                'owner_id' => $data['OwnerID'] ?? null,
                'ownership' => $data['Ownership'] ?? null,
                'in_care_of' => $data['InCareOf'] ?? null,
                'conto_ownership' => $data['ContoOwnership'] ?? null,
                'mailing_address_1' => $data['MailingAddress1'] ?? null,
                'mailing_address_2' => $data['MailingAddress2'] ?? null,
                'mailing_city' => $data['MailingCity'] ?? null,
                'mailing_state' => $data['MailingState'] ?? null,
                'mailing_zip' => $data['MailingZip'] ?? null,
                'mailing_country' => $data['MailingCountry'] ?? null,
                'most_current_deed' => $data['MostCurrentDeed'] ?? null,
                'deed_date' => isset($data['DeedDate']) ? date('Y-m-d', strtotime($data['DeedDate'])) : null,
                'deed_type' => $data['DeedType'] ?? null,
                'sale_price' => isset($data['SalePrice']) ? (float)$data['SalePrice'] : null,
                'sale_date' => isset($data['SaleDate']) ? date('Y-m-d', strtotime($data['SaleDate'])) : null,
                'redacted' => $data['Redacted'] ?? false,
                'full_mailing_address' => $data['FullMailingAddress'] ?? null,
                'deed_type_id' => $data['DeedTypeID'] ?? null
            ]
        );
    }

    private function fetchPropertyValuations($apn)
    {
        $response = Http::withHeaders($this->authHeader)
            ->get("https://mcassessor.maricopa.gov/parcel/{$apn}/valuations");

        $data = $response->json();

        foreach ($data as $valuation) {
            Valuation::updateOrCreate(
                [
                    'apn' => $apn,
                    'tax_year' => $valuation['TaxYear'] ?? null
                ],
                [
                    'full_cash_value' => isset($valuation['FullCashValue']) ? (float)str_replace(',', '', $valuation['FullCashValue']) : null,
                    'limited_property_value' => isset($valuation['LimitedPropertyValue']) ? (float)str_replace(',', '', $valuation['LimitedPropertyValue']) : null,
                    'legal_classification_code' => $valuation['LegalClassificationCode'] ?? null,
                    'legal_classification' => $valuation['LegalClassification'] ?? null,
                    'assessment_ratio_percentage' => isset($valuation['AssessmentRatioPercentage']) ? (float)$valuation['AssessmentRatioPercentage'] : null,
                    'assessed_fcv' => $valuation['AssessedFCV'] ?? null, // Keeping as string since it might have 'na'
                    'assessed_lpv' => isset($valuation['AssessedLPV']) ? trim($valuation['AssessedLPV']) : null,
                    'valuation_source' => $valuation['ValuationSource'] ?? null,
                    'pe_prop_use_desc' => $valuation['PEPropUseDesc'] ?? null,
                    'property_use_code' => $valuation['PropertyUseCode'] ?? null,
                    'tax_area_code' => $valuation['TaxAreaCode'] ?? null,
                    'notice_of_change_indc' => $valuation['NoticeOfChangeIndc'] ?? null
                ]
            );
        }
    }
}

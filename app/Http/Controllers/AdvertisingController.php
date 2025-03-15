<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Str;
use App\Models\ResidentialDetail;
use App\Models\Owner;
use App\Models\Valuation;
use function Psy\debug;
use Illuminate\Support\Facades\Log;

class AdvertisingController extends Controller
{
    private $authHeader = [
        'Authorization' => '1034e75e-89c6-11e8-8a04-00155da2c015'
    ];

    private $townships = [

//        '-2S-6E',
//        '-2S-7E',
//        '-2S-8E',
//        '-1S-3E',

//        '-1S-4E',
//        '-1S-5E',
//        '-1S-6E',
//        '-1S-7E',

//        '-1N-3E',
        '-1N-4E',
        '-1N-5E',
        '-1N-6E',
        '-1N-7E',
        '-1N-8E',
        '-2N-4E',
        '-3N-4E'
    ];

    public function updateMailingList()
    {
//        dd('updateMailing');

//        self::updateStr('-2S-6E', 32, 39819);
//        self::updateStr('-2S-7E', 32, 66108);
//        self::updateStr('-1S-3E', 36, 162099);
//        self::updateStr('-1S-4E', 31, 186866);
//        $this->fetchTownshipParcels('26-1S-5E', 67);
//        self::updateStr('-1S-5E', 36, 223735);
        $startingId = 248750;
        self::updateStr('-1S-6E', 13, $startingId);
        self::updateStr('-1S-7E', 3, $startingId);
        self::updateStr('-1N-3E', 2, $startingId);
        self::updateStr('-1N-4E', 1, $startingId);

        foreach ($this->townships as $township) {
            for ($i = 1; $i < 37; $i++) {
                $str = $i . $township;
                $this->fetchTownshipParcels($str);
                $apns = self::getApns($str);
                self::fetchParcelDetails($apns);
                Log::debug("$str is done");
            }
        }

        return response()->json(['message' => 'Mailing list updated successfully.']);
    }

    private function updateStr($township, $startingSection, $startingId)
    {

        $str = $startingSection . $township;
        $apns = self::getApns($str, $startingId);
        self::fetchParcelDetails($apns);
        Log::debug("$str is done");
        for ($i = $startingSection + 1; $i < 37; $i++) {
            $str = $i . $township;
            $this->fetchTownshipParcels($str);
            $apns = self::getApns($str);
            self::fetchParcelDetails($apns);
            Log::debug("$str is done");
        }
    }

    private function fetchTownshipParcels($township, $page = 1)
    {
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

    private function getApns($str, $id = 0)
    {
        return Str::select(['apn'])
            ->where('section_township_range', $str)
            ->where('id', '>', $id)
            ->get();
    }

    private function fetchParcelDetails($apns)
    {
        foreach ($apns as $apn) {
            $this->fetchResidentialDetails($apn);
        }
    }

    private function fetchResidentialDetails($apn)
    {
        $response = Http::withHeaders($this->authHeader)
            ->get("https://mcassessor.maricopa.gov/parcel/{$apn->apn}/residential-details");

        $data = $response->json();

//        if ($apn->apn === '31401522') {
//            Log::info('31401522');
//        }

        if (is_null($data)) {
//            Log::debug($apn->apn . " :: is null" );
            return;
        }

        if (count($data) === 0) {
//            Log::debug($apn->apn . " :: is empty" );
            return;
        }

        if (!$data['Pool']) {
            return;
        }

        if (!isset($data['Pool']) | $data['Pool'] <= 0) {
            return;
        }

        Log::debug($apn->apn . " :: " . $data['Pool']);

        $strRecord = Str::where('apn', $apn->apn)->first();

        ResidentialDetail::updateOrCreate(
            ['apn_id' => $strRecord->id],
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

        $this->fetchOwnerDetails($apn, $strRecord->id);
        $this->fetchPropertyValuations($apn, $strRecord->id);
//        }
    }

    private function fetchOwnerDetails($apn, $apn_id)
    {
        $response = Http::withHeaders($this->authHeader)
            ->get("https://mcassessor.maricopa.gov/parcel/{$apn->apn}/owner-details");

        $data = $response->json();

        Owner::updateOrCreate(
            ['apn_id' => $apn_id],
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

    private function fetchPropertyValuations($apn, $apn_id)
    {
        $response = Http::withHeaders($this->authHeader)
            ->get("https://mcassessor.maricopa.gov/parcel/{$apn->apn}/valuations");

        $data = $response->json();

        foreach ($data as $valuation) {
            Valuation::updateOrCreate(
                [
                    'apn_id' => $apn_id,
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

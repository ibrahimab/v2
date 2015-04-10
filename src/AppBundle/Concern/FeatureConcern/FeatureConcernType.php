<?php
namespace AppBundle\Concern\FeatureConcern;

class FeatureConcernType extends FeatureConcern
{   
    const FEATURE_WINTER_CATERING                  = 1;
    const FEATURE_WINTER_SKI_RUN                   = 2;
    const FEATURE_WINTER_SAUNA_PRIVATE             = 3;
    const FEATURE_WINTER_SWIMMING_POOL_PRIVATE     = 4;
    const FEATURE_WINTER_CHILD_FRIENDLY            = 5;
    const FEATURE_WINTER_LARGE_GROUPS              = 6;
    const FEATURE_WINTER_PRICE_CONSCIOUS           = 7;
    const FEATURE_WINTER_TOP_SELECTION             = 8;
    const FEATURE_WINTER_WINTER_WELLNESS           = 9;
    const FEATURE_WINTER_FIREPLACE                 = 10;
    const FEATURE_WINTER_PETS_ALLOWED              = 11;
    const FEATURE_WINTER_ALLERGY_FREE              = 12;
    const FEATURE_WINTER_RENT_SUNDAY               = 13;
    const FEATURE_WINTER_CHALET_FOR_TWO            = 14;
    const FEATURE_WINTER_SPECIAL                   = 15;
    const FEATURE_WINTER_WASHING_MACHINE           = 16;
    const FEATURE_WINTER_BALCONY                   = 17;
    const FEATURE_WINTER_BALCONY_TERRACE           = 18;
    const FEATURE_WINTER_PETS_DISALLOWED           = 19;
    const FEATURE_WINTER_INTERNET                  = 20;
    const FEATURE_WINTER_CHARMING_CHALET           = 21;
    const FEATURE_WINTER_INTERNET_WIFI             = 22;
    const FEATURE_WINTER_JACUZZI                   = 23;
    
    const FEATURE_SUMMER_CHALET_FOR_TWO            = 1;
    const FEATURE_SUMMER_CHALET_DETACHED           = 2;
    // const FEATURE_SUMMER_SAUNA_PRIVATE          = 3;
    const FEATURE_SUMMER_SWIMMING_POOL_PRIVATE     = 4;
    const FEATURE_SUMMER_CHILD_FRIENDLY            = 5;
    const FEATURE_SUMMER_LARGE_GROUPS              = 6;
    // const FEATURE_SUMMER_PRICE_CONSCIOUS        = 7;
    // const FEATURE_SUMMER_TOP_SELECTION          = 8;
    // const FEATURE_SUMMER_TOUCH_OF_WELLNESS      = 9;
    // const FEATURE_SUMMER_FIREPLACE              = 10;
    const FEATURE_SUMMER_PETS_ALLOWED              = 11;
    // const FEATURE_SUMMER_ALLERGY_FREE           = 12;
    // const FEATURE_SUMMER_RENT_SUNDAY            = 13;
    const FEATURE_SUMMER_CHALET_ATTACHED           = 14;
    const FEATURE_SUMMER_CHALET_DETACHED_MULTIPLE  = 15;
    const FEATURE_SUMMER_SPECIAL                   = 16;
    const FEATURE_SUMMER_GARDEN_TERRACE_PRIVATE    = 17;
    const FEATURE_SUMMER_WASHING_MACHINE           = 18;
    const FEATURE_SUMMER_BALCONY_PRIVATE           = 19;
    const FEATURE_SUMMER_GARDEN_TERRACE_BALCONY    = 20;
    // const FEATURE_SUMMER_PETS_DISALLOWED        = 21;
    const FEATURE_SUMMER_INTERNET                  = 22;
    const FEATURE_SUMMER_GARDEN_FENCED             = 23;
    const FEATURE_SUMMER_INTERNET_WIFI             = 24;
    const FEATURE_SUMMER_GROUND_LEVEL              = 25;
    const FEATURE_SUMMER_NOT_GROUND_LEVEL          = 26;
    const FEATURE_SUMMER_AIRCONDITIONING           = 27;
    const FEATURE_SUMMER_BARBECUE_COMMON           = 28;
    const FEATURE_SUMMER_BARBECUE_PRIVATE          = 29;
    const FEATURE_SUMMER_CHILD_BED                 = 30;
    const FEATURE_SUMMER_JACUZZI                   = 31;
    const FEATURE_SUMMER_CHILD_SEAT                = 32;
    const FEATURE_SUMMER_DISHWASHER                = 33;
    
    /**
     * @var array
     */
    protected $identifiers = [
        
        self::FEATURE_TYPE_WINTER => [
        
            self::FEATURE_WINTER_CATERING                 => 'catering',
            self::FEATURE_WINTER_SKI_RUN                  => 'ski-run',
            self::FEATURE_WINTER_SAUNA_PRIVATE            => 'sauna-private',
            self::FEATURE_WINTER_SWIMMING_POOL_PRIVATE    => 'swimming-pool-private',
            self::FEATURE_WINTER_CHILD_FRIENDLY           => 'child-friendly',
            self::FEATURE_WINTER_LARGE_GROUPS             => 'large-groups',
            self::FEATURE_WINTER_PRICE_CONSCIOUS          => 'price-conscious',
            self::FEATURE_WINTER_TOP_SELECTION            => 'top-selection',
            self::FEATURE_WINTER_WINTER_WELLNESS          => 'winter-wellness',
            self::FEATURE_WINTER_FIREPLACE                => 'fireplace',
            self::FEATURE_WINTER_PETS_ALLOWED             => 'pets-allowed',
            self::FEATURE_WINTER_ALLERGY_FREE             => 'allergy-free',
            self::FEATURE_WINTER_RENT_SUNDAY              => 'rent-sunday',
            self::FEATURE_WINTER_CHALET_FOR_TWO           => 'chalet-for-two',
            self::FEATURE_WINTER_SPECIAL                  => 'special',
            self::FEATURE_WINTER_WASHING_MACHINE          => 'waching-machine',
            self::FEATURE_WINTER_BALCONY                  => 'balcony',
            self::FEATURE_WINTER_BALCONY_TERRACE          => 'balcony-terrace',
            self::FEATURE_WINTER_PETS_DISALLOWED          => 'pets-disallowed',
            self::FEATURE_WINTER_INTERNET                 => 'internet',
            self::FEATURE_WINTER_CHARMING_CHALET          => 'charming-chalet',
            self::FEATURE_WINTER_INTERNET_WIFI            => 'internet-wifi',
            self::FEATURE_WINTER_JACUZZI                  => 'jacuzzi',
        ],
        
        self::FEATURE_TYPE_SUMMER => [
            
            self::FEATURE_SUMMER_CHALET_FOR_TWO           => 'chalet-for-two',
            self::FEATURE_SUMMER_CHALET_DETACHED          => 'chalet-detached',
            self::FEATURE_SUMMER_SAUNA_PRIVATE            => 'sauna-private',
            self::FEATURE_SUMMER_SWIMMING_POOL_PRIVATE    => 'swimming-pool-private',
            self::FEATURE_SUMMER_CHILD_FRIENDLY           => 'child-friendly',
            self::FEATURE_SUMMER_LARGE_GROUPS             => 'large-groups',
            self::FEATURE_SUMMER_PRICE_CONSCIOUS          => 'price-conscious',
            self::FEATURE_SUMMER_TOP_SELECTION            => 'top-selection',
            self::FEATURE_SUMMER_TOUCH_OF_WELLNESS        => 'touch-of-wellness',
            self::FEATURE_SUMMER_FIREPLACE                => 'fireplace',
            self::FEATURE_SUMMER_PETS_ALLOWED             => 'pets-allowed',
            self::FEATURE_SUMMER_ALLERGY_FREE             => 'allergy-free',
            self::FEATURE_SUMMER_RENT_SUNDAY              => 'rent-sunday',
            self::FEATURE_SUMMER_CHALET_ATTACHED          => 'chalet-attached',
            self::FEATURE_SUMMER_CHALET_DETACHED_MULTIPLE => 'chalet-detached-multiple',
            self::FEATURE_SUMMER_SPECIAL                  => 'special',
            self::FEATURE_SUMMER_GARDEN_TERRACE_PRIVATE   => 'terrace-private',
            self::FEATURE_SUMMER_WASHING_MACHINE          => 'washing-machine',
            self::FEATURE_SUMMER_BALCONY_PRIVATE          => 'balcony-private',
            self::FEATURE_SUMMER_GARDEN_TERRACE_BALCONY   => 'garden-terrace-balcony',
            self::FEATURE_SUMMER_PETS_DISALLOWED          => 'pets-disallowed',
            self::FEATURE_SUMMER_INTERNET                 => 'internet',
            self::FEATURE_SUMMER_GARDEN_FENCED            => 'garden-fenced',
            self::FEATURE_SUMMER_INTERNET_WIFI            => 'internet-wifi',
            self::FEATURE_SUMMER_GROUND_LEVEL             => 'ground-level',
            self::FEATURE_SUMMER_NOT_GROUND_LEVEL         => 'not-ground-level',
            self::FEATURE_SUMMER_AIRCONDITIONING          => 'airconditioning',
            self::FEATURE_SUMMER_BARBECUE_COMMON          => 'barbecue-common',
            self::FEATURE_SUMMER_BARBECUE_PRIVATE         => 'barbecue-private',
            self::FEATURE_SUMMER_CHILD_BED                => 'child-bed',
            self::FEATURE_SUMMER_JACUZZI                  => 'jacuzzi',
            self::FEATURE_SUMMER_CHILD_SEAT               => 'child-seat',
            self::FEATURE_SUMMER_DISHWASHER               => 'dishwasher',
        ]
    ];
}
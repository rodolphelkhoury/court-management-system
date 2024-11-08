<?php

namespace App\Domain\SurfaceType\Enums;

enum SurfaceTypeName: string
{
    case HARD_ACRYLIC = 'hard_acrylic';
    case HARD_CONCRETE = 'hard_concrete';
    case HARD_ASPHALT = 'hard_asphalt';
    case GRASS_NATURAL = 'grass_natural';
    case GRASS_SYNTHETIC = 'grass_synthetic';
    case CLAY_RED = 'clay_red';
    case CLAY_GREEN = 'clay_green';
    case CARPET_SYNTHETIC = 'carpet_synthetic';
    case CARPET_PORTABLE = 'carpet_portable';
    case SYNTHETIC_POLYURETHANE = 'synthetic_polyurethane';
    case SYNTHETIC_RUBBER = 'synthetic_rubber';
    case SYNTHETIC_EPDM = 'synthetic_epdm';
    case SAND = 'sand';
    case WOOD_HARDWOOD = 'wood_hardwood';
    case WOOD_PARQUET = 'wood_parquet';
    case OTHER_CUSHIONED = 'other_cushioned';
    case OTHER_HYBRID = 'other_hybrid';
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\CourtType\Enums\CourtTypeName;
use App\Domain\SurfaceType\Enums\SurfaceTypeName;
use App\Models\CourtType;
use App\Models\SurfaceType;

class CourtAndSurfaceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create CourtTypes if they don't already exist
        foreach (CourtTypeName::cases() as $courtTypeEnum) {
            CourtType::firstOrCreate(['name' => $courtTypeEnum->value]);
        }

        // Create SurfaceTypes if they don't already exist
        foreach (SurfaceTypeName::cases() as $surfaceTypeEnum) {
            SurfaceType::firstOrCreate(['name' => $surfaceTypeEnum->value]);
        }

        // CourtType to SurfaceType mapping using enums
        $courtTypeToSurfaceTypes = [
            CourtTypeName::TENNIS->value => [
                SurfaceTypeName::HARD_ACRYLIC->value, 
                SurfaceTypeName::HARD_CONCRETE->value, 
                SurfaceTypeName::CLAY_RED->value, 
                SurfaceTypeName::CLAY_GREEN->value, 
                SurfaceTypeName::GRASS_NATURAL->value, 
                SurfaceTypeName::GRASS_SYNTHETIC->value
            ],
            CourtTypeName::BASKETBALL->value => [
                SurfaceTypeName::HARD_ACRYLIC->value, 
                SurfaceTypeName::HARD_CONCRETE->value, 
                SurfaceTypeName::HARD_ASPHALT->value
            ],
            CourtTypeName::VOLLEYBALL->value => [
                SurfaceTypeName::HARD_ACRYLIC->value, 
                SurfaceTypeName::HARD_CONCRETE->value, 
                SurfaceTypeName::GRASS_NATURAL->value
            ],
            CourtTypeName::BADMINTON->value => [
                SurfaceTypeName::WOOD_HARDWOOD->value, 
                SurfaceTypeName::WOOD_PARQUET->value, 
                SurfaceTypeName::CARPET_SYNTHETIC->value
            ],
            CourtTypeName::SQUASH->value => [
                SurfaceTypeName::WOOD_HARDWOOD->value
            ],
            CourtTypeName::TABLE_TENNIS->value => [
                SurfaceTypeName::WOOD_HARDWOOD->value, 
                SurfaceTypeName::WOOD_PARQUET->value
            ],
            CourtTypeName::SOCCER->value => [
                SurfaceTypeName::GRASS_NATURAL->value, 
                SurfaceTypeName::GRASS_SYNTHETIC->value, 
                SurfaceTypeName::SYNTHETIC_RUBBER->value
            ],
            CourtTypeName::FUTSAL->value => [
                SurfaceTypeName::HARD_ACRYLIC->value, 
                SurfaceTypeName::HARD_CONCRETE->value, 
                SurfaceTypeName::SYNTHETIC_RUBBER->value
            ],
            CourtTypeName::HANDBALL->value => [
                SurfaceTypeName::HARD_ACRYLIC->value, 
                SurfaceTypeName::HARD_CONCRETE->value
            ],
            CourtTypeName::NETBALL->value => [
                SurfaceTypeName::HARD_ACRYLIC->value, 
                SurfaceTypeName::HARD_CONCRETE->value
            ],
            CourtTypeName::PICKLEBALL->value => [
                SurfaceTypeName::HARD_ACRYLIC->value, 
                SurfaceTypeName::HARD_CONCRETE->value, 
                SurfaceTypeName::WOOD_HARDWOOD->value
            ],
            CourtTypeName::HOCKEY_FIELD->value => [
                SurfaceTypeName::GRASS_NATURAL->value, 
                SurfaceTypeName::SYNTHETIC_POLYURETHANE->value
            ],
            CourtTypeName::HOCKEY_ROLLER->value => [
                SurfaceTypeName::SYNTHETIC_RUBBER->value
            ],
            CourtTypeName::CRICKET_PRACTICE->value => [
                SurfaceTypeName::GRASS_NATURAL->value, 
                SurfaceTypeName::SYNTHETIC_POLYURETHANE->value
            ],
            CourtTypeName::MULTISPORT->value => [
                SurfaceTypeName::HARD_ACRYLIC->value, 
                SurfaceTypeName::HARD_CONCRETE->value, 
                SurfaceTypeName::GRASS_NATURAL->value
            ],
            CourtTypeName::PADEL->value => [
                SurfaceTypeName::SYNTHETIC_POLYURETHANE->value, 
                SurfaceTypeName::SYNTHETIC_RUBBER->value
            ],
            CourtTypeName::RACQUETBALL->value => [
                SurfaceTypeName::WOOD_HARDWOOD->value, 
                SurfaceTypeName::SYNTHETIC_RUBBER->value
            ],
        ];

        foreach ($courtTypeToSurfaceTypes as $courtTypeName => $surfaceTypeNames) {
            $courtType = CourtType::where('name', $courtTypeName)->first();
            
            foreach ($surfaceTypeNames as $surfaceTypeName) {
                $surfaceType = SurfaceType::where('name', $surfaceTypeName)->first();
                
                if ($courtType && $surfaceType) {
                    $courtType->surface_types()->attach($surfaceType->id);
                }
            }
        }
    }
}

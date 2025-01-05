<?php

namespace Database\Seeders;

use App\Models\Allergy;
use App\Models\DietaryRequirement;
use App\Models\Immunisation;
use App\Models\Nursery;
use Illuminate\Database\Seeder;

class HealthDataSeeder extends Seeder
{
    public function run(): void
    {
        $nurseries = Nursery::all();

        foreach ($nurseries as $nursery) {
            // Common Allergies
            $allergies = [
                [
                    'name' => 'Peanuts',
                    'description' => 'Allergy to peanuts and peanut-based products',
                ],
                [
                    'name' => 'Milk',
                    'description' => 'Dairy and lactose intolerance',
                ],
                [
                    'name' => 'Eggs',
                    'description' => 'Allergy to eggs and egg-based products',
                ],
                [
                    'name' => 'Tree Nuts',
                    'description' => 'Allergies to various tree nuts (almonds, cashews, etc.)',
                ],
                [
                    'name' => 'Wheat',
                    'description' => 'Gluten and wheat-based products',
                ],
                [
                    'name' => 'Soy',
                    'description' => 'Soy and soy-based products',
                ],
            ];

            foreach ($allergies as $allergy) {
                Allergy::create([
                    'nursery_id' => $nursery->id,
                    'name' => $allergy['name'],
                    'description' => $allergy['description'],
                ]);
            }

            // Common Dietary Requirements
            $dietaryRequirements = [
                [
                    'name' => 'Vegetarian',
                    'description' => 'No meat products',
                ],
                [
                    'name' => 'Vegan',
                    'description' => 'No animal products',
                ],
                [
                    'name' => 'Halal',
                    'description' => 'Halal certified foods only',
                ],
                [
                    'name' => 'Kosher',
                    'description' => 'Kosher certified foods only',
                ],
                [
                    'name' => 'Gluten Free',
                    'description' => 'No gluten or wheat products',
                ],
                [
                    'name' => 'Dairy Free',
                    'description' => 'No dairy products',
                ],
            ];

            foreach ($dietaryRequirements as $requirement) {
                DietaryRequirement::create([
                    'nursery_id' => $nursery->id,
                    'name' => $requirement['name'],
                    'description' => $requirement['description'],
                ]);
            }

            // Common Immunisations (based on UK schedule)
            $immunisations = [
                [
                    'name' => '6-in-1 Vaccine',
                    'description' => 'Protects against: diphtheria, tetanus, whooping cough, polio, Hib (Haemophilus influenzae type b) and hepatitis B',
                    'requires_dates' => true,
                ],
                [
                    'name' => 'Pneumococcal (PCV)',
                    'description' => 'Protects against pneumococcal infections',
                    'requires_dates' => true,
                ],
                [
                    'name' => 'Rotavirus',
                    'description' => 'Protects against rotavirus infection',
                    'requires_dates' => true,
                ],
                [
                    'name' => 'MenB',
                    'description' => 'Protects against meningitis B',
                    'requires_dates' => true,
                ],
                [
                    'name' => 'Hib/MenC',
                    'description' => 'Protects against Haemophilus influenzae type b (Hib) and meningitis C',
                    'requires_dates' => true,
                ],
                [
                    'name' => 'MMR',
                    'description' => 'Protects against measles, mumps and rubella',
                    'requires_dates' => true,
                ],
                [
                    'name' => 'Flu Vaccine',
                    'description' => 'Annual flu protection',
                    'requires_dates' => true,
                ],
            ];

            foreach ($immunisations as $immunisation) {
                Immunisation::create([
                    'nursery_id' => $nursery->id,
                    'name' => $immunisation['name'],
                    'description' => $immunisation['description'],
                    'requires_dates' => $immunisation['requires_dates'],
                ]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Composer;
use App\Models\Movement;
use App\Models\Piece;
use App\Models\TimeSignature;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        TimeSignature::insert([
            ['count' => 'common', 'note' => null],
            ['count' => 'allabreve', 'note' => null],
            ['count' => '2','note' => '1'],
            ['count' => '2', 'note' => '2'],
            ['count' => '2', 'note' => '4'],
            ['count' => '2', 'note' => '8'],
            ['count' => '2', 'note' => '16'],
            ['count' => '2', 'note' => '32'],
            ['count' => '3', 'note' => '2'],
            ['count' => '3', 'note' => '4'],
            ['count' => '3', 'note' => '8'],
            ['count' => '3', 'note' => '16'],
            ['count' => '3', 'note' => '32'],
            ['count' => '4', 'note' => '2'],
            ['count' => '4', 'note' => '4'],
            ['count' => '4', 'note' => '8'],
            ['count' => '4', 'note' => '16'],
            ['count' => '4', 'note' => '32'],
            ['count' => '5', 'note' => '4'],
            ['count' => '5', 'note' => '8'],
            ['count' => '6', 'note' => '4'],
            ['count' => '6', 'note' => '8'],
            ['count' => '6', 'note' => '16'],
            ['count' => '6', 'note' => '32'],
            ['count' => '7', 'note' => '4'],
            ['count' => '7', 'note' => '8'],
            ['count' => '8', 'note' => '8'],
            ['count' => '9', 'note' => '4'],
            ['count' => '9', 'note' => '8'],
            ['count' => '12', 'note' => '4'],
            ['count' => '12', 'note' => '8']
        ]);

        $composers = Composer::factory()->count(10)->create();
        $pieces = Piece::factory()
            ->count(10)
            ->state(new Sequence(
                fn ($sequence) => ['composer_id' => $composers->random()->id ]
            ))->create();
            
        foreach($pieces as $piece) {
            Movement::factory()
            ->count(random_int(2, 5))
            ->state(new Sequence(
                fn ($sequence) => ['piece_id' => $piece->id, 'time_signature_id' => TimeSignature::inRandomOrder()->first()]
            ))->create();
        }
        
    }
}

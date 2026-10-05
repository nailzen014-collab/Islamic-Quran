<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\MealPlan;
use App\Models\Recipe;
use App\Models\Review;
use App\Models\ShoppingItem;
use App\Models\User;
use App\Services\RecipeImporter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection as SupportCollection;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $recipes = app(RecipeImporter::class)->syncQuietly();

        if ($recipes->isEmpty()) {
            $this->command?->warn('Resep dari API tidak tersedia, memakai data contoh.');

            $recipes = Recipe::factory(12)->create();
        }

        $user = User::factory()->create([
            'name' => 'Nadia Pramesti',
            'email' => 'nadia@resep.id',
            'kitchen_name' => 'Dapur Senja',
            'bio' => 'Menyimpan resep cepat dan manis di satu tempat.',
            'avatar_color' => 'orange',
        ]);

        $second = User::factory()->create([
            'name' => 'Bagas Wicaksono',
            'email' => 'bagas@resep.id',
            'kitchen_name' => 'Kitchen Kos',
            'bio' => 'Sedang belajarcook di dapur kos.',
            'avatar_color' => 'sky',
        ]);

        $reviewers = collect([$user, $second]);

        $this->seedReviews($recipes, $reviewers);
        $this->seedCollections($user, $recipes);
        $this->seedMealPlans($user, $recipes);
        $this->seedShoppingList($user);
    }

    private function seedReviews(SupportCollection $recipes, SupportCollection $reviewers): void
    {
        foreach ($recipes->take(18) as $recipe) {
            foreach ($reviewers->random(random_int(1, 2)) as $index => $reviewer) {
                Review::create([
                    'recipe_id' => $recipe->id,
                    'user_id' => $reviewer->id,
                    'author_name' => $reviewer->name,
                    'rating' => random_int(4, 5),
                    'body' => $this->reviewBody($recipe),
                    'is_recipe_owner_cook' => $index === 0,
                ]);
            }
        }
    }

    private function reviewBody(Recipe $recipe): string
    {
        $total = $recipe->total_minutes;
        $count = count($recipe->ingredients ?? []);

        return [
            "Wajib masak ulang. Cuma {$total} menit, tapi rasanya di luar ekspektasi.",
            "Bahannya gampang dicari di pasar. {$count} bahan sudah cukup untuk makan malam.",
            "Ini jadi andalan kalau lagi malas masak tapi pengen yang cepat.",
            "Langkah-langkahnya jelas, tidak ada yang loncat-loncat. Sangat membantu.",
            "Lumayan cepat dan bahannya tidak mahal. Pasti masak lagi.",
        ][array_rand([0, 1, 2, 3, 4])];
    }

    private function seedCollections(User $user, SupportCollection $recipes): void
    {
        $blueprint = [
            ['name' => 'Cepat Saja', 'accent' => 'amber', 'description' => 'Resep di bawah 30 menit untuk hari yang sibuk.'],
            ['name' => 'Menu Makan Malam', 'accent' => 'rose', 'description' => 'Ide hidangan utama untuk makan malam keluarga.'],
            ['name' => 'Dessert House', 'accent' => 'fuchsia', 'description' => 'Manisan untuk menutup hidangan dengan manis.'],
        ];

        foreach ($blueprint as $data) {
            Collection::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'description' => $data['description'],
                'accent' => $data['accent'],
            ])->recipes()->sync($recipes->random(min(6, $recipes->count()))->pluck('id'));
        }
    }

    private function seedMealPlans(User $user, SupportCollection $recipes): void
    {
        $start = now()->startOfWeek();

        for ($day = 0; $day < 7; $day++) {
            foreach (MealPlan::SLOTS as $slot) {
                if (random_int(0, 10) < 4) {
                    continue;
                }

                MealPlan::create([
                    'user_id' => $user->id,
                    'recipe_id' => $recipes->random()->id,
                    'planned_for' => $start->copy()->addDays($day),
                    'slot' => $slot,
                    'servings' => random_int(2, 6),
                ]);
            }
        }
    }

    private function seedShoppingList(User $user): void
    {
        $items = [
            ['Beras 5 kg', 'Sembako'],
            ['Minyak goreng 2 L', 'Sembako'],
            ['Gula pasir 1 kg', 'Sembako'],
            ['Bawang merah 1 kg', 'Sayur'],
            ['Bawang putih 250 g', 'Sayur'],
            ['Tomat 1 kg', 'Sayur'],
            ['Kangkung 1 ikat', 'Sayur'],
            ['Ayam fillet 1 kg', 'Protein'],
            ['Ikan kerap 500 g', 'Protein'],
            ['Telur ayam 1 kg', 'Protein'],
            ['Susu UHT 1 L', 'Susu'],
            ['Keju cheddar 250 g', 'Susu'],
            ['Kecap manis 500 ml', 'Bumbu'],
            ['Sambal botol 3 pcs', 'Bumbu'],
            ['Sabun cuci piring', 'Rumah'],
            ['Kain lap', 'Rumah'],
        ];

        foreach ($items as [$name, $category]) {
            ShoppingItem::create([
                'user_id' => $user->id,
                'name' => $name,
                'category' => $category,
                'quantity' => 1,
                'is_checked' => random_int(0, 10) < 3,
            ]);
        }
    }
}